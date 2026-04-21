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
                                          <a href="<?php echo self::get('sort_urls/crud_test_id') ?>" class="page-list-sorter">Crud Test Id                        <i class="bi bi-caret-down-fill desc"></i>
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
                                          <a href="<?php echo self::get('sort_urls/email') ?>" class="page-list-sorter">Email                        <i class="bi bi-caret-down-fill desc"></i>
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
                                          Price                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/timestamp_time') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Timestamp Time"
                                          >
                                          Timestamp Time                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/date_time_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Time Field"
                                          >
                                          Date Time Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/date_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Date Field"
                                          >
                                          Date Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/enum_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Enum Field"
                                          >
                                          Enum Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/boolean_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Boolean Field"
                                          >
                                          Boolean Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/long_blob_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Blob Field"
                                          >
                                          Long Blob Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/long_text_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Long Text Field"
                                          >
                                          Long Text Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/small_int_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Small Int Field"
                                          >
                                          Small Int Field                                      </th>
                  <th class="sorting <?php echo self::get('sort_classes/uuid_field') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Uuid Field"
                                              aria-sort="ascending"
                                          >
                                          <a href="<?php echo self::get('sort_urls/uuid_field') ?>" class="page-list-sorter">Uuid Field                        <i class="bi bi-caret-down-fill desc"></i>
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
                                          Default Null Value                                      </th>
                </tr>

