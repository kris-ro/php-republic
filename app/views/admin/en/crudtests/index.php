<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Crud Test</h3></div>
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
      <div class="col-sm-12 list-container" id="user-crudtests-list-container" data-list-address="<?php echo self::get('list_address') ?>">
        <div class="card mb-4 list-content" id="user-crudtests-list-content">
          <div class="card-body table-responsive">
            <table id="table-crudtests" class="table table-bordered table-striped table-hover dataTable dtr-inline <?php echo self::view('slim_select') ?>" aria-describedby="crudtests_info">
              <thead>
                <tr>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/crud_test_id') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Crud Test Id"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/crud_test_id') ?>" class="page-list-sorter">Crud Test Id
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/email') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Email"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/email') ?>" class="page-list-sorter">Email
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/price') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Price"
                    >
                      Price
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/timestamp_time') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Timestamp Time"
                    >
                      Timestamp Time
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/date_time_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Time Field"
                    >
                      Date Time Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/date_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Field"
                    >
                      Date Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/enum_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Enum Field"
                    >
                      Enum Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/boolean_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Boolean Field"
                    >
                      Boolean Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/long_blob_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Blob Field"
                    >
                      Long Blob Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/long_text_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Text Field"
                    >
                      Long Text Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/small_int_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Small Int Field"
                    >
                      Small Int Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/uuid_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Uuid Field"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/uuid_field') ?>" class="page-list-sorter">Uuid Field
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/default_null_value') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Default Null Value"
                    >
                      Default Null Value
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/time_field') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Time Field"
                    >
                      Time Field
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/default_empty_string') ?>"
                      tabindex="0"
                      aria-controls="table-crudtests"
                      rowspan="1"
                      colspan="1"
                      aria-label="Default Empty String"
                    >
                      Default Empty String
                  </th>
                  <th class="<?php echo self::view('slim_select') ?>"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-label="Crud Test Id:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="crud_test_id"
                           value="<?php echo Request::param('crud_test_id', ['crud_test_id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Email:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="email"
                           value="<?php echo Request::param('email', ['email']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Price:" class="">
                  </td>
                  <td data-label="Timestamp Time:" class="">
                  </td>
                  <td data-label="Date Time Field:" class="">
                  </td>
                  <td data-label="Date Field:" class="">
                  </td>
                  <td data-label="Enum Field:" class="">
                  </td>
                  <td data-label="Boolean Field:" class="">
                  </td>
                  <td data-label="Long Blob Field:" class="">
                  </td>
                  <td data-label="Long Text Field:" class="">
                  </td>
                  <td data-label="Small Int Field:" class="">
                  </td>
                  <td data-label="Uuid Field:" class="">
                    <input type="text"
                           name="uuid_field"
                           value="<?php echo Request::param('uuid_field', ['uuid_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Default Null Value:" class="">
                  </td>
                  <td data-label="Time Field:" class="">
                  </td>
                  <td data-label="Default Empty String:" class="">
                  </td>
                  <td class="<?php echo self::view('slim_select') ?>"></td>
                </tr>
                <?php foreach (self::view('items') ?: [] as $item) { ?>
                  <tr>
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['crud_test_id'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['email'] ?></td>
                    <td class=""><?php echo $item['price'] ?></td>
                    <td class=""><?php echo $item['timestamp_time'] ?></td>
                    <td class=""><?php echo $item['date_time_field'] ?></td>
                    <td class=""><?php echo $item['date_field'] ?></td>
                    <td class=""><?php echo $item['enum_field'] ?></td>
                    <td class=""><?php echo $item['boolean_field'] ?></td>
                    <td class=""><?php echo $item['long_blob_field'] ?></td>
                    <td class=""><?php echo $item['long_text_field'] ?></td>
                    <td class=""><?php echo $item['small_int_field'] ?></td>
                    <td class=""><?php echo $item['uuid_field'] ?></td>
                    <td class=""><?php echo $item['default_null_value'] ?></td>
                    <td class=""><?php echo $item['time_field'] ?></td>
                    <td class=""><?php echo $item['default_empty_string'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>">
                      <a href="/admin/crudtests/update/<?php echo $item['crud_test_id'] ?>" class="text-decoration-none" title="Delete"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                      <a href="/admin/crudtests/delete/<?php echo $item['crud_test_id'] ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
                      <a data-value="<?php echo $item['crud_test_id'] ?>|<?php echo $item['email'] ?>" class="text-decoration-none slim-select" title="Select"><i class="bi bi-hand-index-fill me-1"></i> Select</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Crud Test Id</th>
                  <th>Email</th>
                  <th>Price</th>
                  <th>Timestamp Time</th>
                  <th>Date Time Field</th>
                  <th>Date Field</th>
                  <th>Enum Field</th>
                  <th>Boolean Field</th>
                  <th>Long Blob Field</th>
                  <th>Long Text Field</th>
                  <th>Small Int Field</th>
                  <th>Uuid Field</th>
                  <th>Default Null Value</th>
                  <th>Time Field</th>
                  <th>Default Empty String</th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->

          <?php echo self::paginationTemplate() ?>

        </div>
        <!-- /.card -->

      </div>
      <!-- /.col.list-container -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!--end::App Content-->

