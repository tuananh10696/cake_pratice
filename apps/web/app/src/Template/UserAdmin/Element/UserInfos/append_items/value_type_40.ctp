<?php
$options = $append->mst_options ?? [];
$is_required = $append['is_required'] ?? 0;
 ?>

<tr id="append_block-<?= $append['slug'] ?>">
    <td>
        <?= h($append['name']);?>
        <?= ($is_required == 1) ? '<span class="attent">※必須</span>' : '';?>
    </td>
    <td>

        <?php if ($this->Common->isUserRole($append->editable_role)): ?>
        <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
        <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
        <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $is_required]);?>
        <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.file", ['type' => 'hidden', 'value' => '']);?>
        <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden', 'value' => '']);?>
        <?= $this->Form->input("info_append_items.{$num}.image", ['type' => 'hidden', 'value' => '']);?>

        <?php
        foreach ($options as $_ => $option):
            $name = $option['ltrl_nm'] ?? '';
            $placeholder = $option['option_value2'] ?? '';
            $maxLenght = $option['option_value3'] ?? '';
            $defaultval = $option['option_value4'] ?? '';

            $value1 = $option['option_value1'] ?? '';
            $key = $append_custom_key_list[$value1]['key'] ?? '';
            $type = $append_custom_key_list[$value1]['type'] ?? '';

            // if (count($options) != 2) {
            //     pr($num);
            //     pr($data);
            //     // pr($options);
            //     exit;
            // }
        ?>
        <dt>
            <?= h($name) ?>
        </dt>

        <?php if ($type == 'text' || $type == 'textarea'): ?>
        <dd>
            <?= $this->Form->input("info_append_items.{$num}.{$key}", ['type' => $type, 'maxlength' => $maxLenght, 'placeholder' => $placeholder, 'class' => '', 'default' => $defaultval]); ?>
        </dd>
        <?php endif; ?>

        <?php if ($type == 'image'):  ?>
        <dd>

            <?php
            $image_column = 'image';
            $saved_image = $entity['info_append_items'][$num]['attaches'][$image_column]['0'] ?? '';
             ?>

            <ul>
                <?php if ($saved_image) :?>
                <li>
                    <a href="<?= $saved_image;?>"
                        class="pop_image_single">
                        <img src="<?= $this->Url->build($saved_image)?>"
                            style="width: 300px;">
                        <?= $this->Form->input("info_append_items.{$num}.attaches.{$image_column}.0", ['type' => 'hidden']); ?>
                    </a><br>

                    <?php $old = $entity['info_append_items'][$num]['_old_' . $image_column] ?? ($entity['info_append_items'][$num][$image_column] ?? ''); ?>
                    <?= $this->Form->input("info_append_items.{$num}._old_{$image_column}", array('type' => 'hidden', 'value' => h($old))); ?>

                    <?php if (!$is_required): ?>
                    <div class="btn_area" style="width: 300px;">
                        <a href="javascript:kakunin('画像を削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'append_delete', $data['id'], $entity['info_append_items'][$num]['id'] ?? 0, 'image', $image_column, '?' => $query)) ?>')"
                            class="btn_delete">画像の削除</a>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endif;?>

                <li>
                    <?= $this->Form->input("info_append_items.{$num}.{$image_column}", array('type' => 'file', 'accept' => 'image/jpeg,image/png,image/gif', 'class' => 'attaches'));?>
                    <div class="remark">※jpeg , jpg , gif , png ファイルのみ</div>
                    <div>※ファイルサイズ５MB以内</div>
                    <?php if ($append['attention']): ?>
                    <div><?= $append['attention'] ?>
                    </div>
                    <?php endif; ?>

                    <?= $this->Form->error("{$slug}.{$append['slug']}") ?>
                </li>
            </ul>

        </dd>
        <?php endif; ?>

        <?php endforeach; ?>

        <!-- <span>※建物名も含めるとより正確に地点を絞り込めます。</span> -->
        <?= $this->Form->error("{$slug}.{$append['slug']}") ?>

        <?php else: ?>

        <?php $data = $this->request->data['info_append_items'][$num] ?? []; ?>

        <?php
        foreach ($options as $_ => $option):
            $name = $option['ltrl_nm'] ?? '';
            $type = $option['option_value1'] ?? '';
            $key = $append_custom_key_list[$type]['key'] ?? '';
        ?>
        <dt>
            <?= h($name) ?>
        </dt>
        <dd>
            <?= h($data[$key] ?? '') ?>
        </dd>
        <?php endforeach; ?>


        <?php endif; ?>

    </td>
</tr>