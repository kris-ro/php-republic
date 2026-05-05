<?php
  use KrisRo\PhpRepublic\Strings;
?>
                  <td data-label="<?php echo Strings::prettify($data['name']) ?>:" class="<?php echo $data['slim_table'] ? '<?php echo self::view(\'slim_select\') ?>' : '' ?>">
<?php if ($data['key']) { ?>
<?php if ($data['type'] == 'TEXT') { ?>
                    <input type="text"
                           name="<?php echo $data['name'] ?>"
                           value="<?php echo '<?php echo Request::param(\'' . $data['name'] . '\', [\'' . $data['name'] . '\']) ?>' ?>"
                           data-base_url="<?php echo '<?php echo self::get(\'list_address\') ?>' ?>"
                           class="form-control table-search"
                          >
<?php } ?>
<?php if ($data['type'] == 'DATE') { ?>
                    <input type="date"
                           name="<?php echo $data['name'] ?>"
                           value="<?php echo '<?php echo Request::param(\'' . $data['name'] . '\', [\'' . $data['name'] . '\']) ?>' ?>"
                           data-base_url="<?php echo '<?php echo self::get(\'list_address\') ?>' ?>"
                           class="form-control table-search"
                          >
<?php } ?>
<?php if ($data['type'] == 'TIME') { ?>
                    <input type="time"
                           name="<?php echo $data['name'] ?>"
                           value="<?php echo '<?php echo Request::param(\'' . $data['name'] . '\', [\'' . $data['name'] . '\']) ?>' ?>"
                           data-base_url="<?php echo '<?php echo self::get(\'list_address\') ?>' ?>"
                           class="form-control table-search"
                          >
<?php } ?>
<?php if ($data['type'] == 'SELECT') { ?>
                    <select name="<?php echo $data['name'] ?>" class="form-control table-search">
                      <option value=""> -- </option>
<?php foreach ($data['options'] as $key => $label) { ?>
                      <option value="<?php echo $key ?>" <?php echo '<?php echo Request::param(\'' . $data['name'] . '\', [\'' . $data['name'] . '\']) === \'' . $key . '\' ? \'selected\' : \'\' ?>' ?>><?php echo $label ?></option>
<?php } ?>
                    </select>
<?php } ?>
<?php } ?>
                  </td>