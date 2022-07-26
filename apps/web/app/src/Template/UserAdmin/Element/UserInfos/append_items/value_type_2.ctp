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
        <?php
if (empty($append['max_length']) || $append['max_length'] == 0) {
    $length = '';
} else {
    $length = $append['max_length'];
}
$placeholder = '';
if (isset($placeholder_list[$append['slug']])) {
    $placeholder = $placeholder_list[$append['slug']];
}
$notes = '';
if (isset($notes_list[$append['slug']])) {
    $notes = $notes_list[$append['slug']];
}
?>
        <?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'text', 'maxlength' => $length, 'default' => empty($append['value_default']) ? '' : h($append['value_default']), 'placeholder' => h($append['value_placeholder'] ?? '')]); ?>
        <?= empty($length) ? '' : '<br><span>※' . h($length) . '文字以内で入力してください</span>';?>
        <?= empty($notes) ? '' : '<br><span>※' . h($notes) . '</span>';?>
        <?= $this->Html->view($append['attention'], ['before' => '<br><span>', 'after' => '</span>']); ?>
        <?= $this->Form->error("{$slug}.{$append['slug']}") ?>


        <?php else: ?>

        <?php $data = $this->request->data['info_append_items'][$num] ?? []; ?>
        <?= h($data['value_text'] ?? '') ?>

        <?php endif; ?>

    </td>
</tr>