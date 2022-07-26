<div class="title_area">
  <h1>コンテンツ設定</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['controller' => 'page-configs', 'action' => 'index']); ?>">コンテンツ設定</a>
      </li>
      <li><span>項目設定</span></li>
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
    <h3 class="box__caption--count"><span>項目設定一覧[<?= $page_config->page_title;?>]</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>
    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['page_id' => $page_config->id])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>


    <div class="table_area">
      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 90px;">
          <col style="width: 150px">
          <col style="width: 200px;">
          <col>
          <col style="width: 150px">
        </colgroup>

        <tr>
          <th>状態</th>
          <th style="text-align:left;">種別</th>
          <th>名前</th>
          <th>リンク</th>
          <th>順序の変更</th>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
$status = ($data->status == 'publish' ? true : false);

$preview_url = '/' . $this->Common->session_read('data.username') . "/{$data->id}?preview=on";
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td>
            <?= $this->element('status_button', ['status' => $status, 'id' => $data->id, 'enable_text' => __('有効'), 'disable_text' => __('無効')]); ?>
          </td>

          <td>
            <?= $this->Html->link($data->type, ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <td>
            <?= $this->Html->link($data->name, ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <td>
            <?= $data->link; ?>
          </td>


          <?php if ($this->Common->isUserRole('admin')): ?>
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
          <?php endif; ?>

        </tr>

        <?php endforeach; ?>

      </table>

    </div>
    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['page_id' => $page_config->id])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif; ?>

    <?= $this->element('pagination')?>

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
