                  <tr>
<?php foreach ($data['fields'] as $field) { ?>
                    <td><?php echo '<?php echo $item[\'' . $field['name'] . '\'] ?>' ?></td>
<?php } ?>
                    <td>
                      <a href="/admin/<?php echo $data['controller'] ?>/update/<?php echo $data['primary_key'] ?>" class="text-decoration-none" title="Delete"><i class="bi bi-x-pencil-fill me-1"></i> Edit</a>
                      <a href="/admin/<?php echo $data['controller'] ?>/delete/<?php echo $data['primary_key'] ?>" class="text-decoration-none text-danger" title="Delete"><i class="bi bi-x-square-fill me-1"></i> Delete</a>
                    </td>
                  </tr>