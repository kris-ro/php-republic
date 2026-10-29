<?php 
  use KrisRo\PhpConfig\Config;
?>
<div style="margin-top: 2em;"><!-- body -->
  <h1 style="
      font-family: Roboto, Arial, Helvetica, sans-serif;
      font-size: 28px;
      font-weight: normal;
      color: #001a1f;
      line-height: 1.3;
      text-align: center;
      letter-spacing: -0.3px;
      margin-block: 0.67em 1.3em;
      ">
    Hi partner,
  </h1>

  <div><!-- validation block -->
    <p style="
         font-family: Roboto, Arial, Helvetica, sans-serif;
         font-size: 17px;
         color: #5C4B37;
         line-height: 1.7;
         text-align: center;
         ">
      To start the password recovery process please click on the button bellow.
    </p>

    <div style="text-align: center; padding: 2em;"><!-- text-style-wrapper -->
      <!-- email validation button -->
      <a href="<?php echo self::view('webroot') ?>/forgot-password-reset/<?php echo self::view('token') ?>"
           style="
           color: #2C1810;
           display: inline-block;
           font-family: Roboto, Arial, Helvetica, sans-serif;
           font-size: 16px;
           font-weight: bold;
           letter-spacing: 1px;
           line-height: 18px;
           text-align: center;
           text-decoration: none;
           text-transform: uppercase;
           padding: 16px 24px;
           white-space: nowrap;
           background-color: #F7C32E;
           border: 2px solid #FADB82;
           border-radius: 8px;
           ">
        Go to password recovery
      </a><!-- / email validation button -->
    </div><!-- / text-style-wrapper -->
    <p style="
         font-family: Roboto, Arial, Helvetica, sans-serif;
         font-size: 15px;
         color: #8B7355;
         line-height: 1.6;
         text-align: center;
         ">
      If the button doesn't work, copy and paste this link into your browser:
    </p>
    <p style="
         padding: 15px 20px;
         font-family: 'Courier New', monospace;
         font-size: 13px;
         color: #8B7355;
         word-break: break-all;
         background-color: #F9F6F1;
         border-left: 4px solid #F7C32E;
         ">
      <?php echo self::view('webroot') ?>/forgot-password-reset/<?php echo self::view('token') ?>
    </p>
    <div style="display: flex; justify-content: space-between; font-family: Roboto, Arial, Helvetica, sans-serif; font-size: 24px;">
      <div style="box-sizing: border-box; margin-top: 1.5em; padding: 0.7em; text-align: center; width: 48%; border: 1px solid #e8e3dd; border-radius: 10px; background-color: #f9f6f1;">
          ⏱️
        <span style="display: block; color: #8b7355; text-transform: uppercase; font-size: 12px; margin-top: 0.8rem; font-weight: bold;">Expires in</span>
        <span style="font-size: 16px">10 minutes</span>
      </div>
      <div style="box-sizing: border-box; margin-top: 1.5em; padding: 0.7em; text-align: center; width: 48%; border: 1px solid #e8e3dd; border-radius: 10px; background-color: #f9f6f1;">
          🔐<!-- DO NOT EDIT THIS LINE: INVISIBLE CHARACTER -->
        <span style="display: block; color: #8b7355; text-transform: uppercase; font-size: 12px; margin-top: 0.8rem; font-weight: bold;">Security</span>
        <span style="font-size: 16px">one-time link</span>
      </div>
    </div><!-- / validation block -->
  </div>
</div>