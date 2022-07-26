<?php $this->start('css') ?>
<link rel="stylesheet" href="/assets/css/form.css?v=18b0096847a9388fd6858240b5148cd2">
<link rel="stylesheet" href="/assets/css/contact.css?v=fad47bc98f7f9e8f0600237957dde94b">
<?php $this->end() ?>

<main>
    <div class="cmn__lower-head lower-head">
        <h1 class="lower-head__ttl"><span class="txt-img-wrap"><img src="/assets/images/text/contact.svg?v=204e48646f2a35778e96da2232f321e1" alt="CONTACT" width="561" height="78" loading="lazy" decoding="async" /></span></h2>
    </div>
    <div class="contact">
        <div class="contact__inner cmn__lower-inner">
            <p class="contact__lead">ご意見、お問い合わせにつきましては下記にご入力の上、送信ボタンをクリックしてください。</p>
            <?= $this->Form->create($contact, ['class' => 'frm__confirm']) ?>

            <input type="hidden" value="1" name="is_confirm_success">
            <?= $this->Form->control('category', ['type' => 'hidden']) ?>

            <div class="form__row">
                <p class="form__label">お名前<span class="required">必須</span></p>
                <div class="form__body">
                    <p><?= h($data['name']) ?></p>
                    <?= $this->Form->control('name', ['type' => 'hidden']) ?>
                </div>
            </div>
            <div class="form__row">
                <p class="form__label">メールアドレス<span class="required">必須</span></p>
                <div class="form__body">
                    <p><?= h($data['email']) ?></p>
                    <?= $this->Form->control('email', ['type' => 'hidden']) ?>
                </div>
            </div>

            <?php
            if ($data['category'] == '1') {
                $cc = '当社に関するお問い合わせ';
            } else if ($data['category'] == '2') {
                $cc = '取材に関するお問い合わせ';
            } else {
                $cc = '採用に関するお問い合わせ';
            }
            ?>
            <div class="form__row">
                <p class="form__label">お問い合わせ種別<span class="required">必須</span></p>
                <div class="form__body form__body--radio">
                    <p><?= $cc ?></p>

                </div>
            </div>
            <div class="form__row">
                <p class="form__label">お問い合わせ内容<span class="required">必須</span></p>
                <div class="form__body">
                    <p><?= nl2br(h($data['detail'])) ?></p>
                    <?= $this->Form->control('detail', ['type' => 'hidden']) ?>
                </div>
            </div>
            <div class="form__privacy-wrap">
                <div class="form__privacy">
                    <p>プライバシーポリシーに同意する</p>
                </div>
            </div>
            <div class="form__submit">
                <button class="btn btn--back" type="button" onclick="window.history.back();">入力画面へ戻る<i class="glyphs-icon_arrow-r icon-arrow"></i></button>
                <button class="btn" type="submit">送信<i class="glyphs-icon_arrow-r icon-arrow"></i></button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</main>
