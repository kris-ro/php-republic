<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php echo $data['name'] ?>-id" class="form-label"><?php echo $data['label'] ?></label>
<?php echo $data['indent'] ?>  <textarea class="form-control <?php echo '<?php echo self::view(\'errors/' . $data['name'] . '\') ? \'border-danger text-danger\' : \'\' ?>' ?>" name="<?php echo $data['name'] ?>" id="<?php echo $data['name'] ?>-id" rows="3"><?php echo '<?php echo Request::isPost() ? Request::post(\'' . $data['name'] . '\') : (self::view(\'item/' . $data['name'] . '\') ?? \'\') ?>' . PHP_EOL ?></textarea>
<?php echo $data['indent'] ?>  <?php echo '<?php if (self::view(\'errors/' . $data['name'] . '\')) { ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?>    <div id="<?php echo $data['name'] ?>-id" class="form-text text-danger">
<?php echo $data['indent'] ?>      <?php echo '<?php echo self::view(\'errors/' . $data['name'] . '\') ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?>    </div>
<?php echo $data['indent'] ?>  <?php echo '<?php } ?>' . PHP_EOL ?>
<?php echo $data['indent'] ?></div>
