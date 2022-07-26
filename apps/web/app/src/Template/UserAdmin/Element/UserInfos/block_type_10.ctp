<tr id="block_no_<?= h($rownum); ?>" data-sub-block-move="0"
  class="first-dir">
  <td>
    <div class="sort_handle"></div>
    <?= $this->Form->input("info_contents.{$rownum}.id", ['type' => 'hidden', 'value' => h($content['id']), 'id' => 'idBlockId_' . h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.position", ['type' => 'hidden', 'value' => h($content['position'])]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.block_type", ['type' => 'hidden', 'value' => h($content['block_type']), 'class' => 'block_type']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.content", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.image_pos", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_size", ['type' => 'hidden', 'value' => '0']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_name", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.section_sequence_id", ['type' => 'hidden', 'value' => h($content['section_sequence_id']), 'class' => 'section_no']); ?>
    <?= $this->Form->input("info_contents.{$rownum}._block_no", ['type' => 'hidden', 'value' => h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value2", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value3", ['type' => 'hidden', 'value' => '']); ?>
  </td>

  <td colspan="2">
    <div
      class="sub-unit__wrap <?= $content['option_value']; ?> <?= $content['option_value2']; ?> <?= ($content['option_value3'] ? 'waku_width_' . $content['option_value3'] : ''); ?>">
      <h4><?= (h($rownum) + 1); ?>.枠</h4>

      <table style="margin: 0; width: 100%;table-layout: fixed;"
        id="wakuId_<?= h($content['section_sequence_id']);?>"
        data-section-no="<?= h($content['section_sequence_id']);?>"
        data-block-type="<?= h($content['block_type']); ?>">
        <colgroup>
          <col style="width: 70px;">
          <col style="width: 150px;">
          <col>
          <col style="width: 90px;">
        </colgroup>
        <thead>

        </thead>
        <tbody class="list_table_sub"
          data-waku-block-type="<?= $content['block_type'];?>">
          <tr>
            <td colspan="4" class="td__movable old-style" style="border-bottom: 1px solid #cbcbcb;">
              <div style="text-align: right;float: right;">
              </div>
              ここへブロックを移動できます
            </td>
          </tr>
          <?php if (array_key_exists('sub_contents', $content)): ?>
          <?php foreach ($content['sub_contents'] as $sub_key => $sub_val): ?>
          <?php $block_type = h($sub_val['block_type']); ?>
          <?= $this->element("UserInfos/block_type_{$block_type}", ['rownum' => h($sub_val['_block_no']), 'content' => h($sub_val)]); ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </td>
  <td>
    <div class='btn_area' style='float: right;'>
      <a href="javascript:void(0);" class="btn_confirm small_btn btn_list_delete size_min"
        data-row="<?= h($rownum);?>"
        style='text-align:center; width:auto;'>削除</a>
    </div>
  </td>
</tr>