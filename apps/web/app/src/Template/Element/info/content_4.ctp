
<div class="c-file_btn">
<?php if ($c['file_extension'] == 'pdf'): ?>

<a class="c-link c-link-pdf"
    href="<?= $c['attaches']['file']['download'];?>"
    target="_blank"><span><?= h($c['file_name']); ?></span></a>

<?php elseif ($c['file_extension'] == 'xls' || $c['file_extension'] == 'xlsx'): ?>

<a class="c-link c-link-exl"
    href="<?= $c['attaches']['file']['download'];?>"
    target="_blank"><span><?= h($c['file_name']); ?></span></a>

<?php elseif ($c['file_extension'] == 'doc' || $c['file_extension'] == 'docx'): ?>

<a class="c-link c-link-word"
    href="<?= $c['attaches']['file']['download'];?>"
    target="_blank"><span><?= h($c['file_name']); ?></span></a>

<?php else: ?>

<a class="c-link c-link-<?= $c['file_extension'] ?>"
    href="<?= $c['attaches']['file']['download'];?>"
    target="_blank"><span><?= h($c['file_name']); ?></span></a>

<?php endif; ?>
</div>
<br>