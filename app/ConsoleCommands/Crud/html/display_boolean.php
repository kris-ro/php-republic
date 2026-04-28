<?php echo $data['indent'] ?><div class="row mt-2">
<?php echo $data['indent'] ?>  <div class="col-sm-12 col-md-4 col-lg-3">
<?php echo $data['indent'] ?>    <?php echo $data['label'] ?>

<?php echo $data['indent'] ?>  </div>
<?php echo $data['indent'] ?>  <div class="col-sm-12 col-md-8 col-lg-9">
<?php echo $data['indent'] ?>    <?php echo '<?php echo self::view(\'item/' . $data['name'] . '\') ? \'Yes\' : \'No\' ?>' ?>

<?php echo $data['indent'] ?>  </div>
<?php echo $data['indent'] ?></div>
