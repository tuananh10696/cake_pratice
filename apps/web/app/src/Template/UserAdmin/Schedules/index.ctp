<div class="title_area">
	<h1><?= h($data_info->title) ?>・日程</h1>
	<div class="pankuzu">
		<ul>
			<?= $this->element('pankuzu_home'); ?>
			<li><?= $this->Html->link('オープンキャンパス・個別見学管理', ['prefix' => 'user_admin', 'controller' => 'infos', 'action' => 'index', '?' => ['page_slug' => $slug]]) ?></li>
			<li><?= $this->Html->link($data_info->title, ['prefix' => 'user_admin', 'controller' => 'infos', 'action' => 'edit', $data_info->id, '?' => ['sch_page_id' => $data_info->page_config->id]]) ?></li>
			<li><span>日程</span></li>
		</ul>
	</div>
</div>

<div class="content_inr">
	<div class="box">
		<h3 class="box__caption--count"><span>登録一覧</span><span class="count"><?= count($data_info->schedules) ?>件の登録</span></h3>
		<div class="btn_area" style="margin-top:10px;"><a href="<?= $this->Url->build(array('action' => 'edit', '?' => ['info_id' => $data_info->id])); ?>" class="btn_confirm btn_post">新規登録</a></div>
		<div class="table_area">
			<table class="table__list" style="table-layout: fixed;">
				<colgroup>
					<col style="width: 135px;">
					<col>
				</colgroup>
				<tr>
					<th>表示番号</th>
					<th>日程</th>
				</tr>
				<?php $days = array('日', '月', '火', '水', '木', '金', '土'); ?>
				<?php
				$i = 1;
				foreach ($data_info->schedules as $schedule) : ?>
					<?php $txt = $schedule->start->format(__('Y年n月j（{0}）/ H:i　〜　', $days[$schedule->start->format('w')])) ?>
					<?php if (!is_null($schedule->end)) {
						$txt .= $schedule->end->format('Ymd') === $schedule->start->format('Ymd') ? $schedule->end->format('H:i') : $schedule->end->format(__('Y年n月j（{0}）/ H:i', $days[$schedule->end->format('w')]));
					} ?>
					<tr>
						<td><?= $i ?></td>
						<td>
							<?= $this->Html->link($txt, ['action' => 'edit', $schedule->id, '?' => ['info_id' => $data_info->id]], ['class' => 'btn btn-light w-100 text-left']) ?>
						</td>
					</tr>
				<?php
					$i++;
				endforeach; ?>
			</table>
		</div>
		<div class="btn_area" style="margin-top:10px;">
			<a href="<?= $this->Url->build(array('action' => 'edit', '?' => ['info_id' => $data_info->id])); ?>" class="btn_confirm btn_post">新規登録</a>
		</div>
	</div>
</div>

<?php $this->start('beforeBodyClose'); ?>
<link rel="stylesheet" href="/admin/common/css/cms.css">
<?php $this->end();
