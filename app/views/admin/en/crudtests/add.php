<?php
use KrisRo\PhpRepublic\Request;
?>
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0"> Admin &raquo; crud test &raquo; Add</h3></div>
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
          <div class="card-header"><div class="card-title">Add crud test</div></div>
          <!--end::Header-->
          <!--begin::Form-->
          <form action="/admin/crudtests/add" method="POST">
            <?php echo self::getFormToken('addcrud_test') // self is instance of KrisRo\PhpRepublic\Template ?>
            <!--begin::Body-->
            <div class="card-body">
<>
<>
<>
<>
<>
              <div class="mb-3">
                <label for="-id" class="form-label"></label>
                <select class="form-select" id="-id" aria-label="">
                  <option value="">Open this select menu</option>
                  <option value="aaa">aaa</option>
                  <option value="bbb">bbb</option>
                  <option value="ccc">ccc</option>
                </select>
              </div>
<>
<>
              <div class="mb-3">
                <label for="-id" class="form-label"></label>
                <textarea class="form-control" id="-id" rows="3"></textarea>
              </div>
<>
<>
<>
            </div>
          </form>
        </div>

