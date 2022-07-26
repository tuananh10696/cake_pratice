<?php
$is_editable = $this->Common->isUserRole($_page_config_item->editable_role);
if ($is_editable):
$entity->setPageConfig('article');
?>
<tr>
  <td rowspan="2">ハッシュタグ</td>
  <td>
    <?= $this->Form->input('add_tag', ['type' => 'text', 'style' => 'width: 200px;', 'maxlength' => '40', 'id' => 'idAddTag', 'placeholder' => 'タグを入力']); ?>
    <span class="btn_area" style="display: inline;">
      <a href="#" class="btn_confirm small_menu_btn btn_orange" id="btnAddTag">追加</a>
      <a href="#" class="btn_confirm small_menu_btn" id="btnListTag">タグリスト</a>
    </span>
    <div>※タグを入力して追加ボタンで追加またはタグリストから選択する事もできます。</div>
    <div>※重複した場合は１つにまとめられます。</div>
  </td>
</tr>
<tr>
  <td style="display:none;"></td>
  <td>
    <ul id="tagArea">
      <?php 
        foreach (($entity->article_tags ?? []) as $k => $tag): 
      ?>
      <?= $this->element('UserInfos/tag', ['num' => $k, 'tag' => $tag->tag]); ?>
      <?php endforeach; ?>

    </ul>
  </td>
</tr>
<?php else: ?>

<tr>
  <td rowspan="2">ハッシュタグ</td>
  <td>

    <?php foreach (($entity->article_tags ?? []) as $k => $tag): ?>
    <?php $value = $tag['tag'] ?? ''; ?>
    <?= $this->Form->hidden("tags.{$k}.tag", ['value' => $value]); ?>
    <?= h($value) . ', ' ?>
    <?php endforeach; ?>

  </td>
</tr>
<tr>

  <?php endif;
