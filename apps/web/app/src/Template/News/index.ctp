<?php $this->start('css') ?>
<link rel="stylesheet" href="/assets/css/news.css?v=449417f058692fb9f4a9c33f32a39cf8">
<?php $this->end() ?>

<main>
	<div class="cmn__lower-head lower-head">
		<h1 class="lower-head__ttl"><span class="txt-img-wrap"><img src="/assets/images/text/news.svg?v=438cf9e9670e84ced541ac0d52fc8f46" alt="NEWS" width="265" height="67" loading="lazy" decoding="async" /></span></h1>
	</div>
	<div class="news-list ">
		<div class="news-list__inner cmn__lower-inner">
			<div class="news-list__container">
				<ul class="news-list__list">
					<?php foreach ($infos as $data) : ?>
						
						<li class="cmn__news-item intersect-elem" data-effect="fadeup"><a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'detail',$data->id]) ?>">
								<time class="date" datetime="2022-07-06"><?= $data->start_datetime->format('Y m/d') ?></time>
								<p class="label"><?= h($data->title) ?></p>
							</a></li>
					<?php endforeach ?>
				</ul>
				<div class="news-list__pager">
					<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()) : ?>
						<div class="pager">
							<?php if ($this->Paginator->hasPrev()) : ?><?= $this->Paginator->prev('') ?><?php endif; ?>
							<?= $this->Paginator->numbers(); ?>
							<?php if ($this->Paginator->hasNext()) : ?><?= $this->Paginator->next('…') ?><?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</main>