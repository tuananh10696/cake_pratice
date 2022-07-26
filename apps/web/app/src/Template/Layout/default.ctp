<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8RT81XX2HK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-8RT81XX2HK');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <title><?= $__title__ ?></title>
    <meta name="Description" content="株式会社BLOCKSMITH&amp;Co. では、Web3及びブロックチェーンゲームの開発・運営を行います。">
    <meta property="og:type" content="website">
    <meta property="og:description" content="株式会社BLOCKSMITH&amp;Co. では、Web3及びブロックチェーンゲームの開発・運営を行います。">
    <meta property="og:title" content="<?= $__title__ ?>">
    <meta property="og:url" content="https://www.blocksmithand.co.jp/">
    <meta property="og:image" content="https://www.blocksmithand.co.jp/og.png">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:site_name" content="株式会社BLOCKSMITH&amp;Co.（ブロックスミス アンドコー）">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="株式会社BLOCKSMITH&amp;Co. では、Web3及びブロックチェーンゲームの開発・運営を行います。">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/common.css?v=b9efcc2fdf3baeee8bbdb5508e361fbb">
    

    <?= $this->fetch('css') ?>
    <script>
        var bodyWidth = (document.body && document.body.clientWidth) || 0;
        document.documentElement.style.setProperty('--vw', (bodyWidth / 100) + 'px');
        document.documentElement.style.setProperty('--vh', (window.innerHeight / 100) + 'px');
    </script>
</head>

<body>
    <div class="root" id="root">
        <header class="header" id="header">
            <div class="header__inner cmn__inner">
                <div class="header__logo"><a class="link__alpha" href="/"><img src="/assets/images/common/logo.svg?v=2136b90472da2955a64c430aa9e70abb" alt="BLOCKSMITH&amp;Co." width="283" height="41" loading="lazy" decoding="async" /></a></div>
                <button class="header__menu-btn show_sp js-headerBtn" type="button" aria-label="メニューを開く"><span class="bar"></span><span class="bar"></span></button>
                <nav class="header__nav js-headerNav">
                    <div class="header__nav-inner">
                        <ul class="header__nav-list">
                            <li class="header__nav-item"><a class="header__nav-link link__text" href="/#news">
                                    <div class="txt-img-wrap"><img src="/assets/images/text/header_news.svg?v=fdec477362e3374f5e82eb3fbb135bf8" alt="News" width="186" height="68" loading="lazy" decoding="async" />
                                    </div>
                                </a></li>
                            <li class="header__nav-item"><a class="header__nav-link link__text" href="/#service">
                                    <div class="txt-img-wrap"><img src="/assets/images/text/header_service.svg?v=ad532c5e4c6bae0da9c8388e43260f48" alt="Service" width="262" height="68" loading="lazy" decoding="async" />
                                    </div>
                                </a></li>
                            <li class="header__nav-item"><a class="header__nav-link link__text" href="/#recruit">
                                    <div class="txt-img-wrap"><img src="/assets/images/text/header_recruit.svg?v=515f6dc528461799b27d85504d5465a5" alt="Recruit" width="261" height="68" loading="lazy" decoding="async" />
                                    </div>
                                </a></li>
                            <li class="header__nav-item"><a class="header__nav-link link__text" href="/#company">
                                    <div class="txt-img-wrap"><img src="/assets/images/text/header_company.svg?v=95a76ac4c907ce86500374ecbb6f3c62" alt="Company" width="331" height="68" loading="lazy" decoding="async" />
                                    </div>
                                </a></li>
                            <li class="header__nav-item header__nav-item--contact"><a class="header__nav-link link__text" href="/contact/">
                                    <div class="txt-img-wrap"><img src="/assets/images/text/header_contact.svg?v=ca4432573e701ea7c0b5048c73e4f784" alt="Contact" width="287" height="68" loading="lazy" decoding="async" />
                                    </div>
                                </a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <?= $this->fetch('content') ?>

        <footer class="footer">
            <div class="footer__inner">
                <div class="footer__go-top-top"><a class="go-to-top" href="#root"><i class="glyphs-icon_arrow-t"></i></a></div>
                <ul class="footer__nav-list">
                    <li class="footer__nav-item"><a class="footer__nav-link link__text" href="/privacy/">
                            <div class="txt-img-wrap"><img src="/assets/images/text/footer_privacy-policy.svg?v=5d5c3f0049d00dd683f682bcd9bf6bff" alt="Privacy Policy" width="580" height="76" loading="lazy" decoding="async" />
                            </div>
                        </a>
                    </li>
                    <li class="footer__nav-item"><a class="footer__nav-link link__text" href="/sitepolicy/">
                            <div class="txt-img-wrap"><img src="/assets/images/text/footer_site-policy.svg?v=585dcad001063496c9dad58df38d7810" alt="Site Policy" width="449" height="76" loading="lazy" decoding="async" />
                            </div>
                        </a>
                    </li>
                    <li class="footer__nav-item"><a class="footer__nav-link link__text" href="/contact/">
                            <div class="txt-img-wrap"><img src="/assets/images/text/footer_contact.svg?v=2f17b87f817182b03c259cbb2847203c" alt="Contact" width="328" height="76" loading="lazy" decoding="async" />
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="footer__logo"><img src="/assets/images/common/logo.svg?v=2136b90472da2955a64c430aa9e70abb" alt="BLOCKSMITH&amp;Co." width="283" height="41" loading="lazy" decoding="async" />
                </div><small class="footer__copyright"><span class="txt-img-wrap"><img src="/assets/images/text/footer_copyright.svg?v=ee7842da4b0a5a761ba88dbec183a495" alt="Copyrignt(c) BLOCKSMITH&amp;Co." width="1102" height="55" loading="lazy" decoding="async" /></span></small>
            </div>
        </footer>
    </div>
    <script src="/assets/js/vendor.js?v=63139ba14252025d84e7f983d1be6e80" defer></script>
    <script src="/assets/js/runtime.js?v=9d35ac3127f45e1ecce0308e784e82a9" defer></script>
    <script src="/assets/js/bundle.js?v=a5b2593bf389d9a557be1c4c66146e21" defer></script>
</body>

</html>