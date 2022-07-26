<div class="box-border-files">
    <?php if (!empty($c['sub_contents'])): ?>
    <?php foreach ($c['sub_contents'] as $sub): ?>
    <?= $this->element("info/content_{$sub['block_type']}", ['c' => $sub]); ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>