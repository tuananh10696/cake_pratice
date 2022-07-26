<?php use App\Model\Entity\MstList;

?>
<div class="title_area">
  <h1>リスト一覧</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><span>一覧</span></li>
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
    <h3>検索条件</h3>
    <div class="table_area form_area">
      <?= $this->Form->create(null, array('type' => 'get', 'id' => 'fm_search', 'url' => array('action' => 'index'))); ?>
      <table class="vertical_table ">
        <tr>
          <td>区分</td>
          <td>
            <?php if ($this->Common->isUserRole('develop')): ?>
            <?= $this->Form->select('list_code', $sys_list, ['onChange' => 'change_category();', 'value' => $list_code]) ?>
            <?php else: ?>
            <?= $this->Form->input('list_code', ['type' => 'hidden', 'value' => MstList::LIST_FOR_USER]); ?>
            <?= $sys_list[MstList::LIST_FOR_USER]; ?>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td>リスト</td>
          <td>
            <?= $this->Form->input('target_id', ['type' => 'select',
                'options' => $target_list,
                'onChange' => 'change_category();',
                'value' => $target_id,
                'empty' => '選択してください'
            ]); ?>
          </td>
        </tr>
      </table>
      <?= $this->Form->end(); ?>
    </div>
  </div>

  <?php if ($this->Common->isUserRole('develop') || $target_id): ?>
  <div class="box">
    <h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?php echo h($numrows); ?>件の登録</span></h3>

    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['target_id' => $target_id, 'list_code' => $list_code])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif;?>


    <div class="table_area">
      <table class="table__list" style="table-layout: fixed;">
        <colgroup>
          <?php if ($this->Common->isUserRole('develop')): ?>
          <col style="width: 100px;">
          <col style="width: 150px;">
          <?php endif; ?>
          <col style="width: 100px;">
          <col>
          <col style="width: 200px;">
          <col style="width: 80px;">
          <col style="width: 150px;">

        </colgroup>

        <tr>
          <?php if ($this->Common->isUserRole('develop')): ?>
          <th>項目No.</th>
          <th>リスト名</th>
          <?php endif; ?>
          <th>値</th>
          <th style="text-align:left;">項目</th>
          <th style="">予備キー</th>
          <th>詳細</th>
          <th>順序の変更</th>
        </tr>

        <?php
foreach ($datas as $key => $data):
$no = sprintf('%02d', $data->id);
$id = $data->id;

?>
        <a name="m_<?= $id ?>"></a>
        <tr>
          <?php if ($this->Common->isUserRole('develop')): ?>
          <td>
            <?= h($no) ?>
          </td>

          <td>
            <?= h($data['list_name']) ?>
          </td>
          <?php endif; ?>

          <td>
            <?= h($data['ltrl_val']) ?>
          </td>

          <td>
            <?= h($data['ltrl_nm']) ?>
          </td>

          <td>
            <?= h($data['ltrl_slug']); ?>
          </td>

          <td>
            <?= $this->Html->link('編集', ['action' => 'edit', $data->id, '?' => $query], ['class' => 'btn btn-success text-white'])?>
          </td>

          <td>
            <ul class="ctrlis">
              <?php if (!$this->Paginator->hasPrev() && $key == 0): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="cttop"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'top', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <li class="ctup"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'up', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <?php endif; ?>

              <?php if (!$this->Paginator->hasNext() && $key == count($datas) - 1): ?>
              <li class="non">&nbsp;</li>
              <li class="non">&nbsp;</li>
              <?php else: ?>
              <li class="ctdown"><?= $this->Html->link('top', array('action' => 'position', $data->id, 'down', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <li class="ctend"><?= $this->Html->link('bottom', array('action' => 'position', $data->id, 'bottom', '?' => $query), ['class' => 'scroll_pos'])?>
              </li>
              <?php endif; ?>
            </ul>
          </td>



        </tr>

        <?php endforeach; ?>


      </table>

    </div>
    <?php if ($this->Common->isUserRole('admin')): ?>
    <div class="btn_area" style="margin-top:10px;"><a
        href="<?= $this->Url->build(array('action' => 'edit', '?' => ['target_id' => $target_id, 'list_code' => $list_code])); ?>"
        class="btn_confirm btn_post">新規登録</a></div>
    <?php endif;?>
    <?= $this->element('pagination')?>

  </div>
  <?php endif; ?>
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
