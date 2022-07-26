<div class="title_area">
  <h1>スキルのカテゴリ</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['controller' => 'skills']); ?>">スキル</a>
      </li>
      <li><span>カテゴリ</span></li>
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
    <h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>

    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>

    <?= $this->element('pagination')?>

    <div class="table_area">

      <div style="display:flex; justify-content: space-between;">
        <div>

        </div>
        <div class="btn_area" style="margin-top:10px;justify-content:right;margin-bottom:10px !important;">
          <a href="<?= $this->Url->build(['controller' => 'skills', 'action' => 'index']); ?>"
            class="btn btn-primary rounded-pill mr-1"><i class="fas fa-undo-alt"></i> <?= __('スキル一覧へ'); ?></a>
        </div>
      </div>

      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 75px;">
          <col style="width: 100px;">
          <col>
          <col style="width: 150px;">

        </colgroup>

        <tr>
          <th>掲載</th>
          <th>ID</th>
          <th style="text-align:left;">カテゴリ名</th>
          <th>並び順</th>
        </tr>

        <?php
foreach ($data_query->toArray() as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;
$scripturl = '';
if ($data['status'] === 'publish') {
    $count['enable']++;
    $status = true;
} else {
    $count['disable']++;
    $status = false;
}

$preview_url = '/' . $this->Common->session_read('data.username') . "/{$data->id}?preview=on";
?>
        <a name="m_<?= $id ?>"></a>
        <tr
          class="<?= $status ? 'visible' : 'unvisible' ?>"
          id="content-<?= $data->id ?>">

          <td>
            <?= $this->element('status_button', ['status' => $status, 'id' => $data->id, 'enable_text' => __('有効'), 'disable_text' => __('無効')]); ?>
          </td>

          <td title="">
            <?= $data->id?>
          </td>

          <td>
            <?= $this->Html->link($data->name, ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-light w-100 text-left'])?>
          </td>

          <td>
            <ul class="ctrlis">
              <?php if (!$this->Paginator->hasPrev() && $key == 0): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="cttop"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'top'))?>
              </li>
              <li class="ctup"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'up'))?>
              </li>
              <?php endif; ?>

              <?php if (!$this->Paginator->hasNext() && $key == count($datas) - 1): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="ctdown"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'down'))?>
              </li>
              <li class="ctend"><?= $this->Html->link('bottom', array('action' => 'position', $data->id, 'bottom'))?>
              </li>
              <?php endif; ?>
            </ul>
          </td>


        </tr>

        <?php endforeach; ?>

      </table>

    </div>

    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => $query)); ?>"
        class="btn_confirm btn_post">新規登録</a></div>

    <?= $this->element('pagination')?>

  </div>
</div>
<?php $this->start('beforeBodyClose');?>
<link rel="stylesheet" href="/admin/common/css/cms.css">

<?php $this->end();
