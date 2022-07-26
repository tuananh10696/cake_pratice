<div class="title_area">
  <h1>管理メニュー</h1>
  <div class="pankuzu">
    <ul>
      <?= $this->element('pankuzu_home'); ?>
      <li><span>管理メニュー</span></li>
    </ul>
  </div>
</div>

<?= $this->element('error_message'); ?>


<?php $menu_list = $this->Common->getAdminMenu(); ?>
<div class="content_inr">
  <?php foreach ($menu_list['main'] as $m): ?>

  <div class="box">
    <h3 style="margin-bottom:20px;"><?= $m['group_name']; ?>
    </h3>
    <?php foreach ($m['buttons'] as $row_no => $rows): ?>
    <div class="btn_area" style="text-align:left;margin-left: 20px;margin-bottom: 10px !important;">
      <?php foreach ($rows as $row): ?>
      <a href="<?= $row['link']; ?>"
        class="btn_send btn_search" style="width:130px;text-align:center;"
        target="<?= $row['target'] ?? '_self';?>"><?= $row['name']; ?></a>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <?php
    $func_under_button = $m['func_under_button'] ?? null;
    if ($func_under_button) {
        $this->Common->{$func_under_button}();
    }
    ?>
  </div>
  <?php endforeach; ?>
</div>