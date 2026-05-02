              <li class="nav-item <?php echo '<?php echo self::openTreeMenu(\'' . $data['menu_item_path'] . '\') ?>' ?>">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-caret-right-fill"></i>
                  <p>
                    <?php echo $data['menu_item_name'] . PHP_EOL ?>
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                  <li class="nav-item">
                    <a href="/admin/<?php echo $data['menu_item_path'] ?>" class="nav-link <?php echo  '<?php echo self::isActiveMenu([\'' . $data['menu_item_path'] . '\']) ?>' ?>">
                      <i class="bi bi-list-columns-reverse nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/admin/<?php echo $data['menu_item_path'] ?>/add" class="nav-link <?php echo  '<?php echo self::isActiveMenu([\'' . $data['menu_item_path'] . '/add\']) ?>' ?>">
                      <i class="bi bi-plus-square nav-icon"></i>
                      <p>Add</p>
                    </a>
                  </li>
                </ul>
              </li>