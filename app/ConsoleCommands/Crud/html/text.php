<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php echo $data['name'] ?>-id" class="form-label"><?php echo $data['label'] ?></label>
<?php echo $data['indent'] ?>  <input type="text" class="form-control" name="<?php echo $data['name'] ?>" id="<?php echo $data['name'] ?>-id" value="<?php echo '<?php echo Request::post(\'' . $data['name'] . '\') ?: (self::view(\'item/' . $data['name'] . '\') ?? \'\') ?>' ?>">
<?php echo $data['indent'] ?></div>
