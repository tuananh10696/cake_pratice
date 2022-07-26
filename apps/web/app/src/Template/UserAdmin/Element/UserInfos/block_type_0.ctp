<tr id="block_no_<?= h($rownum); ?>" data-sub-block-move="1"
  class="first-dir">
  <td>
    <div class="sort_handle"></div>

  </td>
  <td></td>
  <td>
    <?= $this->Form->input("info_contents.{$rownum}.id", ['type' => 'text', 'value' => h($content['id']), 'id' => 'idBlockId_' . h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.position", ['type' => 'text', 'value' => h($content['position'])]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.block_type", ['type' => 'text', 'value' => h($content['block_type']), 'class' => 'block_type']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.content", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image_pos", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_size", ['type' => 'text', 'value' => '0']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_name", ['type' => 'text', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.section_sequence_id", ['type' => 'text', 'value' => h($content['section_sequence_id']), 'class' => 'section_no']); ?>
    <?= $this->Form->input("info_contents.{$rownum}._block_no", ['type' => 'text', 'value' => h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value", ['type' => 'text', 'value' => h($rownum)]); ?>
  </td>

  <td>
    <div class='btn_area' style='float: right;'>
      <a href="javascript:void(0);" class="btn_confirm small_btn btn_list_delete size_min"
        data-row="<?= h($rownum);?>"
        style='text-align:center; width:auto;'>削除</a>
    </div>
  </td>
</tr>