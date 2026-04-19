        <?php $pageData = self::pagination('', false); ?>
        <?php if ($pageData['page_count'] ?? null) { ?>
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-end">
              <li class="page-item pe-2 pt-1">
                Showing <?php echo $pageData['start'] + min(1, intval($pageData['total'])) ?> to <?php echo $pageData['stop'] ?> of <?php echo $pageData['total'] ?> entries
              </li>
              <?php if ($pageData['previous']['href'] ?? null) { ?>
              <li class="page-item"><a class="page-link" href="<?php echo self::list_address('', false) . ($pageData['previous']['href'] ?? '') ?>">«</a></li>
              <?php } ?>
              <?php foreach (($pageData['pages'] ?? []) as $page) { ?>
                <?php if (isset($page['href'])) { ?>
                  <li class="page-item <?php echo $page['class'] ?>"><a class="page-link" id="page-link-<?php echo $page['page'] ?>" href="<?php echo self::list_address('', false) . $page['href'] ?>"><?php echo $page['page'] ?></a></li>
                <?php } else { ?>
                  <li class="page-item"><a class="page-link"><?php echo $page['page'] ?></a></li>
                <?php } ?>
              <?php } ?>
              <?php if ($pageData['next']['href'] ?? null) { ?>
              <li class="page-item"><a class="page-link" href="<?php echo self::list_address('', false) . ($pageData['next']['href'] ?? '') ?>">»</a></li>
              <?php } ?>
            </ul>
          </div>
        <?php }