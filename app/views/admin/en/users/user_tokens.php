<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-8"><h3 class="mb-0">Account &raquo; User Tokens</h3></div>
      <div class="col-sm-4 text-end"><a href="/admin/account/user/token/add" class="btn btn-link text-decoration-none"><i class="bi bi-plus"></i> Add Token</a></div>
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

      <?php echo self::view('token_add_form', false) ?? '' ?>

      <!--begin::Col-->
      <div class="<?php echo (self::view('token_add_form') ?? '') ? 'col-lg-9 col-sm-12' : 'col-12' ?> list-container" id="user-tokens-list-container" data-list-address="<?php echo self::get('list_address') ?>">

        <div class="card mb-4 list-content" id="user-tokens-list-content">
          <div class="card-body table-responsive">
            <table id="user-tokens" class="table table-bordered table-striped table-hover dataTable dtr-inline" aria-describedby="example2_info">
              <thead>
                <tr>
                  <th class="sorting <?php echo self::get('sort_classes/id') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="ID"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/id') ?>" class="page-list-sorter">ID
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/label') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Label"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/label') ?>" class="page-list-sorter">Label
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/fingerprint') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Fingerprint"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/fingerprint') ?>" class="page-list-sorter">Fingerprint
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/revoked') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Revoked"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/revoked') ?>" class="page-list-sorter">Revoked
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/expires') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Expires on"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/expires') ?>" class="page-list-sorter">Expires on
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th class="sorting <?php echo self::get('sort_classes/created') ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Created on"
                      aria-sort="ascending">
                    <a href="<?php echo self::get('sort_urls/created') ?>" class="page-list-sorter" class="page-list-sorter">Created on
                      <i class="bi bi-caret-down-fill desc"></i>
                      <i class="bi bi-caret-up-fill asc"></i>
                    </a>
                  </th>
                  <th
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="Add">
                    &nbsp;
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-search-row">
                  <td data-label="Token ID:">
                    <input type="text"
                           name="id"
                           value="<?php echo Request::param('id', ['id']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                           size="5"
                          >
                  </td>
                  <td data-label="Token Label:">
                    <input type="text"
                           name="label"
                           value="<?php echo Request::param('label', ['label']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td data-label="Fingerprint:">
                    <input type="text"
                           name="fingerprint"
                           value="<?php echo Request::param('fingerprint', ['fingerprint']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td>
                    <select name="revoked" class="form-control table-search">
                      <option value=""> -- </option>
                      <option value="1" <?php echo Request::param('revoked', ['revoked']) === '1' ? 'selected' : '' ?>>Yes</option>
                      <option value="0" <?php echo Request::param('revoked', ['revoked']) === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                  </td>
                  <td>
                    <input type="date"
                           name="expires"
                           value="<?php echo Request::param('expires', ['expires']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td>
                    <input type="date"
                           name="created"
                           value="<?php echo Request::param('created', ['created']) ?>"
                           data-base_url="<?php echo self::get('list_address') ?>"
                           class="form-control table-search"
                          >
                  </td>
                  <td>
                  </td>
                </tr>
                <?php foreach (self::view('tokens') ?: [] as $token) { ?>
                  <tr>
                    <td><?php echo $token['id'] ?></td>
                    <td><?php echo $token['label'] ?></td>
                    <td><?php echo $token['fingerprint'] ?></td>
                    <td><?php echo $token['revoked'] ?></td>
                    <td><?php echo $token['expires'] ?></td>
                    <td><?php echo $token['created'] ?></td>
                    <td>
                      <a href="/admin/account/user/token/delete/<?php echo $token['id'] ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Label</th>
                  <th>Fingerprint</th>
                  <th>Revoked</th>
                  <th>Expires on</th>
                  <th>Created on</th>
                  <th>&nbsp;</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->

          <?php echo self::paginationTemplate() ?>
        </div>

      </div>
    </div>
  </div>
</div>
<!--end::App Content-->