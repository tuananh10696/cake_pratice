<?php $this->start('css') ?>
<link rel="stylesheet" href="/assets/css/news.css?v=449417f058692fb9f4a9c33f32a39cf8">
<link rel="stylesheet" href="/assets/css/ckeditor.css">
<?php $this->end() ?>
<style>
	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}
</style>

<main>
	<div class="news-detail">
		<div class="news-detail__inner cmn__lower-inner">
			<div class="news-detail__content">
				<div class="content__head">
					<time class="date" datetime="2022-03-24"><?= @$info->start_datetime->format('Y m/d') ?></time>
					<h1 class="label"><?= h($info->title) ?></h1>
				</div>
				<div class="content__body">
					<div class="wysiwyg">
						<?php foreach ($contents as $content) : ?>
							<?= $this->element('info/content_' . $content['block_type'], ['c' => $content]); ?>
						<?php endforeach; ?>
					</div>
					<div class="content__detail-pager">
						<div class="detail-pager">
							<?php
							$prev_id = 'detail/' . $info->id;
							$next_id = 'detail/' . $info->id;
							for ($i = 0; $i < count($listId); $i++) {
								if ($listId[$i] == $info->id) {
									$prev = $i - 1;
									$prev_id = isset($listId[$prev]) ? $listId[$prev] : '';

									$next = $i + 1;
									$next_id = isset($listId[$next]) ? $listId[$next] : '';
									break;
								}
							}
							$div_style1 = $prev_id == null ? $div_style = 'display:none;' : '';
							$div_style2 = $next_id == null ? $div_style = 'display:none;' : '';
							$preview = $this->request->getQuery('preview');
							if ($preview == 'on') {
								$div_style1 = 'display:none;';
								$div_style2 = 'display:none;';
							}
							?>
							<div style="<?= $div_style1 ?>"><a class="btn prev-btn" href="/news/<?= $prev_id ?>" aria-label="ひとつ前の記事を見る"><i class="glyphs-icon_arrow-l icon"></i></a></div>
							<a class="list-link" href="/news/"><i class="glyphs-icon_list icon"></i>NEWS一覧</a>
							<div style="<?= $div_style2 ?>"><a class="btn next-btn" href="/news/<?= $next_id ?>" aria-label="次の記事を見る"><i class="glyphs-icon_arrow-r icon"></i></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>