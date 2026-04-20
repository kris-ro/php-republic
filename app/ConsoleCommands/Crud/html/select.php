<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php echo $data['name'] ?>-id" class="form-label"><?php echo $data['label'] ?></label>
<?php echo $data['indent'] ?>  <select class="form-select" name="<?php echo $data['name'] ?>" id="<?php echo $data['name'] ?>-id" aria-label="<?php echo $data['label'] ?>">
<?php echo $data['indent'] ?>    <option value=""> -- </option>
<?php echo $data['indent'] ?>    <?php echo '<?php foreach ($data[' . $data['name'] . '] as $value => $label) { ?>' ?>

<?php echo $data['indent'] ?>      <option value="<?php echo '<?php echo $value ?>' ?>" <?php echo '<?php echo $value == (Request::post(\'' . $data['name'] . '\') ?: ($data[\'' . $data['name'] . '\'] ?? null)) ? \'selected\' : \'\' ?>' ?>><?php echo '<?php echo $label ?>' ?></option>
<?php echo $data['indent'] ?>    <?php echo '<?php } ?>' ?>

<?php echo $data['indent'] ?>  </select>
<?php echo $data['indent'] ?></div>
