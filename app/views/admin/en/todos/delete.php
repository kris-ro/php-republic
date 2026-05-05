<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Todo &raquo; Delete</h3></div>
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
      <div class="col-sm-12">
        <div class="card card-primary card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header"><div class="card-title">Delete Todo</div></div>
          <!--end::Header-->
          <div class="alert alert-warning m-3" role="alert">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>
            You're deleting Todo item. You won't be able to undo this.
          </div>
          <!--begin::Form-->
          <form action="/admin/todos/delete/<?php echo self::view('item/todo_id') ?>" method="POST">
            <?php echo self::getFormToken('deletetodo') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <input type="hidden" name="todo_id" id="todo_id-id" value="<?php echo self::view('item/todo_id') ?>">
              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Todo Id
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/todo_id') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Title
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/title') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Details
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/details') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Status
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/status') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Users Id
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/users_id') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Created
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/created') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Updated
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/updated') ?>
                </div>
              </div>

            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button type="submit" class="btn btn-danger">Delete</button>
            </div>
            <!--end::Footer-->
          </form>
          <!--end::Form-->
        </div>
        <!--end::Card-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->

