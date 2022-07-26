<tr id="append_block-<?= $append['slug'] ?>">
  <td>
    <?= h($append['name']);?>
    <?= ($append['is_required'] == 1) ? '<span class="attent">※必須</span>' : '';?>
  </td>

  <td class="edit_image_area">

    <?php if ($this->Common->isUserRole($append->editable_role)): ?>

    <!-- <td> -->
    <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
    <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $append['is_required']]);?>
    <?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_textarea", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => '0']);?>
    <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden', 'value' => '']);?>
    <?= $this->Form->input("info_append_items.{$num}.image", ['type' => 'hidden', 'value' => '']);?>

    <dd><?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'select', 'options' => ${$append['slug'] . '_list'}, 'empty' => '未選択']); ?>
    </dd>

    <?php else: ?>

    <?php $data = $this->request->data['info_append_items'][$num] ?? []; ?>
    <?= ${$append['slug'] . '_list'}[$data['value_int']] ?? '' ?>

    <?php endif; ?>

  </td>
</tr>