<?php
  use KrisRo\PhpRepublic\Strings;
?>
                  <th class="sorting <?php echo '<?php echo self::get(\'sort_classes/' . $data['name'] . '\') ?>' ?>"
                      tabindex="0"
                      aria-controls="user-tokens"
                      rowspan="1"
                      colspan="1"
                      aria-label="<?php echo Strings::prettify($data['name']) ?>"
                      <?php if ($data['key']) { ?>
                        aria-sort="ascending"
                      <?php } ?>
                    >
                    <?php if ($data['key']) { ?>
                      <a href="<?php echo '<?php echo self::get(\'sort_urls/' . $data['name'] . '\') ?>' ?>" class="page-list-sorter"><?php echo Strings::prettify($data['name']) ?>
                        <i class="bi bi-caret-down-fill desc"></i>
                        <i class="bi bi-caret-up-fill asc"></i>
                      </a>
                    <?php } else { ?>
                      <?php echo Strings::prettify($data['name']) ?>
                    <?php } ?>
                  </th>