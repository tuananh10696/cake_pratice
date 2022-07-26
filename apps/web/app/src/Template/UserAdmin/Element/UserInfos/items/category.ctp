<?php
//項目名
$title_name = $_page_config_item['title'] ? $_page_config_item['title'] : 'カテゴリ';
$sub_title_name = $_page_config_item['sub_title'] ? $_page_config_item['sub_title'] : '';
?>

<?php
$isCategoryEnabled = $this->Common->isCategoryEnabled($page_config);
$isCategoryEnabled_multiple = $this->Common->isCategoryEnabled($page_config, 'category_multiple');
//閲覧権限
$is_viewable = $_page_config_item && $isCategoryEnabled && $this->Common->isUserRole($_page_config_item->viewable_role);

//編集権限
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
//カテゴリー複数選択機能をonにしている場合
$is_multiple_category = $isCategoryEnabled_multiple;
//カテゴリー他階層込みのセレクト
$is_single_category = !$isCategoryEnabled_multiple;

//選択済みのカテゴリー
$category_id = $this->request->data['category_id'] ?? 0;
function get_category_name($array, $category_id) {
    foreach ($array as $k => $category) {
        if (is_array($category)) {
            $category_name = get_category_name($category, $category_id);
            if ($category_name) {
                return $category_name;
            }
        } else {
            if ($k == $category_id) {
                return $category;
            }
        }
    }
    return '';
}
$category_name = get_category_name($category_list, $category_id);
?>

<?php if ($is_viewable): ?>

<?php if ($is_editable): ?>


<?php if ($is_single_category): ?>
<tr>
  <td>
    <?= $title_name ?>
    <span class="attent">※必須</span>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?= $this->Form->input('category_id', ['type' => 'select', 'options' => $category_list, 'empty' => ['0' => '選択してください']]); ?>
  </td>
</tr>
<?php endif; ?>
<?php if ($is_multiple_category): ?>
<tr>
  <td>
    <?= $title_name ?>
    <span class="attent">※必須</span>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <div class="list-group" style="height: 200px; overflow:auto;">

      <?php foreach ($category_list as $cat_id => $cat_name): ?>
      <label class="list-group-item">
        <?= $this->Form->input(
    "info_categories.{$cat_id}",
    [
        'type' => 'checkbox',
        'value' => $cat_id,
        'checked' => in_array((int)$cat_id, $info_category_ids, false),
        'class' => 'form-check-input me-1',
        'hiddenField' => false
    ]
); ?>
        <?= $cat_name; ?>
      </label>
      <?php endforeach; ?>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php else: ?>

<?= $this->Form->hidden('category_id', ['value' => $category_id]); ?>
<tr>
  <td>
    <?= $title_name ?>
    <span class="attent">※必須</span>
    <?php if(!empty($sub_title_name)): ?>
    <div>(<?= $sub_title_name ?>)</div>
    <?php endif; ?>
  </td>
  <td>
    <?= h($category_name) ?>
  </td>
</tr>

<?php endif; ?>

<?php endif;
