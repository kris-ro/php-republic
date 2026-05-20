<?php
  use KrisRo\PhpRepublic\Template;
  use KrisRo\PhpRepublic\Session;
  use KrisRo\PhpRepublic\Arrays;
  use KrisRo\PhpConfig\Config;
?>
<!DOCTYPE html>
<html lang="<?php echo Session::language() ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <title>PhpRepublic - Admin - Dashboard</title>
    <meta name="description" content="Admin section">
    <meta name="keywords" content="PHP, framework">
    <meta name="author" content="https://github.com/kris-ro">
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/admin/css/adminlte.min.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <link rel="stylesheet" href="/admin/css/admin.css">

    <?php if (Config::get('css/' . Config::get('current_page'))) { ?>
      <link rel="stylesheet" href="/admin/css/<?php echo Config::get('css/' . Config::get('current_page')) ?>">
    <?php } ?>

    <link rel="stylesheet" href="/admin/css/lists.css">
    <link rel="stylesheet" href="/admin/css/selector_popup.css">
  </head>

  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <?php echo Template::topMenu('', false); ?>
      <?php echo Template::sideMenu('', false); ?>
      <!--begin::App Main-->
      <main class="app-main">
        <?php if (Template::popup_error('', false) || Template::popup_warning('', false) || Template::popup_info('', false) || Template::popup_success('', false)) { ?>
          <section class="p-3">
            <?php echo Template::popup_error('', false); ?>
            <?php echo Template::popup_warning('', false); ?>
            <?php echo Template::popup_info('', false); ?>
            <?php echo Template::popup_success('', false); ?>
          </section>
        <?php } ?>

        <?php echo Template::page('', false); ?>
      </main>
      <!--end::App Main-->

      <?php echo Template::footer('', false); ?>
    </div>

    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="/admin/js/adminlte.min.js"></script>
    <!--end::Required Plugin(AdminLTE)-->

    <script src="/admin/js/lists.js"></script>

    <?php echo Template::js('', false); ?>

    <?php if (Config::get('js/' . Config::get('current_page'))) { ?>
      <script src="/admin/js/<?php echo Config::get('js/' . Config::get('current_page')) ?>"></script>
    <?php } ?>

    <script src="/admin/js/selector_popup.js"></script>

    <div id="selector-popup-loader">
      <div id="selector-popup-header">
        <a id="selector-popup-closer" class="me-2"><i class="bi bi-x-square-fill"></i> Close</a>
      </div>
      <iframe src="about:blank" id="selector-popup"></iframe>
    </div>
    <!-- end #selector-popup-loader -->

  </body>
</html>