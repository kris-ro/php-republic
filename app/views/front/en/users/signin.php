<?php
  use KrisRo\PhpRepublic\Request;
?>
<div class="container-fluid min-vh-100">
  <div class="row h-100">
    <div class="d-none d-md-block col-md-4 col-lg-6 h-100"><?php echo self::view('left_side', false) ?></div>
    <div class="col-md-8 col-lg-6 signup-container vh-100">
      <div class="form-signup w-100 m-auto text-center">
        <form action="signin" method="POST">
          <?php echo self::getFormToken('signin') // self is instance of Template class ?>
          <img class="mb-4" src="/assets/bootstrap-logo.svg" alt="" width="72" height="57">
          <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

          <div class="form-floating mb-1 has-validation">
            <input type="email" value="<?php echo Request::post('email') ?>" class="form-control <?php echo (self::view('errors/email') ?? '') ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="name@example.com">
            <label for="email">Email address</label>
            <div class="invalid-feedback">
              <?php echo self::view('errors/email') ?? '' ?>
            </div>
          </div>
          <div class="form-floating has-validation">
            <input type="password" class="form-control <?php echo (self::view('errors/password') ?? '') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
            <div class="invalid-feedback">
              <?php echo self::view('errors/password') ?? '' ?>
            </div>
          </div>

          <div class="checkbox mb-3">
            <label>
              <input type="checkbox" value="remember-me"> Remember me
            </label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</div>