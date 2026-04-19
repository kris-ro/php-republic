<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Account &raquo; User Profile</h3></div>
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <!--begin::Col-->

      <?php echo self::view('left_user_summary', false) ?>

      <div class="col-lg-9 col-sm-12">
        <div class="card card-primary card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header"><div class="card-title">Update user info</div></div>
          <!--end::Header-->
          <!--begin::Form-->
          <form action="/admin/account/user" method="POST">
            <?php echo self::getFormToken('profile') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" value="<?php echo Request::post('email') ?: self::view('user/email') ?>" class="form-control" id="email" aria-describedby="emailHelp">
                    <div class="invalid-feedback">
                      <?php echo self::view('errors/email') ?? '' ?>
                    </div>
                    <div id="emailHelp" class="form-text">
                      We'll never share your email with anyone else.
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="username" class="form-label">User Name</label>
                    <input type="username" name="username" value="<?php echo Request::post('username') ?: self::view('user/username') ?>" class="form-control" id="username" aria-describedby="usernameHelp">
                    <div class="invalid-feedback">
                      <?php echo self::view('errors/username') ?? '' ?>
                    </div>
                    <div id="usernameHelp" class="form-text">
                      We'll never share your name with anyone else.
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="new-password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" id="new-password">
                <div class="invalid-feedback">
                  <?php echo self::view('errors/password') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" name="repeat" class="form-control" id="confirm-password">
                <div class="invalid-feedback">
                  <?php echo self::view('errors/repeat') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" id="password">
                <div class="invalid-feedback">
                  <?php echo self::view('errors/current_password') ?? '' ?>
                </div>
              </div>
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!--end::Footer-->
          </form>
          <!--end::Form-->
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::App Content-->