      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
<!--            <img
              src="/admin/assets/img/php-republic.png"
              alt="PHP PhpRepublic"
              class="brand-image opacity-75 shadow"
            />-->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">PHP PhpRepublic</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item <?php echo self::openTreeMenu('account') ?>">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>
                    Account
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" role="navigation" aria-label="Account">
                  <li class="nav-item">
                    <a href="/admin/account/user" class="nav-link <?php echo self::isActiveMenu(['account/user']) ?>">
                      <i class="nav-icon bi bi-person-fill"></i>
                      <p>Profile</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/admin/account/user/tokens" class="nav-link <?php echo self::isActiveMenu(['account/user/tokens', 'account/user/token/add', 'account/user/token/delete']) ?>">
                      <i class="nav-icon bi bi-key-fill"></i>
                      <p>API Tokens</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/admin/account/user/delete" class="nav-link bg-danger text-white">
                      <i class="nav-icon bi bi-x-octagon"></i>
                      <p>Delete Account</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->