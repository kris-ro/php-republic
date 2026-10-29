<?php
  use KrisRo\PhpRepublic\Request;
?>
<div class="container-fluid min-vh-100">
  <div class="row h-100">
    <div class="d-none d-md-block col-md-4 col-lg-6 h-100"><?php echo self::view('left_side', false) ?></div>
    <div class="col-md-8 col-lg-6 signup-container vh-100">
      <div class="form-signup w-100 m-auto text-center">
        <form action="/forgot-password-reset/<?php echo self::view('token') ?>" method="POST">
          <?php echo self::getFormToken('forgotpasswordreset') ?>
          <img class="mb-4" src="/assets/phprepublic.svg" alt="" width="120">
          <h1 class="h3 mb-5 fw-normal">Reset your password</h1>

          <input type="hidden" name="token" value="<?php echo self::view('token') ?>">

          <div class="col-12 text-start mb-3">
            <label for="password" class="form-label">Your password</label>
            <div class="input-group has-validation">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
              <input type="password" value="<?php echo Request::post('password') ?>" class="form-control <?php echo (self::view('errors/password') ?? '') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Your password">
              <div class="invalid-feedback">
                <?php echo self::view('errors/password') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="col-12 text-start mb-5">
            <label for="repeat" class="form-label">Confirm your password</label>
            <div class="input-group has-validation">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
              <input type="password" value="<?php echo Request::post('repeat') ?>" class="form-control <?php echo (self::view('errors/repeat') ?? '') ? 'is-invalid' : '' ?>" id="repeat" name="repeat" placeholder="Your password again">
              <div class="invalid-feedback">
                <?php echo self::view('errors/repeat') ?? '' ?>
              </div>
            </div>
          </div>

          <button class="w-100 btn btn-lg btn-primary" type="submit">Save password</button>
        </form>
      </div>
    </div>
  </div>
</div>