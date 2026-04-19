<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php $data['name'] ?>-id" class="form-label"><?php $data['label'] ?></label>
<?php echo $data['indent'] ?>  <select class="form-select" id="<?php $data['name'] ?>-id" aria-label="<?php $data['label'] ?>">
<?php echo $data['indent'] ?>    <option value="">Open this select menu</option>
<?php foreach ($data['values'] as $value => $label) { ?>
<?php echo $data['indent'] ?>    <option value="<?php echo $value ?>"><?php echo $label ?></option>
<?php } ?>
<?php echo $data['indent'] ?>  </select>
<?php echo $data['indent'] ?></div>