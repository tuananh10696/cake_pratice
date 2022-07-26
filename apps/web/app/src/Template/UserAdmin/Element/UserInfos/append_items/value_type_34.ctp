<tr id="append_block-<?= $append['slug'] ?>">
    <td>
        <?= h($append['name']);?>
        <?= ($append['is_required'] == 1) ? '<span class="attent">※必須</span>' : '';?>
    </td>
    <td>

        <?php if ($this->Common->isUserRole($append->editable_role)): ?>
        <?= $this->Form->input("info_append_items.{$num}.id", ['type' => 'hidden', 'value' => empty($entity['info_append_items'][$num]['id']) ? '' : $entity['info_append_items'][$num]['id']]);?>
        <?= $this->Form->input("info_append_items.{$num}.append_item_id", ['type' => 'hidden', 'value' => $append['id']]);?>
        <?= $this->Form->input("info_append_items.{$num}.is_required", ['type' => 'hidden', 'value' => $append['is_required']]);?>
        <?= $this->Form->input("info_append_items.{$num}.value_date", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_datetime", ['type' => 'hidden', 'value' => '0000-00-00']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_time", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_int", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.value_decimal", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.file", ['type' => 'hidden', 'value' => '']);?>
        <?= $this->Form->input("info_append_items.{$num}.file_size", ['type' => 'hidden', 'value' => '0']);?>
        <?= $this->Form->input("info_append_items.{$num}.file_extension", ['type' => 'hidden', 'value' => '']);?>
        <?= $this->Form->input("info_append_items.{$num}.image", ['type' => 'hidden', 'value' => '']);?>

        <dt>郵便番号</dt>
        <dd><?= $this->Form->input("info_append_items.{$num}.value_text", ['type' => 'text', 'maxlength' => '8', 'placeholder' => '000-0000', 'class' => 'zip']); ?>
        </dd>

        <dt>住所</dt>
        <dd><?= $this->Form->input("info_append_items.{$num}.value_textarea", ['type' => 'text', 'maxlength' => '200', 'placeholder' => '〇〇県〇〇市〇〇町〇〇-〇〇', 'class' => 'address']); ?>
        </dd>

        <dt>Googleマップ用の住所(デフォルトは校舎名のみ)</dt>
        <dd><?= $this->Form->input("info_append_items.{$num}.value_textarea2", ['type' => 'text', 'maxlength' => '200', 'placeholder' => '〇〇県〇〇市〇〇町〇〇-〇〇 〇〇建物', 'class' => 'map_address']); ?>
        </dd>
        <span>※建物名も含めるとより正確な地点を絞り込めます。</span>
        <br>


        <?php
        $address = $entity->getAppend('school_address');
        $address = $address['visit_google_map_url'] ?? '';
        ?>
        <?php if (false): ?>
        <dt><a target="_target" href="<?= $address ?>"
                style="color: #007bff;" class="confirm_googlemap">Googleマップの表示を確認する</a></dt>
        <span>※一旦保存していただくことで入力した情報を確認することができます。</span>
        <?php endif; ?>

        <?= $this->Form->error("{$slug}.{$append['slug']}") ?>

        <?php else: ?>

        <?php $data = $this->request->data['info_append_items'][$num] ?? []; ?>

        <dt>郵便番号</dt>
        <dd> <?= h($data['value_text'] ?? '') ?>
        </dd>

        <dt>住所</dt>
        <dd> <?= h($data['value_textarea'] ?? '') ?>
        </dd>

        <dt>Googleマップの共有リンク</dt>
        <dd> <?= h($data['value_text2'] ?? '') ?>
        </dd>

        <?php endif; ?>

    </td>
</tr>