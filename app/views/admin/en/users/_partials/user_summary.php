<?php
use KrisRo\PhpRepublic\Session;
use KrisRo\PhpRepublic\Dates;
use KrisRo\PhpConfig\Config;
?>
      <div class="col-lg-3 col-sm-12">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <!--<img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">-->
            </div>

            <h3 class="profile-username text-center">
              <?php echo Session::user()['username'] ?>
            </h3>

            <p class="text-muted text-center">Role: <?php echo Session::user()['role'] ?></p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item d-flex justify-content-between">
                <b>Created</b> <span class="float-right"><?php echo Dates::format(Session::user()['created']) ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <b>Updated</b> <span class="float-right"><?php echo Dates::format(Session::user()['updated']) ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <b>Active</b> <span class="float-right"><?php echo Session::user()['is_active'] ? 'Yes' : 'No' ?></span>
              </li>
            </ul>

            <?php if (Config::current_page() != 'account/user/delete') { ?>
              <a href="/admin/account/user/delete" class="btn btn-danger btn-block"><i class="bi bi-trash3-fill"></i> <b>Delete Account</b></a>
            <?php } ?>
          </div>
          <!-- /.card-body -->
        </div>
      </div>