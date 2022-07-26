<tr id="append_block-<?= $append['slug'] ?>">
  <td>
    <?= h($append['name']);?>
    <?= ($append['is_required'] == 1) ? '<span class="attent">※必須</span>' : '';?>
  </td>
  <td>

    <?php if ($this->Common->isUserRole($append->editable_role)): ?>

    <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $append['is_required']]);?>
    <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.image", ['type' => 'hidden', 'value' => '']);?>
    <?php $image_column = 'value_text'; ?>

    <dl>
      <?php if (!in_array($slug, ['voice'])): ?>
      <dt></dt>
      <?php else:?>
      <?php endif;?>
      <dd>
        <button type="button" class="media-upload"
          data-slug="<?= $append['slug'];?>"><?= __('ファイル選択'); ?></button>
        <div class="wrap"
          id="append_block_image_<?= $append['slug'];?>">
          <?php if (!empty($entity['info_append_items'][$num]['value_text'])): ?>
          <p><img class="image-view"
              src="<?= $entity['info_append_items'][$num]['value_text']; ?>"
              width="260"></p>
          <?php else: ?>
          <p class="image-view-block"></p>
          <?php endif; ?>
          <p><?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'hidden', 'class' => 'image-url']); ?>
          </p>
        </div>

        <div style="clear: both;"></div>
      </dd>

      <?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'hidden', 'value' => '0']) ?>
      <?= $this->Form->input("info_append_items.{$num}.value_textarea", ['type' => 'hidden', 'value' => '']) ?>

    </dl>

    <?php else: ?>

    <p><img class="image-view"
        src="<?= $entity['info_append_items'][$num]['value_text']; ?>"
        width="260"></p>

    <?php endif; ?>


  </td>
</tr>