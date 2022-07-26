<?php
//項目名
$title_name = $_page_config_item['title'] ? $_page_config_item['title'] : 'メイン画像';
$sub_title_name = $_page_config_item['sub_title'] ? $_page_config_item['sub_title'] : (
  ($page_config->list_style == $PageConfig::LIST_STYLE_THUMBNAIL) ? '一覧と詳細に表示' : '詳細に表示'
);
?>

<?php $image_column = 'image'; ?>

<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
?>
<tr>
  <td>
    <?= $title_name ?>
    <div>(<?= $sub_title_name ?>)</div>
  </td>
  <td class="edit_image_area">

    <ul>
      <?php if (!empty($data['attaches'][$image_column]['0'])) :?>
      <li>
        <a href="<?= $data['attaches'][$image_column]['0'];?>"
          class="pop_image_single">
          <img
            src="<?= $this->Url->build($data['attaches'][$image_column]['0'])?>"
            style="width: 300px;">
          <?= $this->Form->input("attaches.{$image_column}.0", ['type' => 'hidden']); ?>
        </a><br>

        <?php $old = $data['_old_' . $image_column] ?? ($data[$image_column] ?? ''); ?>
        <?= $this->Form->input("_old_{$image_column}", array('type' => 'hidden', 'value' => h($old))); ?>

        <div class="btn_area" style="width: 300px;">
          <a href="javascript:kakunin('画像を削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $data['id'], 'image', $image_column)) ?>')"
            class="btn_delete">画像の削除</a>
        </div>
      </li>
      <?php endif;?>

      <li>
        <?= $this->Form->input($image_column, array('type' => 'file', 'accept' => 'image/jpeg,image/png,image/gif', 'id' => 'idMainImage', 'class' => 'attaches'));?>
        <div class="remark">※jpeg , jpg , gif , png ファイルのみ</div>
        <div><?= $this->Form->getRecommendSize('Infos', 'image', ['before' => '※', 'after' => '']); ?>
        </div>
        <div>※ファイルサイズ５MB以内</div>
        <br />
      </li>

    </ul>
  </td>
</tr>
<?php else: ?>

<tr>
  <td>
    <?= $title_name ?>
    <div>(<?= $sub_title_name ?>)</div>
  </td>
  <td class="edit_image_area">
    <ul>
      <?php if (!empty($data['attaches'][$image_column]['0'])) :?>
      <li>
        <a href="<?= $data['attaches'][$image_column]['0'];?>"
          class="pop_image_single">
          <img
            src="<?= $this->Url->build($data['attaches'][$image_column]['0'])?>"
            style="width: 300px;">
        </a><br>
      </li>
      <?php endif;?>

    </ul>
  </td>
</tr>
<?php endif;
