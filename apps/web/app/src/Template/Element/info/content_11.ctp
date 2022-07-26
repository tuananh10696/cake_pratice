<?php
$link = $c['option_value3'] ?? '';
$caption = $c['title'] ?? '';
$dir = $c['image_pos'] ?? '';
 ?>
<div class="clearfix">
  <figure class="image top-<?= $dir; ?>">

    <?php if (!($link)): ?>
      <img class="fit"
        src="<?= $c['attaches']['image']['0']; ?>"
        alt=""
        loading="lazy"
        decoding="async"
      />
    <?php else: ?>
      <a
        href="<?= $link ?>"
        target="_blank"
        rel="noopener"
      >
        <img class="fit"
          src="<?= $c['attaches']['image']['0']; ?>"
          alt=""
          loading="lazy"
          decoding="async"
        />
      </a>
    <?php endif; ?>
    <?php if ($caption): ?>
      <figcaption><?= h($caption); ?></figcaption>
    <?php endif; ?>
  </figure>
  <p>
    <?= nl2br(h($c['content'])); ?>
  </p>
</div>
<br>