<?php $this->start('css') ?>
<link rel="stylesheet" href="/assets/css/index.css?v=e836683d8cc680f41edfa7ee8c29344c">
<?php $this->end() ?>

<main>
	<h1 class="top__h1">株式会社BLCKSMITH&amp;Co.</h1>
	<div class="mv intersect-elem">
		<div class="mv__visual">
			<picture>
				<source media="(min-width: 769px)" srcset="/assets/images/top/mv_bg_pc.jpg" />
				<source media="(max-width: 768px)" srcset="/assets/images/top/mv_bg_sp.jpg?v=6c9568a9ea84a26613448d02d6fce75c" /><img src="/assets/images/top/mv_bg_sp.jpg?v=6c9568a9ea84a26613448d02d6fce75c" srcset="/assets/images/top/mv_bg_sp.jpg?v=6c9568a9ea84a26613448d02d6fce75c" alt="" />
			</picture>
		</div>
		<div class="mv__inner">
			<p class="mv__catch"><span class="en">
					<picture>
						<source media="(min-width: 769px)" srcset="/assets/images/text/mv_catch_pc.svg" />
						<source media="(max-width: 768px)" srcset="/assets/images/text/mv_catch_sp.svg?v=02f1ee9af0f7f725ced0e47e63866c80" /><img src="/assets/images/text/mv_catch_sp.svg?v=02f1ee9af0f7f725ced0e47e63866c80" srcset="/assets/images/text/mv_catch_sp.svg?v=02f1ee9af0f7f725ced0e47e63866c80" alt="Creating an exciting future" />
					</picture>
				</span><span class="ja">ワクワクする未来を創る</span></p>
		</div>
	</div>
	<section class="news" id="news">
		<div class="news__inner cmn__inner">
			<h2 class="cmn__ttl news__ttl intersect-elem" data-effect="fadeup"><span class="txt-img-wrap"><img src="/assets/images/text/news.svg?v=438cf9e9670e84ced541ac0d52fc8f46" alt="news" width="265" height="67" loading="lazy" decoding="async" /></span></h2>
			<ul class="news__list">
				<?php foreach ($info_model as $data) :?>
				<li class="cmn__news-item intersect-elem cmn__news-item--clamp" data-effect="fadeup"><a href="news/detail/<?= $data->id ?>"  rel="noopener">
						<time class="date" datetime="2022-07-06"><?= $data->start_datetime->format('Y m/d') ?></time>
						<p class="label"><?= h($data->title) ?></p>
					</a></li>
				<?php endforeach; ?>
				
			</ul>
			<div class="news__link-area intersect-elem" data-effect="fadeup"><a class="news__link cmn__link" href="/news/"><span class="icon-arrow"><i class="glyphs-icon_arrow-r"></i></span>一覧を見る</a></div>
		</div>
	</section>
	<div class="company-name js-movingText intersect-elem" data-effect="fadeup">
		<div class="txt-img-wrap"><img src="/assets/images/top/logo-text-only.svg?v=b7dba21564a1ad021b28e02aa76d7ec2" alt="" width="223" height="20" loading="lazy" decoding="async" />
		</div>
	</div>
	<section class="service" id="service">
		<div class="service__inner cmn__inner">
			<h2 class="cmn__ttl service__ttl intersect-elem" data-effect="fadeup"><span class="txt-img-wrap"><img src="/assets/images/text/service.svg?v=fa902297f7cd6b500c750b7e2b41a012" alt="service" width="370" height="67" loading="lazy" decoding="async" /></span></h2>
			<div class="service__row">
				<div class="service__img-wrap intersect-elem" data-effect="fadeup" data-delay="0.4"><img src="/assets/images/top/service_01.png?v=7e0116e140129b82ef7663ed63f35232" alt="" width="960" height="540" loading="lazy" decoding="async" />
				</div>
				<div class="service__txt-wrap">
					<p class="service__catch cmn__catch intersect-elem" data-effect="fadeup">Quizo.ooo</p>
					<p class="service__txt intersect-elem" data-effect="fadeup">『Quizo.ooo』は、Quizを解くことにより知識を得ながら報酬を獲得できる、学んで稼げるLearn to Earnサービスです。<br><br>＊リリースまでお楽しみにお待ちください。最新情報は公式ツイッターにて配信して参りますので、ご興味のある方はフォローをお願いします。<a class="link__alpha" href="https://twitter.com/Quizo_ooo" target="_blank" rel="noopener"><i class="glyphs-icon_tw icon-twitter"></i></a></p>
				</div>
			</div>
		</div>
	</section>
	<section class="recruit" id="recruit">
		<div class="recruit__inner cmn__inner">
			<h2 class="cmn__ttl recruit__ttl intersect-elem" data-effect="fadeup"><span class="txt-img-wrap"><img src="/assets/images/text/recruit.svg?v=ff01e45780f56996aa1247f03b9b007d" alt="recruit" width="390" height="67" loading="lazy" decoding="async" /></span></h2>
			<p class="recruit__catch cmn__catch intersect-elem" data-effect="fadeup">BlockSmith＆Co.では、私たちと新しいGameFiを<br class="show_pc">開拓していく仲間を募集してます。</p>
			<div class="recruit__link-area intersect-elem" data-effect="fadeup"><a class="recruit__link cmn__link" href="https://recruit.jobcan.jp/blocksmith/" target="_blank" rel="noopener"><span class="icon-arrow"><i class="glyphs-icon_arrow-r"></i></span>キャリア採用/募集要項・エントリー</a></div>
		</div>
	</section>
	<section class="company" id="company">
		<div class="company__inner cmn__inner">
			<h2 class="cmn__ttl company__ttl intersect-elem" data-effect="fadeup"><span class="txt-img-wrap"><img src="/assets/images/text/company.svg?v=ded4b1bcb179b0f4ea1a41810d37dd4c" alt="company" width="480" height="67" loading="lazy" decoding="async" /></span></h2>
			<dl class="company__details">
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">社名</dt>
					<dd class="company__data">株式会社BLOCKSMITH&Co. （ブロックスミスアンドコー）</dd>
				</div>
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">代表者</dt>
					<dd class="company__data">代表取締役社長CEO 真田哲弥</dd>
				</div>
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">設立</dt>
					<dd class="company__data">2022年4月1日</dd>
				</div>
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">資本金</dt>
					<dd class="company__data">1000万円</dd>
				</div>
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">本社所在地</dt>
					<dd class="company__data">〒106-6122 東京都港区六本木6-10-1六本木ヒルズ森タワー</dd>
				</div>
				<div class="company__row intersect-elem" data-effect="fadeup">
					<dt class="company__label">事業内容</dt>
					<dd class="company__data">ブロックチェーン技術または暗号資産、NFTを活用したゲームなどのサービスの開発および配信</dd>
				</div>
			</dl>
		</div>
	</section>
</main>
