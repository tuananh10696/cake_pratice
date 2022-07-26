<div id="side">
  <?php if (false): ?>
  <!-- <div style="text-align: center;">
        <?= $this->Form->input('site', ['type' => 'select',
            'options' => $user_site_list,
            'style' => 'width:100%;background-color:#FFF;color:#000;',
            'id' => 'selectSite',
            'value' => $current_site_id]); ?>
</div> -->
<?php endif; ?>


<?php $menu_list = $this->Common->getAdminMenu(); ?>
<nav style="height: 100%; overflow: auto;">
  <?php foreach ($menu_list['side'] as $m): ?>
  <h4 class="text-center bb-1"><?= $m['group_name'];?>
  </h4>
  <ul class="menu scrollbar">

    <?php
      foreach ($m['buttons'] as $row_no => $rows):
        $subMenu = $rows['subMenu'] ?? [];
        if ($subMenu):
      ?>
    <li>
      <span class="parent_link"><?= $rows['name']; ?></span>
      <ul class="submenu">
        <?php foreach ($subMenu as $sub): ?>
        <li><a href="<?= $sub['link']; ?>"
            target="<?= $sub['target'] ?? '_self'; ?>"><?= $sub['name']; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php else: ?>
    <li>
    <li><a href="<?= $rows['link']; ?>"
        target="<?= $rows['target'] ?? '_self'; ?>"><?= $rows['name']; ?></a></li>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>

  </ul>
  <?php endforeach; ?>
</nav>
</div>