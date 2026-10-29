<?php
  use KrisRo\PhpRepublic\Request;
?>
<div class="container-fluid min-vh-100">
  <div class="row h-100">
    <div class="d-none d-md-block col-md-4 col-lg-6 h-100"><?php echo self::view('left_side', false) ?></div>
    <div class="col-md-8 col-lg-6 signup-container vh-100">
      <div class="form-signup w-100 m-auto text-center">
        <form action="/users/confirm/email" method="POST">
          <?php echo self::getFormToken('confirmemail') // self is instance of Template class ?>
          <img class="mb-4" src="/assets/awork-logo.svg" alt="" width="35">
          <h1 class="h3 mb-3 fw-normal">Resend validation code</h1>

          <div class="form-floating mb-3 has-validation">
            <input type="email" value="<?php echo Request::post('email') ?>" class="form-control <?php echo (self::view('errors/email') ?? '') ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="name@example.com">
            <label for="email">Email address</label>
            <div class="invalid-feedback">
              <?php echo self::view('errors/email') ?? '' ?>
            </div>
          </div>
          
          <button class="w-100 btn btn-lg btn-primary" type="submit">Resend confirmation code</button>
        </form>
      </div>
    </div>
  </div>
</div>