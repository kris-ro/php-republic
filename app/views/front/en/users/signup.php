<?php
  use KrisRo\PhpRepublic\Request;
?>
<div class="container-fluid min-vh-100">
  <div class="row h-100">
    <div class="d-none d-md-block col-md-4 col-lg-4 h-100"><?php echo self::view('left_side', false) ?></div>
    <div class="col-md-8 col-lg-8 signup-container vh-100">
      <div class="form-signup w-100 m-auto text-center" style="max-width: 520px;">
        <form action="signup" method="POST">
          <?php echo self::getFormToken('signup') // self is instance of KrisRo\PhpRepublic\Template ?>
          <img class="mb-4" src="/assets/bootstrap-logo.svg" alt="" width="72" height="57">
          <h1 class="h3 mb-3 fw-normal">Nice to meet you !</h1>

          <div class="col-12 text-start mb-3">
            <label for="username" class="form-label">Your name</label>
            <div class="input-group has-validation">
              <span class="input-group-text">👤</span>
              <input type="text" value="<?php echo Request::post('username') ?>" class="form-control <?php echo (self::view('errors/username') ?? '') ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Your name">
              <div class="invalid-feedback">
                <?php echo self::view('errors/username') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="col-12 text-start mb-3">
            <label for="email" class="form-label">Your email</label>
            <div class="input-group has-validation">
              <span class="input-group-text">✉</span>
              <input type="text" value="<?php echo Request::post('email') ?>" class="form-control <?php echo (self::view('errors/email') ?? '') ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Your email">
              <div class="invalid-feedback">
                <?php echo self::view('errors/email') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="col-12 text-start mb-5">
            <label for="password" class="form-label">Password</label>
            <div class="input-group has-validation">
              <span class="input-group-text">🗝</span>
              <input type="password" value="<?php echo Request::post('password') ?>" class="form-control <?php echo (self::view('errors/password') ?? '') ? 'is-invalid' : '' ?>" name="password" id="password" placeholder="Your password">
              <input type="password" value="<?php echo Request::post('repeat') ?>" class="form-control <?php echo (self::view('errors/password') ?? '') ? 'is-invalid' : '' ?>" name="repeat" id="repeat" placeholder="Repeat password">
              <div class="invalid-feedback form-text">
                <?php echo self::view('errors/password') ?? '' ?>
              </div>
            </div>
          </div>

          <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
        </form>
      </div>
    </div>
  </div>
</div>