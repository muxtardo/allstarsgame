<?=partial('shared/title',	[
	'title'	=> 'users.activation.title',
	'place'	=> 'users.activation.title'
]);?>
<?php if (FW_ENV != 'dev') { ?>
	<!-- AASG - Users -->
	<ins class="adsbygoogle"
		style="display:inline-block;width:728px;height:90px"
		data-ad-client="ca-pub-6665062829379662"
		data-ad-slot="3196308392"></ins>
	<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
	</script><br />
<?php } ?>
<?=partial('shared/info',	[
	'id'		=> 1,
	'title'		=> 'users.activation.title',
	'message'	=> t('users.activation.base_text')
]);?>
<form method="post" action="<?=make_url('users#activate');?>" clas="form">
	<div class="form-group">
		<label class="control-label"><?=t('users.activation.labels.key');?></label>
		<input type="text" class="form-control input-sm" placeholder="<?=t('users.activation.placeholders.key');?>" name="key" />
	</div>
	<div class="pull-right">
		<input type="submit" value="<?=t('buttons.proceed');?>" class="btn btn-sm btn-primary" />
	</div>
	<div class="clearfix"></div>
</form>
