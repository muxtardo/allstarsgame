<?php echo partial('shared/title', array('title' => 'menus.account', 'place' => 'menus.account')) ?>
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
<form class="form" id="f-account-join" onsubmit="return false">
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.name') ?></label>
			<input type="text" class="form-control input-sm" name="name" value="<?php echo $user->name?>" />
		</div>
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.email') ?></label>
			<input type="text" class="form-control input-sm" name="email" value="<?php echo $user->email?>" disabled="disabled" />
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.gender') ?></label>
			<select name="gender" class="form-control input-sm">
				<option value="1" <?php echo $user->gender == 1 ? "selected='selected'" : ""?>><?php echo t('genders.male') ?></option>
				<option value="2" <?php echo $user->gender == 2 ? "selected='selected'" : ""?>><?php echo t('genders.female') ?></option>
			</select>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.street') ?></label>
			<input type="text" class="form-control input-sm" name="street" placeholder="<?php echo t('users.join.placeholders.street') ?>" value="<?php echo $user->street?>" />
		</div>
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.city') ?></label>
			<input type="text" class="form-control input-sm"  placeholder="<?php echo t('users.join.placeholders.city') ?>" name="city" value="<?php echo $user->city?>" />
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.neighborhood') ?></label>
			<input type="text" class="form-control input-sm" name="neighborhood" placeholder="<?php echo t('users.join.placeholders.neighborhood') ?>" value="<?php echo $user->neighborhood?>" />
		</div>
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.state') ?></label>
			<input type="text" class="form-control input-sm"  placeholder="<?php echo t('users.join.placeholders.state') ?>" name="state" value="<?php echo $user->state?>" />
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.zip') ?></label>
			<input type="text" class="form-control input-sm" id="zip" name="zip" placeholder="<?php echo t('users.join.placeholders.zip') ?>" value="<?php echo $user->zip?>" maxlength="8"/><span id="errmsg"></span>
		</div>
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.country') ?></label>
			<select name="country_id" class="form-control input-sm select2">
				<?php foreach ($countries as $country): ?>
					<option value="<?php echo $country->id ?>" <?php echo $user->country_id == $country->id ? "selected='selected'" : ""?>><?php echo $country->name ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<hr />
	<h5 class="verde text-center">Para alterar sua senha, preencha o formul??rio abaixo:</h5>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.password') ?></label>
			<input type="password" class="form-control input-sm" id="password" name="password" placeholder="<?php echo t('users.join.placeholders.password2') ?>" />
		</div>
		<div class="col-md-6"></div>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.password_new') ?></label>
			<input type="password" class="form-control input-sm" id="password_new" name="password_new" placeholder="<?php echo t('users.join.placeholders.password_new') ?>" />
		</div>
		<div class="form-group col-md-6">
			<label class="control-label"><?php echo t('users.join.labels.password_new_confirmation') ?></label>
			<input type="password" class="form-control input-sm" id="password_new_confirmation" name="password_new_confirmation" placeholder="<?php echo t('users.join.placeholders.password_new_confirmation') ?>" />
		</div>
	</div>
	<hr />
	<div class="form-group text-center">
		<input type="submit" class="btn btn-sm btn-primary" value="<?php echo t('users.join.update_account') ?>" />
	</div>
</form>
