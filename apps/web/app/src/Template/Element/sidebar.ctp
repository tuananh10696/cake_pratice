<?php
  $counter = 0;  //ランキング用カウンタ
?>
<div class="wrap">
  <div class="sidebar__top show_pc">
    <a class="sidebar-logo" href="/">
      <img src="/assets/images/common/logo.svg" alt="TOCHINO-トチノ-"/>
    </a>
    <p class="des">とちぎ救農ポータルサイトtochino（トチノ）は実際に栃木で農業を始めるためのポイントや役立つ情報をお伝えします。</p>
    <a class="image-book" href="/guide/">
      <img src="/assets/images/common/image_01.png" alt=""/>
      <span class="txt_01">初めての方は <br/>まずはこちらから!!</span>
      <span class="txt_02">農家の始め方<br/>ガイドブック</span>
    </a>
  </div>
  <div class="sidebar__ranking blocks"> 
    <h3>ランキング</h3>
    <div class="rank-list"> 
      <?php foreach($ranking as $info): ?>
        <?php
          $info->setPageConfig('article');  // page_configの設定
          $rank_cls = ++$counter > 3 ? 'rank_default': 'rank_0'.$counter;
        ?>
        <a class="rank-list__item <?= $rank_cls ?>" href="<?= $info->detailUrl() ?>"><span><?= $counter ?></span><?= h($info->title) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="sidebar__information blocks"> 
    <h3>最新情報</h3>
    <div class="info">
      <?php foreach($new_infos as $info): ?>
        <a class="info-link" href="<?= $info->detailUrl() ?>"><?= $info->title ?></a>
      <?php endforeach; ?>
    </div>
    <a class="see-all" href="/information/">一覧はこちら</a>
  </div>
  <div class="sidebar__movie blocks">
    <h3>動画</h3>
    <div class="movies">
      <div class="movies-item">
        <div class="player">
          <iframe src="https://www.youtube.com/embed/EngW7tLk6R8"></iframe>
        </div><a class="movies-box" href="https://www.youtube.com/embed/EngW7tLk6R8" target="_blank"><span class="ttl">農家になるまで</span><span>個人の力を試してみたいと農業にチャレンジ</span></a>
      </div>
      <div class="movies-item">
        <div class="player">
          <iframe src="https://www.youtube.com/embed/a3ICNMQW7Ok"></iframe>
        </div><a class="movies-box" href="https://www.youtube.com/embed/a3ICNMQW7Ok" target="_blank"><span class="ttl">農家になると言われて</span><span>就農の第一歩は農業体験から！<br class="show_pc"/>私達にぴったりな農業のかたち。</span></a>
      </div>
    </div>
  </div>
  <div class="sidebar__links">
    <a class="cta link__alpha" href="" target="_blank">
      <img class="fit" src="/assets/images/common/cta_01.png" alt="ベリーマッチとちぎ"/>
    </a>
    <a class="cta link__alpha" href="" target="_blank">
      <img class="fit" src="/assets/images/common/cta_02.png" alt="農地の窓口"/>
    </a>
    <a class="cta link__alpha" href="" target="_blank">
      <img class="fit" src="/assets/images/common/cta_03.png" alt="空き家バンクガイド"/>
    </a>
    <a class="cta link__alpha no-border" href="" target="_blank">
      <img class="fit" src="/assets/images/common/cta_04.png" alt="移住相談センター"/>
    </a>
  </div>
</div>