<?php if (!isset($enable_text)) {
    $enable_text = '有効';
} ?>
<?php if (!isset($disable_text)) {
    $disable_text = '無効';
} ?>
<?php if (!isset($btn_class)) {
    $btn_class = 'success';
} ?>
<a role="button" type="button"
    class="btn w-100 text-light btn-sm btn-<?= ($status ? $btn_class : 'danger'); ?> text-decoration-none"
    style="cursor: not-allowed !important;" href="javascript:void(0);">
    <?= ($status ? $enable_text : $disable_text); ?>
</a>