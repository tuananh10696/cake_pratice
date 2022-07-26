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
            <?= $this->Form->create($contact, ['class' => 'contact__form form', 'name' => 'contact']) ?>

            <?php $class = "";
            $text = "";
            $err = "";
            if (isset($error['name']) && !empty($error['name'])) {
                $class = "error";
                $text = array_values($error['name'])[0];
                $err = "error-txt";
            } ?>
            <div class="form__row">
                <label class="form__label" for="name">お名前<span class="required">必須</span></label>
                <div class="form__body">
                    <?= $this->Form->control('name', ['class' => $class, 'type' => 'text', 'label' => false, 'error' => false, 'id' => 'name']) ?>
                    <span class="<?= $err ?>"><?= $text ?></span>
                </div>
            </div>

            <?php $class = "";
            $text = "";
            $err = "";
            if (isset($error['email']) && !empty($error['email'])) {
                $class = "error";
                $text = array_values($error['email'])[0];
                $err = "error-txt";
            } ?>
            <div class="form__row">
                <label class="form__label" for="email">メールアドレス<span class="required">必須</span></label>
                <div class="form__body">
                    <?= $this->Form->control('email', ['class' => $class, 'type' => 'email', 'label' => false, 'error' => false, 'id' => 'email']) ?>
                    <span class="<?= $err ?>"><?= $text ?></span>
                </div>
            </div>

            <?php $class = "";
            $text = "";
            $err = "";
            if (isset($error['category']) && !empty($error['category'])) {
                $class = "error";
                $text = array_values($error['category'])[0];
                $err = "error-txt";
            } ?>
            <div class="form__row">
                <p class="form__label">お問い合わせ種別<span class="required">必須</span></p>

                <div class="form__body form__body--radio">
                    <input type="hidden" name="category" value="0">
                    <?php foreach ($list_cat as $i => $name) : ?>
                        <input class="<?= $class ?>" type="radio" name="category" id="category-<?= $i ?>" value="<?= $i ?>" <?= isset($data['category']) && intval($data['category']) == $i  ? 'checked="checked"' : '' ?>>
                        <label for="category-<?= $i ?>"><?= $name ?></label>
                    <?php endforeach ?>
                    <span class="<?= $err ?>"><?= $text ?></span>
                </div>
            </div>

            <?php $class = "";
            $text = "";
            $err = "";
            if (isset($error['detail']) && !empty($error['detail'])) {
                $class = "error";
                $text = array_values($error['detail'])[0];
                $err = "error-txt";
            } ?>
            <div class="form__row">
                <label class="form__label" for="detail">お問い合わせ内容<span class="required">必須</span></label>
                <div class="form__body">
                    <?= $this->Form->control('detail', ['class' => $class, 'type' => 'textarea', 'label' => false, 'error' => false, 'id' => 'detail', 'rows' => '16']) ?>
                    <span class="<?= $err ?>"><?= $text ?></span>
                </div>
            </div>

            <?php $class = "";
            $text = "";
            $err = "";
            if (isset($error['is_accept']) && !empty($error['is_accept'])) {
                $class = "error";
                $text = array_values($error['is_accept'])[0];
                $err = "error-txt";
            } ?>
            <div class="form__privacy-wrap">
                <div class="form__privacy">
                    <?= $this->Form->checkbox('is_accept', ['class' => $class, 'label' => false, 'div' => false, 'id' => 'privacy', 'error' => false, 'required' => false]); ?>
                    <label for="privacy"><a href="/privacy" target="_blank" rel="noopener">プライバシーポリシー</a>に同意する</label>
                    <span class="<?= $err ?>"><?= $text ?></span>
                </div>
            </div>
            <div class="form__submit">
                <button class="btn" type="button" onclick="document.contact.submit();">確認画面へ<i class="glyphs-icon_arrow-r icon-arrow"></i></button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</main>
