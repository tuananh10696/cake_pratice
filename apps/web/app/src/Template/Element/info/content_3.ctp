<?php
$link = $c['content'] ?? '';
$caption = $c['title'] ?? '';
$target_type = $c['option_value'] ?? '';
 ?>
<figure class="image image--fluid">
        <?php if ($link): ?>
        <a 
                href="<?= $link ?>"
                target="<?= $target_type ?>">
                <img
                        src="<?= $c['attaches']['image']['0'];?>"
                        alt=""
                        loading="lazy"
                        decoding="async"
                />
        </a>
        <?php else: ?>
        <img
                src="<?= $c['attaches']['image']['0'];?>"
                alt=""
                loading="lazy"
                decoding="async"
        />
        <?php endif; ?>
        <?php if ($caption): ?>
        <figcaption><?= h($caption); ?></figcaption>
        <?php endif; ?>
</figure>
<br>