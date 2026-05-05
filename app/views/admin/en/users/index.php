<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Users</h3></div>
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
      <div class="col-sm-12 list-container" id="user-users-list-container" data-list-address="<?php echo self::get('list_address') ?>">
        <div class="card mb-4 list-content" id="user-users-list-content">
          <div class="card-body table-responsive">
            <table id="table-users" class="table table-bordered table-striped table-hover dataTable dtr-inline <?php echo self::view('slim_select') ?>" aria-describedby="users_info">
              <thead>
                <tr>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/id') ?>"
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="User Id"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/id') ?>" class="page-list-sorter">Id
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/name') ?>"
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="Name"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/name') ?>" class="page-list-sorter">Name
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/email') ?>"
                      tabindex="0"
                      aria-controls="table-users"
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
                  <th class=""
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="Status"
                    >
                      Status
                  </th>
                  <th class=""
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="Role"
                    >
                      Role
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/created') ?>"
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="Created"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/created') ?>" class="page-list-sorter">Created
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/updated') ?>"
                      tabindex="0"
                      aria-controls="table-users"
                      rowspan="1"
                      colspan="1"
                      aria-label="Updated"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/updated') ?>" class="page-list-sorter">Updated
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="<?php echo self::view('slim_select') ?>"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-label="Id:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="id"
                           value="<?php echo Request::param('id', ['id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Name:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="name"
                           value="<?php echo Request::param('name', ['name']) ?>"
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
                  <td data-label="Status:">
                    <select name="status" class="form-control table-search">
                      <option value=""> -- </option>
                      <option value="1" <?php echo Request::param('status', ['status']) === '1' ? 'selected' : '' ?>>Active</option>
                      <option value="0" <?php echo Request::param('status', ['status']) === '0' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                  </td>
                  <td data-label="Role:" class="">
                  </td>
                  <td data-label="Created:" class="">
                    <input type="date"
                           name="created"
                           value="<?php echo Request::param('created', ['created']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Updated:" class="">
                    <input type="date"
                           name="updated"
                           value="<?php echo Request::param('updated', ['updated']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td class="<?php echo self::view('slim_select') ?>"></td>
                </tr>
                <?php foreach (self::view('items') ?: [] as $item) { ?>
                  <tr>
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['id'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['username'] ?></td>
                    <td class=""><?php echo $item['email'] ?></td>
                    <td class=""><?php echo $item['is_active'] ?></td>
                    <td class=""><?php echo $item['role'] ?></td>
                    <td class=""><?php echo $item['created'] ?></td>
                    <td class=""><?php echo $item['updated'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>">
                      <a data-value="<?php echo $item['id'] ?>|<?php echo $item['username'] ?>" class="text-decoration-none slim-select" title="Select"><i class="bi bi-hand-index-fill me-1"></i> Select</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Role</th>
                  <th>Created</th>
                  <th>Updated</th>
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

