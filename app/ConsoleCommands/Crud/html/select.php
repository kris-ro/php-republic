<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php echo $data['name'] ?>-id" class="form-label"><?php echo $data['label'] ?></label>
<?php echo $data['indent'] ?>  <select class="form-select" name="<?php echo $data['name'] ?>" id="<?php echo $data['name'] ?>-id" aria-label="<?php echo $data['label'] ?>">
<?php echo $data['indent'] ?>    <option value=""> -- </option>
<?php foreach ($data['values'] ?? [] as $value => $label) { ?>
<?php echo $data['indent'] ?>      <option value="<?php echo $value ?>" <?php echo '<?php echo "' . $value . '" == (Request::post(\'' . $data['name'] . '\') ?: (self::view(\'item/' . $data['name'] . '\') ?? \'\')) ? \'selected\' : \'\' ?>' ?>><?php echo $label ?></option>
<?php } ?>
<?php echo $data['indent'] ?>  </select>
<?php echo $data['indent'] ?></div>
