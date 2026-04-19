<?php
use KrisRo\PhpRepublic\Session;
?>
<header class="p-3 text-bg-primary">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
      </a>
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="/" class="nav-link px-2 text-white">Home</a></li>
        <li><a href="/layout" class="nav-link px-2 text-white">Layout</a></li>
        <li><a href="/security" class="nav-link px-2 text-white">Security</a></li>
        <li><a href="/rest-api" class="nav-link px-2 text-white">API</a></li>
        <li><a href="/config" class="nav-link px-2 text-white">Config</a></li>
        <li><a href="/localization" class="nav-link px-2 text-white">Localization</a></li>
      </ul>
      <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
      </form>
      <div class="text-end">
        <?php if (Session::user()) { ?>
          <a href="/logout" class="btn btn-outline-light me-2">Sign-out</a>
        <?php } else { ?>
          <a href="/signin" class="btn btn-outline-light me-2">Sign-in</a>
          <a href="/signup" class="btn btn-warning">Sign-up</a>
        <?php } ?>
      </div>
    </div>
  </div>
</header>