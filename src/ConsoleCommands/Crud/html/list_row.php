                  <tr>
<?php foreach ($data['fields'] as $field) { ?>
                    <td class="<?php echo in_array($field['name'], $data['slim_table_fields']) ? '<?php echo self::view(\'slim_select\') ?>' : '' ?>"><?php echo '<?php echo $item[\'' . $field['name'] . '\'] ?>' ?></td>
<?php } ?>
                    <td class="<?php echo '<?php echo self::view(\'slim_select\') ?>' ?>">
                      <a href="/admin/<?php echo $data['controller'] ?>/update/<?php echo  '<?php echo $item[\'' . $data['primary_key'] . '\']' ?> ?>" class="text-decoration-none" title="Edit"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                      <a href="/admin/<?php echo $data['controller'] ?>/delete/<?php echo  '<?php echo $item[\'' . $data['primary_key'] . '\']' ?> ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
                      <a data-value="<?php echo '<?php echo $item[\'' . implode('\'] ?>|<?php echo $item[\'', $data['slim_table_fields']) . '\'] ?>' ?>" class="text-decoration-none slim-select" title="Select"><i class="bi bi-hand-index-fill me-1"></i> Select</a>
                    </td>
                  </tr>