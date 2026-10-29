<?php
  use KrisRo\PhpRepublic\Request;
  use App\Models\User as UserModel;
?>
<div class="container-fluid min-vh-100">
  <div class="row h-100">
    <div class="d-none d-md-block col-md-4 col-lg-4 h-100"><?php echo self::view('left_side', false) ?></div>
    <div class="col-md-8 col-lg-8 signup-container vh-100">
      <div class="form-signup w-100 m-auto text-center" style="max-width: 520px;">
        <form action="signup" method="POST">
          <?php echo self::getFormToken('signup') // self is instance of KrisRo\PhpRepublic\Template ?>
          <img class="mb-4" src="/assets/phprepublic.svg" alt="" width="120">
          <h1 class="h3 mb-3 fw-normal">Nice to meet you !</h1>

          <div class="col-12 text-start mb-3">
            <label for="username" class="form-label">Your name</label>
            <div class="input-group has-validation">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input type="text" value="<?php echo Request::post('username') ?>" class="form-control <?php echo (self::view('errors/username') ?? '') ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Your name">
              <div class="invalid-feedback">
                <?php echo self::view('errors/username') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="col-12 text-start mb-3">
            <label for="username" class="form-label">Your role</label>
            <div class="input-group mb-3">
              <label class="input-group-text" for="role" style="padding-left: 0.7rem !important; padding-right: 0.7rem !important;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="width: 1.2rem;"><path d="M405.5 349.6C439.2 349.6 487.8 342.7 487.8 302.6C488 295.9 488.7 300.8 466.9 206.4C462.3 187.3 458.2 178.6 424.6 161.8C398.5 148.5 341.7 126.4 324.9 126.4C309.2 126.4 304.7 146.6 286 146.6C268 146.6 254.7 131.5 237.9 131.5C221.8 131.5 211.2 142.5 203.1 165.1C175.6 242.7 176.8 239.4 177 243.4C177 268.2 274.6 349.5 405.5 349.5zM493 318.8C497.7 340.8 497.7 343.1 497.7 346C497.7 383.7 455.4 404.6 399.7 404.6C274 404.7 163.8 331 163.8 282.3C163.8 275.5 165.2 268.8 167.9 262.6C122.7 264.9 64.1 272.9 64.1 324.6C64.1 409.3 264.7 513.6 423.6 513.6C545.4 513.6 576.1 458.5 576.1 415C576.1 380.8 546.5 342 493.2 318.8z"/></svg></label>
              <select class="form-select" name="role" id="role">
                <option value="">Select your role</option>
                <?php foreach (UserModel::getRoleLabels([UserModel::ROLE_GUEST, UserModel::ROLE_APP]) as $id => $label) { ?>
                  <option value="<?php echo $id ?>" <?php echo $id == Request::post('role') ? 'selected' : '' ?>><?php echo $label ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-12 text-start mb-3">
            <label for="email" class="form-label">Your email</label>
            <div class="input-group has-validation">
              <span class="input-group-text"><i class="bi bi-envelope-at-fill"></i></span>
              <input type="text" value="<?php echo Request::post('email') ?>" class="form-control <?php echo (self::view('errors/email') ?? '') ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Your email">
              <div class="invalid-feedback">
                <?php echo self::view('errors/email') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="col-12 text-start mb-5">
            <label for="password" class="form-label">Password</label>
            <div class="input-group has-validation">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
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
