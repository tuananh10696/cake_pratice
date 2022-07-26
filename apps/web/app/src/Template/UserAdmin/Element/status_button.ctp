<?php if (!isset($enable_text)) {
    $enable_text = __('有効');
} ?>
<?php if (!isset($disable_text)) {
    $disable_text = __('無効');
} ?>
<?php if (!isset($action)) {
    $action = 'enable';
} ?>
<?php if (!isset($class)) {
    $class = '';
} ?>
<a role="button" type="button"
    class="btn w-100 text-light btn-sm btn-<?= ($status ? 'success' : 'secondary'); ?> text-decoration-none <?= $class;?>"
    href="<?= $this->Url->build(['action' => $action, $id, '?' => ($query ?? [])]); ?>">
    <?= ($status ? $enable_text : $disable_text); ?>
</a>