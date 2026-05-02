<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Crud Test &raquo; Delete</h3></div>
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
          <div class="card-header"><div class="card-title">Delete Crud Test</div></div>
          <!--end::Header-->
          <div class="alert alert-warning m-3" role="alert">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>
            You're deleting Crud Test item. You won't be able to undo this.
          </div>
          <!--begin::Form-->
          <form action="/admin/crudtests/delete/<?php echo self::view('item/crud_test_id') ?>" method="POST">
            <?php echo self::getFormToken('deletecrud_test') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <input type="hidden" name="crud_test_id" id="crud_test_id-id" value="<?php echo self::view('item/crud_test_id') ?>">
              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Crud Test Id
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/crud_test_id') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Email
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/email') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Price
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/price') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Timestamp Time
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/timestamp_time') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Date Time Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/date_time_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Date Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/date_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Enum Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/enum_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Boolean Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/boolean_field') ? 'Yes' : 'No' ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Long Blob Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  Binary content
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Long Text Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/long_text_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Small Int Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/small_int_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Uuid Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/uuid_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Default Null Value
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/default_null_value') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Time Field
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/time_field') ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                  Default Empty String
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                  <?php echo self::view('item/default_empty_string') ?>
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

