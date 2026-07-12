<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; Debug &raquo; Emails</h3></div>
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
      

      <!--begin::left-col-->
      <div class="col-lg-5 col-sm-12">

        <div class="card mb-4 list-content" id="debug-emails-list-content">
          <div class="card-body table-responsive">
            <table id="debug-emails" class="table table-bordered table-striped table-hover dataTable dtr-inline" aria-describedby="debug-emails">
              <thead>
                <tr>
                  <th
                      tabindex="0"
                      aria-controls="debug-emails"
                      rowspan="1"
                      colspan="1"
                      aria-label="Emails">
                    <?php if (empty(self::view('files'))) { ?> 
                      No emails
                    <?php } else { ?>
                      Emails
                    <?php } ?>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (self::view('files') ?: [] as $file) { ?>
                  <tr>
                    <td><a href="/admin/debug/emails?file=<?php echo urlencode($file) ?>" class="text-dark decoration-none"><?php echo $file ?></a></td>
                  </tr>
                <?php } ?>
              </tbody>              
            </table>
          </div>
        </div>

      </div>
      <!--end::left-col-->

      <?php if (self::view('email')) { ?>
        <!--begin::right-col-->
        <div class="col-lg-7 col-sm-12">
          <div id="mail">
            <?php echo self::view('email', false) ?>
          </div>
        </div>
        <!--end::right-col-->
      <?php } ?>

    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->