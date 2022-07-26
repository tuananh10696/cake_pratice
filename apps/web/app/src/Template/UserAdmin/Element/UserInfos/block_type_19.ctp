<tr id="block_no_<?= h($rownum); ?>" data-sub-block-move="0"
  class="first-dir">
  <td>
    <div class="sort_handle"></div>
    <?= $this->Form->input("info_contents.{$rownum}.id", ['type' => 'hidden', 'value' => h($content['id']), 'id' => 'idBlockId_' . h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.position", ['type' => 'hidden', 'value' => h($content['position'])]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.block_type", ['type' => 'hidden', 'value' => h($content['block_type']), 'class' => 'block_type']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_size", ['type' => 'hidden', 'value' => '0']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.file_name", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.section_sequence_id", ['type' => 'hidden', 'value' => h($content['section_sequence_id']), 'class' => 'section_no']); ?>
    <?= $this->Form->input("info_contents.{$rownum}._block_no", ['type' => 'hidden', 'value' => h($rownum)]); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value", ['type' => 'hidden', 'value' => '']); ?>
    <?= $this->Form->input("info_contents.{$rownum}.option_value2", ['type' => 'hidden', 'value' => '']); ?>
  </td>

  <td colspan="2">
    <div class="sub-unit__wrap">
      <h4><?= (h($rownum) + 1); ?>.インフォ</h4>

      <?php $image_column = 'image'; ?>
      <dl style="border:1px solid #cbcbcb;padding: 10px;">
        <dt>１．名前</dt>
        <dd>
          <?= $this->Form->input("info_contents.{$rownum}.title", ['type' => 'text', 'style' => 'width: 100%;', 'maxlength' => 20, ]); ?>
          <div>※２０文字以内</div>
        </dd>

        <dt>２．画像</dt>
        <dd>
          <?php if (!empty($content['attaches'][$image_column]['0'])) :?>
          <div>
            <a href="<?= h($content['attaches'][$image_column]['0']);?>"
              class="pop_image_single">
              <img
                src="<?= $this->Url->build($content['attaches'][$image_column]['0'])?>"
                style="width: 200px; float: left;">
              <?= $this->Form->input("info_contents.{$rownum}.attaches.{$image_column}.0", ['type' => 'hidden']); ?>
            </a><br>

            <?php $old = $content['_old_' . $image_column] ?? ($content[$image_column] ?? ''); ?>
            <?= $this->Form->input("info_contents.{$rownum}._old_{$image_column}", array('type' => 'hidden', 'value' => h($old))); ?>
          </div>
          <?php endif;?>

          <div>
            <?= $this->Form->input("info_contents.{$rownum}.{$image_column}", array('type' => 'file', 'class' => 'attaches'));?>
            <div class="remark">※jpeg , jpg , gif , png ファイルのみ</div>
            <div><?= $this->Form->getRecommendSize('InfoContents', 'image', ['before' => '※', 'after' => '']); ?>
            </div>
            <div>※ファイルサイズ５MB以内</div>
            <br />
          </div>
          <div style="clear: both;"></div>
        </dd>

        <dt>3．紹介文</dt>
          <div
            class="font_target">
            <?= $this->Form->input("info_contents.{$rownum}.content", ['type' => 'textarea', 'class' => '']); ?>
          </div>

      </dl>

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