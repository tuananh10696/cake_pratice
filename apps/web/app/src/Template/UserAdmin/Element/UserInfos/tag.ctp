<li id="tag_id_<?= $num; ?>">
    <span class="tag_name"><?= $tag; ?></span>
    <span><a href="javascript:void(0);" class="delete_tag"
            data-id="<?= $num; ?>">×</a></span>
    <?= $this->Form->input("tags.{$num}.tag", ['type' => 'hidden', 'value' => $tag]); ?>
</li>