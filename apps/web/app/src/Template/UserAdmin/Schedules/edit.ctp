<?php $this->start('beforeHeaderClose'); ?>

<?php $this->end(); ?>

<div class="title_area">
	<h1><?= h($data_info->title) ?>・日程</h1>
	<div class="pankuzu">
		<ul>
			<?= $this->element('pankuzu_home'); ?>
			<li><?= $this->Html->link('オープンキャンパス・個別見学管理', ['prefix' => 'user_admin', 'controller' => 'infos', 'action' => 'index', '?' => ['page_slug' => $slug]]) ?></li>
			<li><?= $this->Html->link($data_info->title, ['prefix' => 'user_admin', 'controller' => 'infos', 'action' => 'edit', $data_info->id, '?' => ['sch_page_id' => $data_info->page_config->id]]) ?></li>
			<li><?= $this->Html->link('一覧', ['action' => 'index', '?' => ['info_id' => $data_info->id]]) ?></li>
			<li><span><?= (@$entity->id > 0) ? '編集' : '新規登録'; ?></span></li>
		</ul>
	</div>
</div>

<div class="content_inr">
	<div class="box">
		<h3><?= (@$entity->id > 0) ? '編集' : '新規登録'; ?></h3>
		<div class="table_area form_area">
			<?= $this->Form->create($entity); ?>
			<?= $this->Form->input('id', ['type' => 'hidden', 'value' => $entity->id]) ?>
			<?= $this->Form->input('info_id', ['type' => 'hidden', 'value' => $data_info->id]) ?>
			<table class="vertical_table table__meta">
				<tr>
					<td>開始日時<span class="attent">※必須</span></td>
					<td>
						<?= $this->Form->input('start',  ['type' => 'text', 'value' => $entity->start ? (new DateTime($entity->start))->format('Y-m-d H:i') : date('Y-m-d H:i'), 'class' => 'datetime_picker', 'style' => 'width: 180px;']); ?>
					</td>
				</tr>

				<tr>
					<td>終了日時</td>
					<td>
						<?= $this->Form->input('end',  ['type' => 'text', 'value' => $entity->end ? (new DateTime($entity->end))->format('Y-m-d H:i') : '', 'class' => 'datetime_picker', 'style' => 'width: 180px;']); ?>
					</td>
				</tr>

			</table>

			<div class="btn_area">
				<?php if (@$entity->id > 0) { ?>
					<a href="#" class="btn_confirm submitButton submitButtonPost">変更する</a>
					<a href="javascript:kakunin('データを完全に削除します。よろしいですか？','<?= $this->Url->build(array('action' => 'delete', $entity->id, 'content', '?' => ['info_id' => $data_info->id])) ?>')" class="btn_delete">削除する</a>
				<?php } else { ?>
					<a href="#" class="btn_confirm submitButton submitButtonPost">登録する</a>
				<?php } ?>
			</div>
			<?= $this->Form->end(); ?>
		</div>
	</div>
</div>


<?php $this->start('beforeBodyClose'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />
<link rel="stylesheet" href="/user/common/css/cms.css">
<script src="/user/common/js/jquery.ui.datepicker-ja.js"></script>
<script src="/user/common/js/cms.js"></script>

<script>
	// detetimepicker
	$(function() {
		$.datetimepicker.setLocale('ja');
		$('.datetime_picker').datetimepicker({
			format: 'Y-m-d H:i',
			step: 30
		});
	});
</script>
<?php $this->end();
