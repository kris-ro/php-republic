<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php echo $data['name'] ?>-id" class="form-label"><?php echo $data['label'] ?></label>
<?php echo $data['indent'] ?>  <select class="form-select <?php echo '<?php echo self::view(\'errors/' . $data['name'] . '\') ? \'border-danger text-danger\' : \'\' ?>' ?>" name="<?php echo $data['name'] ?>" id="<?php echo $data['name'] ?>-id" aria-label="<?php echo $data['label'] ?>">
<?php echo $data['indent'] ?>    <option value=""> -- </option>
<?php foreach ($data['values'] ?? [] as $value => $label) { ?>
<?php echo $data['indent'] ?>      <option value="<?php echo $value ?>" <?php echo '<?php echo "' . $value . '" == (Request::isPost() ? Request::post(\'' . $data['name'] . '\') : (self::view(\'item/' . $data['name'] . '\') ?? \'\')) ? \'selected\' : \'\' ?>' ?>><?php echo $label ?></option>
<?php } ?>
<?php echo $data['indent'] ?>  </select>
<?php echo $data['indent'] ?>  <?php echo '<?php if (self::view(\'errors/' . $data['name'] . '\')) { ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?>    <div id="<?php echo $data['name'] ?>-id" class="form-text text-danger">
<?php echo $data['indent'] ?>      <?php echo '<?php echo self::view(\'errors/' . $data['name'] . '\') ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?>    </div>
<?php echo $data['indent'] ?>  <?php echo '<?php } ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?></div>
