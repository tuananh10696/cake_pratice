<div class="title_area">
  <h1>「<?= $page_config->page_title; ?>」のトップ表示リスト</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><a
          href="<?= $this->Url->build(['controller' => 'infos', 'action' => 'index', '?' => ['page_slug' => $page_config->slug]]); ?>"><?= $page_config->page_title; ?></a></li>
      <li><span>トップ表示</span></li>
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
    <h3 class="box__caption--count"><span>一覧</span><span class="count"><?php echo $count['total']; ?>件の登録</span></h3>

    <div class="table_area">

      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <col style="width: 75px;">
          <col>
          <col style="width: 150px;">

        </colgroup>

        <tr>
          <th>ID</th>
          <th style="text-align:left;">タイトル</th>
          <th>並び順</th>
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
            <?= $data->info_id?>
          </td>

          <td>
            <?= $this->Html->link($data->info->title, ['controller' => 'infos', 'action' => 'edit', $data->info_id, '?' => ['page_slug' => $query['slug']]], ['class' => 'btn btn-light w-100 text-left'])?>
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
