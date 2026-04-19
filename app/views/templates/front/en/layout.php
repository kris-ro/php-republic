<?php
  use KrisRo\PhpRepublic\Template;
  use KrisRo\PhpRepublic\Session;
  use KrisRo\PhpRepublic\Arrays;
  use KrisRo\PhpConfig\Config;
?>
<!DOCTYPE html>
<html lang="<?php echo Session::language() ?>">
  <head>
    <meta charset="utf-8">
    <title>PhpRepublic - Ultra slim PHP framework</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Super slim MVC framework written in PHP with layered JSON config, PHP templating, translation, data validation, account authentication and others">
    <meta name="keywords" content="PHP, framework">
    <meta name="author" content="https://github.com/kris-ro">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/sidebars.css">

    <?php if (Arrays::getValueByPath(Config::get('css'), Config::get('current_page'))) { ?>
      <link rel="stylesheet" href="/css/<?php echo Config::get('css')[Config::get('current_page')] ?>">
    <?php } ?>
  </head>

  <body>
    <main>
      <?php echo Template::topMenu('', false); ?>

      <?php if (Template::popup_error('', false) || Template::popup_warning('', false) || Template::popup_info('', false) || Template::popup_success('', false)) { ?>
        <section class="container xpt-lg-2 xpt-xl-3 xpb-3 xpb-xl-5 xmt-n2 xmt-sm-0 xmb-2 xmb-md-4 xmb-lg-5">
          <?php echo Template::popup_error('', false); ?>
          <?php echo Template::popup_warning('', false); ?>
          <?php echo Template::popup_info('', false); ?>
          <?php echo Template::popup_success('', false); ?>
        </section>
      <?php } ?>

      <section class="d-flex flex-nowrap">
        <?php echo Template::sideMenu('', false); ?>
        <?php echo Template::page('', false); ?>
      </section>

    </main>

    <?php echo Template::footer('', false); ?>

    <?php echo Template::js('', false); ?>

  </body>
</html>