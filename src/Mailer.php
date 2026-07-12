<?php

namespace KrisRo\PhpRepublic;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use \KrisRo\PhpConfig\Config;
use KrisRo\Validator\Validator;
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Template;
use KrisRo\PhpRepublic\Arrays;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Debug;
use KrisRo\PhpRepublic\Strings;
use App\Models\Notification;
use App\Models\User;

class Mailer {

  private static $validator;

  private $mail = null;
  private $isHTML = true;
  private $config = true;
  private $language;

  private $valid = true;

  public function __construct(?string $language = null) {
    $this->config = Config::smtp();
    $this->language = $this->getLanguage($language);

    $this->mail = new PHPMailer(true);
    $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
    $this->mail->Encoding = PHPMailer::ENCODING_BASE64;

    $this->connect();
    $this->setHTML();
  }

  /**
   * Replace method for sendgrid api
   *
   * @param array $message
   *   $message['confirm'] = Template::users('Confirmation shown to user on browser.');
   *   $message['subject'] = Template::users('Email subject on __DOMAIN__', ['__DOMAIN__' => DOMAIN]);
   *   $message['message'] = '(Multiline) email body goes here';
   *   $message['to']['address'] = 'email@example.com';
   *   $message['to']['name'] = 'name for email@example.com';
   *   $message['from']['address'] = config::get('mail-contact');
   *   $message['from']['name'] = config::get('signature-support');
   *   $message['reply_to']['address'] = config::get('mail-support');
   *
   * @param bool|null $suppressConfirmation
   *
   * @return bool
   */
  public static function send(array $message, ?bool $suppressConfirmation = false, ?string $language = null): bool {
    if (!$language) {
      $language = Config::get('app/default_language');
    }

    // Debug::dump($message);

    $message['message'] = self::loadLayout($message['message'], $language);

    if(Config::_debug()) {
      Messages::test('This is a test environment. The mail was writen to file.');
      self::saveNotification($message);
      return self::mailToFile($message) ? true : !(['Failed to save mail to file']);
    }

    if (!strlen(trim($message['subject'] ?? ''))) {
      return false;
    }
    if (!Mailer::getValidator()->email($message['to']['address'] ?? '')) {
      return false;
    }
    if (!Mailer::getValidator()->email($message['from']['address'] ?? '')) {
      return false;
    }
    if (!Mailer::getValidator()->email($message['reply_to']['address'] ?? '')) {
      $message['reply_to']['address'] = $message['from']['address'];
    }

    if (!($message['to']['name'] ?? null)) {
      $message['to']['name'] = $message['to']['address'];
    }
    if (!($message['from']['name'] ?? null)) {
      $message['from']['name'] = $message['from']['address'];
    }
    if (!($message['reply_to']['name'] ?? null)) {
      $message['reply_to']['name'] = $message['reply_to']['address'];
    }

    $data = [
      'to' => $message['to']['name'] . ' <' . $message['to']['address'] . '>',
      'from' => $message['from']['name'] . ' <' . $message['from']['address'] . '>',
      'reply_to' => $message['reply_to']['address'],
      'subject' => $message['subject'],
      'message' => $message['message'],
    ];
    // Debug::log($data);die('gata');
    if (!empty($message['attachments'])) {
      $data['attachments'] = $message['attachments'];
    }

    $smtp = new Mailer($language);

    if (!$smtp->sendEmail($data)) {
      if (!$suppressConfirmation) {
        Messages::email('Sorry. Failed to send the email.');
      }

      return false;

    } else {
      self::saveNotification($message);

      if (!$suppressConfirmation) {
        Messages::email(isset($message['confirm']) ? $message['confirm'] : "Thank you. An email was sent to the supplied email.");
      }

      return true;
    }

    return false;
  }

  private static function saveNotification($message) {
    if (Session::user()) {
      return true;
    }

    $userId = Config::dbModel()->getUniqueUsersByEmail($message['to']['address'])->next()['id'] ?? null;
    if (!$userId) {
      return true;
    }

    return Config::dbModel()->setNotification(Notification::getStructure([
      'subject' => $message['subject'],
      'body' => $message['message'],
      'users_id' => $userId,
      'email' => $message['to']['address'],
      'from' => $message['from']['address'],
    ]));
  }

  public static function mailToFile($params) {
    if(!Config::_debug()) {
      return false;
    }

    $folder = APP_ROOT . DS . Config::_mail_folder();
    if (!file_exists($folder)) {
      mkdir($folder);
      chmod($folder, 0775); // this should run on developement only
    }

    return file_put_contents(
      $folder . Strings::slug((new \DateTime('@' . time()))->format('Y-m-d\Th-i-s') . '-' . $params['subject']) . '.html',
      $params['message']
    );
  }

  private static function getValidator() {
    if (!self::$validator && !(self::$validator = Config::validator())) {
      $validatorClass = Config::get('app/validator');
      self::$validator = new $validatorClass();
    }

    return self::$validator;
  }

  private function getLanguage($language): string {
    return in_array($language, Config::get('app/languages')) ? $language : current(Config::get('app/languages'));
  }

