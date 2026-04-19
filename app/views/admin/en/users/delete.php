<?php
use KrisRo\PhpRepublic\Session;
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
          <div class="card-header"><div class="card-title">Deleting account !!!</div></div>
          <!--end::Header-->

          <div class="alert alert-warning m-3" role="alert">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>
            You're deleting your account. You won't be able to undo this.
          </div>

          <!--begin::Form-->
          <form action="/admin/account/user/delete" method="POST">
            <?php echo self::getFormToken('user-delete') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <div class="mb-3">
                <label for="password" class="form-label">Your Password</label>
                <input type="password" name="password" class="form-control" id="password" aria-describedby="passwordHelp">
                <?php if (!(self::view('errors/password') ?? null)) { ?>
                  <div id="passwordHelp" class="form-text">
                    Just to be sure.
                  </div>
                <?php } else { ?>
                  <div class="invalid-feedback">
                    <?php echo self::view('errors/password') ?? '' ?>
                  </div>
                <?php } ?>
              </div>
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button type="submit" class="btn btn-danger">Delete Account</button>
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