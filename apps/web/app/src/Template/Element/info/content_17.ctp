<?php
$dir = $c['image_pos'] ?? '';
 ?>
<div class="c-dialogue-<?= $dir; ?>">
  <figure class="text-center image top-<?= $dir; ?>">
    <img class="fit"
      src="<?= $c['attaches']['image']['0']; ?>" alt="">
  </figure>
  <p><?= nl2br(h($c['content'])); ?></p>
</div>
<br>