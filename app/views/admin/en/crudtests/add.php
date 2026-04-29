<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Crud Test &raquo; Add</h3></div>
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
          <div class="card-header"><div class="card-title">Add Crud Test</div></div>
          <!--end::Header-->
          <!--begin::Form-->
          <form action="/admin/crudtests/add" method="POST">
            <?php echo self::getFormToken('addcrud_test') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <div class="mb-3">
                <label for="email-id" class="form-label">Email</label>
                <input type="text" class="form-control <?php echo self::view('errors/email') ? 'border-danger text-danger' : '' ?>" name="email" id="email-id" value="<?php echo Request::isPost() ? Request::post('email') : (self::view('item/email') ?? '') ?>">
                <?php if (self::view('errors/email')) { ?>
                  <div id="email-id" class="form-text text-danger">
                    <?php echo self::view('errors/email') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="price-id" class="form-label">Price</label>
                <input type="text" class="form-control <?php echo self::view('errors/price') ? 'border-danger text-danger' : '' ?>" name="price" id="price-id" value="<?php echo Request::isPost() ? Request::post('price') : (self::view('item/price') ?? '') ?>">
                <?php if (self::view('errors/price')) { ?>
                  <div id="price-id" class="form-text text-danger">
                    <?php echo self::view('errors/price') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="timestamp_time-id" class="form-label">Timestamp Time</label>
                <input type="datetime-local" class="form-control <?php echo self::view('errors/timestamp_time') ? 'border-danger text-danger' : '' ?>" name="timestamp_time" id="timestamp_time-id" value="<?php echo Request::isPost() ? Request::post('timestamp_time') : (self::view('item/timestamp_time') ?? '') ?>">
                <?php if (self::view('errors/timestamp_time')) { ?>
                  <div id="timestamp_time-id" class="form-text text-danger">
                    <?php echo self::view('errors/timestamp_time') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="date_time_field-id" class="form-label">Date Time Field</label>
                <input type="datetime-local" class="form-control <?php echo self::view('errors/date_time_field') ? 'border-danger text-danger' : '' ?>" name="date_time_field" id="date_time_field-id" value="<?php echo Request::isPost() ? Request::post('date_time_field') : (self::view('item/date_time_field') ?? '') ?>">
                <?php if (self::view('errors/date_time_field')) { ?>
                  <div id="date_time_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/date_time_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="date_field-id" class="form-label">Date Field</label>
                <input type="date" class="form-control <?php echo self::view('errors/date_field') ? 'border-danger text-danger' : '' ?>" name="date_field" id="date_field-id" value="<?php echo Request::isPost() ? Request::post('date_field') : (self::view('item/date_field') ?? '') ?>">
                <?php if (self::view('errors/date_field')) { ?>
                  <div id="date_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/date_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="enum_field-id" class="form-label">Enum Field</label>
                <select class="form-select <?php echo self::view('errors/enum_field') ? 'border-danger text-danger' : '' ?>" name="enum_field" id="enum_field-id" aria-label="Enum Field">
                  <option value=""> -- </option>
                    <option value="aaa" <?php echo "aaa" == (Request::isPost() ? Request::post('enum_field') : (self::view('item/enum_field') ?? '')) ? 'selected' : '' ?>>Aaa</option>
                    <option value="bbb" <?php echo "bbb" == (Request::isPost() ? Request::post('enum_field') : (self::view('item/enum_field') ?? '')) ? 'selected' : '' ?>>Bbb</option>
                    <option value="ccc" <?php echo "ccc" == (Request::isPost() ? Request::post('enum_field') : (self::view('item/enum_field') ?? '')) ? 'selected' : '' ?>>Ccc</option>
                </select>
                <?php if (self::view('errors/enum_field')) { ?>
                  <div id="enum_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/enum_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="boolean_field-id" class="form-label">Boolean Field</label>
                <select class="form-select <?php echo self::view('errors/boolean_field') ? 'border-danger text-danger' : '' ?>" name="boolean_field" id="boolean_field-id" aria-label="Boolean Field">
                  <option value=""> -- </option>
                    <option value="1" <?php echo "1" == (Request::isPost() ? Request::post('boolean_field') : (self::view('item/boolean_field') ?? '')) ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?php echo "0" == (Request::isPost() ? Request::post('boolean_field') : (self::view('item/boolean_field') ?? '')) ? 'selected' : '' ?>>No</option>
                </select>
                <?php if (self::view('errors/boolean_field')) { ?>
                  <div id="boolean_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/boolean_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="long_blob_field-id" class="form-label">Long Blob Field</label>
                <input type="file" class="form-control <?php echo self::view('errors/long_blob_field') ? 'border-danger text-danger' : '' ?>" name="long_blob_field" id="long_blob_field-id">
                <?php if (self::view('errors/long_blob_field')) { ?>
                  <div id="long_blob_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/long_blob_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="long_text_field-id" class="form-label">Long Text Field</label>
                <textarea class="form-control <?php echo self::view('errors/long_text_field') ? 'border-danger text-danger' : '' ?>" name="long_text_field" id="long_text_field-id" rows="3"><?php echo Request::isPost() ? Request::post('long_text_field') : (self::view('item/long_text_field') ?? '') ?>
                </textarea>
                <?php if (self::view('errors/long_text_field')) { ?>
                  <div id="long_text_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/long_text_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="small_int_field-id" class="form-label">Small Int Field</label>
                <input type="text" class="form-control <?php echo self::view('errors/small_int_field') ? 'border-danger text-danger' : '' ?>" name="small_int_field" id="small_int_field-id" value="<?php echo Request::isPost() ? Request::post('small_int_field') : (self::view('item/small_int_field') ?? '') ?>">
                <?php if (self::view('errors/small_int_field')) { ?>
                  <div id="small_int_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/small_int_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="uuid_field-id" class="form-label">Uuid Field</label>
                <input type="file" class="form-control <?php echo self::view('errors/uuid_field') ? 'border-danger text-danger' : '' ?>" name="uuid_field" id="uuid_field-id">
                <?php if (self::view('errors/uuid_field')) { ?>
                  <div id="uuid_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/uuid_field') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="default_null_value-id" class="form-label">Default Null Value</label>
                <input type="text" class="form-control <?php echo self::view('errors/default_null_value') ? 'border-danger text-danger' : '' ?>" name="default_null_value" id="default_null_value-id" value="<?php echo Request::isPost() ? Request::post('default_null_value') : (self::view('item/default_null_value') ?? '') ?>">
                <?php if (self::view('errors/default_null_value')) { ?>
                  <div id="default_null_value-id" class="form-text text-danger">
                    <?php echo self::view('errors/default_null_value') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="time_field-id" class="form-label">Time Field</label>
                <input type="time" class="form-control <?php echo self::view('errors/time_field') ? 'border-danger text-danger' : '' ?>" name="time_field" id="time_field-id" value="<?php echo Request::isPost() ? Request::post('time_field') : (self::view('item/time_field') ?? '') ?>">
                <?php if (self::view('errors/time_field')) { ?>
                  <div id="time_field-id" class="form-text text-danger">
                    <?php echo self::view('errors/time_field') ?>
                  </div>
                <?php } ?>
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
        <!--end::Card-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->

