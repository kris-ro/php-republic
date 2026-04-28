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
          <div class="card-body">
            <table id="user-crudtests" class="table table-bordered table-striped table-hover dataTable dtr-inline" aria-describedby="crudtests_info">
              <thead>
                <tr>
                  <th class="sorting <?php echo self::get('sort_classes/crud_test_id') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
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
                  <th class="sorting <?php echo self::get('sort_classes/email') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
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
                  <th class="sorting <?php echo self::get('sort_classes/price') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Price"
                    >
                      Price
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/timestamp_time') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Timestamp Time"
                    >
                      Timestamp Time
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/date_time_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Time Field"
                    >
                      Date Time Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/date_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Field"
                    >
                      Date Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/enum_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Enum Field"
                    >
                      Enum Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/boolean_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Boolean Field"
                    >
                      Boolean Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/long_blob_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Blob Field"
                    >
                      Long Blob Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/long_text_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Text Field"
                    >
                      Long Text Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/small_int_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Small Int Field"
                    >
                      Small Int Field
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/uuid_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
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
                  <th class="sorting <?php echo self::get('sort_classes/default_null_value') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Default Null Value"
                    >
                      Default Null Value
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-label="Crud Test Id:">
                    <input type="text"
                           name="crud_test_id"
                           value="<?php echo Request::param('crud_test_id', ['crud_test_id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Email:">
                    <input type="text"
                           name="email"
                           value="<?php echo Request::param('email', ['email']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Price:">
                    <input type="text"
                           name="price"
                           value="<?php echo Request::param('price', ['price']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Timestamp Time:">
                    <input type="date"
                           name="timestamp_time"
                           value="<?php echo Request::param('timestamp_time', ['timestamp_time']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Date Time Field:">
                    <input type="date"
                           name="date_time_field"
                           value="<?php echo Request::param('date_time_field', ['date_time_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Date Field:">
                    <input type="date"
                           name="date_field"
                           value="<?php echo Request::param('date_field', ['date_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Enum Field:">
                    <select name="enum_field" class="form-control table-search">
                      <option value=""> -- </option>
                      <option value="aaa" <?php echo Request::param('enum_field', ['enum_field']) === 'aaa' ? 'selected' : '' ?>>Aaa</option>
                      <option value="bbb" <?php echo Request::param('enum_field', ['enum_field']) === 'bbb' ? 'selected' : '' ?>>Bbb</option>
                      <option value="ccc" <?php echo Request::param('enum_field', ['enum_field']) === 'ccc' ? 'selected' : '' ?>>Ccc</option>
                    </select>
                  </td>
                  <td data-label="Boolean Field:">
                    <select name="boolean_field" class="form-control table-search">
                      <option value=""> -- </option>
                      <option value="1" <?php echo Request::param('boolean_field', ['boolean_field']) === '1' ? 'selected' : '' ?>>Yes</option>
                      <option value="0" <?php echo Request::param('boolean_field', ['boolean_field']) === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                  </td>
                  <td data-label="Long Blob Field:">
                    <input type="text"
                           name="long_blob_field"
                           value="<?php echo Request::param('long_blob_field', ['long_blob_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Long Text Field:">
                    <input type="text"
                           name="long_text_field"
                           value="<?php echo Request::param('long_text_field', ['long_text_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Small Int Field:">
                    <input type="text"
                           name="small_int_field"
                           value="<?php echo Request::param('small_int_field', ['small_int_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Uuid Field:">
                    <input type="text"
                           name="uuid_field"
                           value="<?php echo Request::param('uuid_field', ['uuid_field']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Default Null Value:">
                    <input type="text"
                           name="default_null_value"
                           value="<?php echo Request::param('default_null_value', ['default_null_value']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                </tr>
                <?php foreach (self::view('items') ?: [] as $item) { ?>
                  <tr>
                    <td><?php echo $item['crud_test_id'] ?></td>
                    <td><?php echo $item['email'] ?></td>
                    <td><?php echo $item['price'] ?></td>
                    <td><?php echo $item['timestamp_time'] ?></td>
                    <td><?php echo $item['date_time_field'] ?></td>
                    <td><?php echo $item['date_field'] ?></td>
                    <td><?php echo $item['enum_field'] ?></td>
                    <td><?php echo $item['boolean_field'] ?></td>
                    <td><?php echo $item['long_blob_field'] ?></td>
                    <td><?php echo $item['long_text_field'] ?></td>
                    <td><?php echo $item['small_int_field'] ?></td>
                    <td><?php echo $item['uuid_field'] ?></td>
                    <td><?php echo $item['default_null_value'] ?></td>
                    <td>
                      <a href="/admin/crudtests/update/<?php echo $item['crud_test_id'] ?>" class="text-decoration-none" title="Delete"><i class="bi bi-x-pencil-fill me-1"></i> Edit</a>
                      <a href="/admin/crudtests/delete/<?php echo $item['crud_test_id'] ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
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