  public function sendEmail($data): bool {
    // Debug::log($data);die('gata');

    try {
      $this->setFrom($data['from'] ?? $this->mail->Username)
           ->setTo($data['to'])
           ->setSubject($data['subject'])
           ->setBodyMessage($data['message'] ?? '');

      if (isset($data['reply_to'])) {
        $this->setReplyTo($data['reply_to']);
      }

      // Debug::log($this->mail);die('gata');
      // Debug::dump($this->mail);die('gata');

      $this->addAttachments($data['attachments'] ?? []);

      if (!$this->valid) {
        return false;
      }

      // Debug::dump(
      //  $this->emailType
      // ,$this->unsubscribeHeader
      // ,$this->mail->Body
      // ,$this->mail->Body
      // ,$this->mail->getAllRecipientAddresses()
      // );

      $this->setEmbededImages();

      $this->setDKIM();

      return $this->mail->send();

    } catch (Exception $e) {
      Config::logger()->error($e->getMessage());
      Config::logger()->error($this->mail->ErrorInfo);
      Messages::mail('Failed sending the email: ' . $this->mail->ErrorInfo);
      return false;
    }
  }

  public function parseAddresses(array|string $addressList): array {
    if (is_array($addressList)) {
      $addressList = implode(',', $addressList);
    }

    $addresses = $this->mail->parseAddresses($addressList, true, PHPMailer::CHARSET_UTF8);

    return $addresses;
  }

  public function setFrom(string|array $addressList): self {
    $addresses = $this->parseAddresses($addressList);
    // Debug::log($addressList);die;
    // Debug::log($addresses);die('gata');
    if (empty($addresses)) {
      Config::logger()->error('Invalid From Address(es)');
      $this->valid = false;
      return $this;
    }

    foreach ($addresses as $address) {
      $this->mail->setFrom($address['address'], $address['name']);
    }

    return $this;
  }

  public function setTo(string|array $addressList): self {
    $addresses = $this->parseAddresses($addressList);
    if (empty($addresses)) {
      Config::logger()->error('Invalid Recipient Address(es) : ' . implode('; ', $addressList));
      $this->valid = false;
      return $this;
    }

    $validAddreses = false;

    foreach ($addresses as $address) {
      $validAddreses = true;
      $this->mail->addAddress($address['address'], $address['name']);
    }

    if (!$validAddreses) {
      Config::logger()->error('Invalid address');
      $this->valid = false;
      return $this;
    }

    return $this;
  }

  public function setReplyTo(string|array $addressList): self {
    $addresses = $this->parseAddresses($addressList);
    if (empty($addresses)) {
      Config::logger()->error('Invalid ReplyTo Address(es)');
      $this->valid = false;
      return $this;
    }

    foreach ($addresses as $address) {
      $this->mail->addReplyTo($address['address'], $address['name']);
    }

    return $this;
  }

  public function setSubject(string $subject): self {
    $this->mail->Subject = $subject;
    return $this;
  }

  public function setBodyMessage(string $message): self {
    if (empty($message)) {
      return $this;
    }

    $plainBody = strip_tags($message);

    if ($this->config['host'] == $this->config['dkim_host']) {
      $this->mail->AltBody = $plainBody;
    }

    if ($this->isHTML) {
      if ($plainBody == $message) {
        $message = preg_replace('/(\r\n|\n\r|\n|\r)+/', '$1<br>', $message);
      }

      $this->mail->Body = $message;
    }

    return $this;
  }

  public function addAttachments($attachments): bool {
    foreach ($attachments as $attachmentFilePath) {
      if (!$this->mail->addAttachment($attachmentFilePath)) {
        return false;
      }
    }

    return true;
  }

  private function setEmbededImages(): self {
    $cids = Config::get('email_embeded_cids') ?? [];

    if (empty($cids) || !is_array($cids) || empty($cidKeys = array_keys($cids))) {
      return $this;
    }

    foreach ($cidKeys as $key) {
      if (strpos($this->mail->Body, $key) === false) {
        continue;
      }

      if (!apcu_fetch($key)) {
        apcu_store($key, file_get_contents($cids[$key]), 85000);
      }

      $this->mail->addStringEmbeddedImage(apcu_fetch($key), str_replace('cid:', '', $key), basename($cids[$key]));
    }

    return $this;
  }

  private function setDKIM(): self {
    if ($this->config['host'] != $this->config['dkim_host']) {
      return $this;
    }

    $this->mail->DKIM_domain = $this->config['dkim']['domain'];
    $this->mail->DKIM_private = $this->config['dkim']['private_key'];
    $this->mail->DKIM_selector = $this->config['dkim']['selector'];
    $this->mail->DKIM_passphrase = $this->config['dkim']['passphrase'];
    $this->mail->DKIM_identity = $this->config['username'];

    return $this;
  }

  public static function loadLayout($message, $language = 'ro') {
    return str_replace(
      '_TEMPLATE_BODY_PLACEHOLDER_',
      $message,
      Template::renderView(DS . 'templates' . DS . 'front' . DS . $language . DS . 'email_layout.php', [])
    );
  }

  public function connect(): self {
    //Send using SMTP
    $this->mail->isSMTP();
    //Set the SMTP server to send through
    $this->mail->Host       = $this->config['host'];
    //Enable SMTP authentication
    $this->mail->SMTPAuth   = true;
    //SMTP username
    $this->mail->Username   = $this->config['username'];
    //SMTP password
    $this->mail->Password   = $this->config['password'];
    //Enable implicit TLS encryption
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $this->mail->Port       = $this->config['port'];

    return $this;
  }

  public function setHtml(): self {
    $this->isHTML = true;
    $this->mail->isHTML($this->isHTML);
    return $this;
  }
}
