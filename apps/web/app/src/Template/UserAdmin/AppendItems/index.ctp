<div class="title_area">
  <h1>追加入力項目管理</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['controller' => 'page-configs', 'action' => 'index']); ?>">コンテンツ設定</a>
      </li>
      <li><span>入力項目一覧 </span></li>
    </ul>
  </div>
</div>

<?php
//データの位置まで走査
$count = array('total' => 0,
    'enable' => 0,
    'disable' => 0);
$count['total'] = $data_query->count();
?>

<?= $this->element('error_message'); ?>

<div class="content_inr">

  <div class="box">
    <!-- todo -->
    <h3 class="box__caption--count"><span>追加入力項目一覧[<?= $page_config->page_title;?>]</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['page_id' => $page_config->id])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>


    <div class="table_area">
      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 100px;">
          <col>
          <col style="width: 300px">
          <col style="width:130px;">
          <col style="width: 150px">
        </colgroup>

        <tr>
          <th>表示番号</th>
          <th style="text-align:left;">入力項目名</th>
          <th>データ型</th>
          <th>詳細</th>
          <th>順序の変更</th>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
$status = true;
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td title="">
            <?= $data->id?>
          </td>

          <td>
            <?= $this->Html->link($data->name, ['action' => 'edit', $data->id, '?' => ['page_id' => $page_config->id]], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <td>
            <!-- todoデータ型 -->
            <?= $value_type_list[$data->value_type]; ?>
          </td>

          <td>
            <?= $this->Html->link('編集', ['action' => 'edit', $data->id, '?' => ['page_id' => $page_config->id]], ['class' => 'btn btn-success text-white'])?>
          </td>

          <td>
            <ul class="ctrlis">
              <?php if (!$this->Paginator->hasPrev() && $key == 0): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="cttop"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'top', '?' => ['page_id' => $page_config->id]))?>
              </li>
              <li class="ctup"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'up', '?' => ['page_id' => $page_config->id]))?>
              </li>
              <?php endif; ?>

              <?php if (!$this->Paginator->hasNext() && $key == count($datas) - 1): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="ctdown"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'down', '?' => ['page_id' => $page_config->id]))?>
              </li>
              <li class="ctend"><?= $this->Html->link('bottom', array('action' => 'position', $data->id, 'bottom', '?' => ['page_id' => $page_config->id]))?>
              </li>
              <?php endif; ?>
            </ul>
          </td>
        </tr>

        <?php endforeach; ?>

      </table>

    </div>

    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['page_id' => $page_config->id])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>


  </div>
</div>
<?php $this->start('beforeBodyClose');?>
<link rel="stylesheet" href="/admin/common/css/cms.css">

<script>
  function change_category() {
    $("#fm_search").submit();

  }
  $(function() {



  })
</script>
<?php $this->end();
