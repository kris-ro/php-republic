<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Todo &raquo; Add</h3></div>
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
          <div class="card-header"><div class="card-title">Add Todo</div></div>
          <!--end::Header-->
          <!--begin::Form-->
          <form action="/admin/todos/add" method="POST" enctype="multipart/form-data">
            <?php echo self::getFormToken('addtodo') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
              <div class="mb-3">
                <label for="title-id" class="form-label">Title</label>
                <input type="text" class="form-control <?php echo self::view('errors/title') ? 'border-danger text-danger' : '' ?>" name="title" id="title-id" value="<?php echo Request::isPost() ? Request::post('title') : (self::view('item/title') ?? '') ?>">
                <?php if (self::view('errors/title')) { ?>
                  <div id="title-id" class="form-text text-danger">
                    <?php echo self::view('errors/title') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="details-id" class="form-label">Details</label>
                <textarea class="form-control <?php echo self::view('errors/details') ? 'border-danger text-danger' : '' ?>" name="details" id="details-id" rows="3"><?php echo Request::isPost() ? Request::post('details') : (self::view('item/details') ?? '') ?>
</textarea>
                <?php if (self::view('errors/details')) { ?>
                  <div id="details-id" class="form-text text-danger">
                    <?php echo self::view('errors/details') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="status-id" class="form-label">Status</label>
                <select class="form-select <?php echo self::view('errors/status') ? 'border-danger text-danger' : '' ?>" name="status" id="status-id" aria-label="Status">
                  <option value=""> -- </option>
                    <option value="new" <?php echo "new" == (Request::isPost() ? Request::post('status') : (self::view('item/status', true, '') ?? '')) ? 'selected' : '' ?>>New</option>
                    <option value="in progress" <?php echo "in progress" == (Request::isPost() ? Request::post('status') : (self::view('item/status', true, '') ?? '')) ? 'selected' : '' ?>>In Progress</option>
                    <option value="in review" <?php echo "in review" == (Request::isPost() ? Request::post('status') : (self::view('item/status', true, '') ?? '')) ? 'selected' : '' ?>>In Review</option>
                    <option value="in tests" <?php echo "in tests" == (Request::isPost() ? Request::post('status') : (self::view('item/status', true, '') ?? '')) ? 'selected' : '' ?>>In Tests</option>
                    <option value="done" <?php echo "done" == (Request::isPost() ? Request::post('status') : (self::view('item/status', true, '') ?? '')) ? 'selected' : '' ?>>Done</option>
                </select>
                <?php if (self::view('errors/status')) { ?>
                  <div id="status-id" class="form-text text-danger">
                    <?php echo self::view('errors/status') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="users_id-id" class="form-label">Users Id</label>
                <div class="input-group">
                  <input type="text" class="form-control <?php echo self::view('errors/users_id') ? 'border-danger text-danger' : '' ?>" name="users_id" id="users_id-id" value="<?php echo Request::isPost() ? Request::post('users_id') : (self::view('item/users_id') ?? '') ?>" aria-describedby="user-selector">
                  <div class="input-group-text slim-selector-trigger" id="user-selector" data-source="/admin/users?slim_table=1&target=users_id-id"><i class="bi bi-hand-index-fill" data-source="/admin/users?slim_table=1&target=users_id-id"></i></div>
                </div>
                <?php if (self::view('errors/users_id')) { ?>
                  <div id="users_id-id" class="form-text text-danger">
                    <?php echo self::view('errors/users_id') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="created-id" class="form-label">Created</label>
                <input type="datetime-local" class="form-control <?php echo self::view('errors/created') ? 'border-danger text-danger' : '' ?>" name="created" id="created-id" value="<?php echo Request::isPost() ? Request::post('created') : (self::view('item/created') ?? '') ?>">
                <?php if (self::view('errors/created')) { ?>
                  <div id="created-id" class="form-text text-danger">
                    <?php echo self::view('errors/created') ?>
                  </div>
                <?php } ?>
              </div>

              <div class="mb-3">
                <label for="updated-id" class="form-label">Updated</label>
                <input type="datetime-local" class="form-control <?php echo self::view('errors/updated') ? 'border-danger text-danger' : '' ?>" name="updated" id="updated-id" value="<?php echo Request::isPost() ? Request::post('updated') : (self::view('item/updated') ?? '') ?>">
                <?php if (self::view('errors/updated')) { ?>
                  <div id="updated-id" class="form-text text-danger">
                    <?php echo self::view('errors/updated') ?>
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

