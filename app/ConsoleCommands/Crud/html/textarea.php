<?php echo $data['indent'] ?><div class="mb-3">
<?php echo $data['indent'] ?>  <label for="<?php $data['name'] ?>-id" class="form-label"><?php $data['label'] ?></label>
<?php echo $data['indent'] ?>  <textarea class="form-control" id="<?php $data['name'] ?>-id" rows="3"><?php $data['value'] ?? null ?></textarea>
<?php echo $data['indent'] ?></div>