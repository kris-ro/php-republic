<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Todo</h3></div>
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
      <div class="col-sm-12 list-container" id="user-todos-list-container" data-list-address="<?php echo self::get('list_address') ?>">
        <div class="card mb-4 list-content" id="user-todos-list-content">
          <div class="card-body table-responsive">
            <table id="table-todos" class="table table-bordered table-striped table-hover dataTable dtr-inline <?php echo self::view('slim_select') ?>" aria-describedby="todos_info">
              <thead>
                <tr>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/todo_id') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
                      rowspan="1"
                      colspan="1"
                      aria-label="Todo Id"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/todo_id') ?>" class="page-list-sorter">Todo Id
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting <?php echo self::view('slim_select') ?> <?php echo self::get('sort_classes/title') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
                      rowspan="1"
                      colspan="1"
                      aria-label="Title"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/title') ?>" class="page-list-sorter">Title
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/details') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
                      rowspan="1"
                      colspan="1"
                      aria-label="Details"
                    >
                      Details
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/status') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
                      rowspan="1"
                      colspan="1"
                      aria-label="Status"
                    >
                      Status
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/users_id') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
                      rowspan="1"
                      colspan="1"
                      aria-label="Users Id"
                      aria-sort="ascending"
                    >
                      <a href="<?php echo self::get('sort_urls/users_id') ?>" class="page-list-sorter">Users Id
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                  </th>
                  <th class="sorting  <?php echo self::get('sort_classes/created') ?>"
                      tabindex="0"
                      aria-controls="table-todos"
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
                      aria-controls="table-todos"
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
                  <td data-label="Todo Id:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="todo_id"
                           value="<?php echo Request::param('todo_id', ['todo_id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Title:" class="<?php echo self::view('slim_select') ?>">
                    <input type="text"
                           name="title"
                           value="<?php echo Request::param('title', ['title']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Details:" class="">
                  </td>
                  <td data-label="Status:" class="">
                  </td>
                  <td data-label="Users Id:" class="">
                    <input type="text"
                           name="users_id"
                           value="<?php echo Request::param('users_id', ['users_id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
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
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['todo_id'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>"><?php echo $item['title'] ?></td>
                    <td class=""><?php echo $item['details'] ?></td>
                    <td class=""><?php echo $item['status'] ?></td>
                    <td class=""><?php echo $item['users_id'] ?></td>
                    <td class=""><?php echo $item['created'] ?></td>
                    <td class=""><?php echo $item['updated'] ?></td>
                    <td class="<?php echo self::view('slim_select') ?>">
                      <a href="/admin/todos/update/<?php echo $item['todo_id'] ?>" class="text-decoration-none" title="Delete"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                      <a href="/admin/todos/delete/<?php echo $item['todo_id'] ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
                      <a data-value="<?php echo $item['todo_id'] ?>|<?php echo $item['title'] ?>" class="text-decoration-none slim-select" title="Select"><i class="bi bi-hand-index-fill me-1"></i> Select</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Todo Id</th>
                  <th>Title</th>
                  <th>Details</th>
                  <th>Status</th>
                  <th>Users Id</th>
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

