<?php
use KrisRo\PhpRepublic\Dates;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Account &raquo; User Tokens</h3></div>
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

      <div class="col-lg-3 col-sm-12">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <!--<img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">-->
            </div>

            <h3 class="profile-username text-center">
              <?php echo self::view('token/label') ?>
            </h3>

            <p class="text-muted text-center">Token ID: <?php echo self::view('token/id') ?></p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item d-flex justify-content-between">
                <b class="me-2">Fingerprint</b> <span class="float-right overflow-hidden"><?php echo self::view('token/fingerprint') ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <b>Created</b> <span class="float-right"><?php echo Dates::format(self::view('token/created')) ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <b>Expires</b> <span class="float-right"><?php echo Dates::format(self::view('token/expires')) ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <b>Revoked</b> <span class="float-right"><?php echo self::view('token/revoked') ? 'Yes' : 'No' ?></span>
              </li>
            </ul>
          </div>
          <!-- /.card-body -->
        </div>
      </div>

      <div class="col-lg-9 col-sm-12">
        <div class="card card-primary card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header"><div class="card-title">Deleting token !!!</div></div>
          <!--end::Header-->

          <div class="alert alert-warning m-3" role="alert">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>
            You're deleting a token and won't be able to undo this. Any services using this token will not be able to connect to your app anymore.
          </div>

          <!--begin::Form-->
          <form action="/admin/account/user/token/delete/<?php echo self::view('token/id') ?>" method="POST">
            <?php echo self::getFormToken('user-token-delete') // self is instance of KrisRo\PhpRepublic\Template ?>
            <input type="hidden" name="id" value="<?php echo self::view('token/id') ?>">
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
              <button type="submit" class="btn btn-danger">Delete Token</button>
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