<?php
	$ranking 				= $player->ranking();
	$ranking_achievement 	= $player->ranking_achievement();
	$ranking_guild 			= $player->ranking_guild();
	$ranking_account 		= $player->ranking_account();
	$tutorial				= $player->tutorial();
?>
<div style="width:242px; height:285px; float: left; text-align: center" class="tutorial_profile">
	<?php echo $player->profile_image() ?>
	<input class="button btn btn-sm btn-warning" type="button" id="current-player-change-theme" data-url="<?php echo make_url('characters#list_themes') ?>" value="Temas" style="position:relative; top: -30px" />
	<input class="button btn btn-sm btn-primary" type="button" id="current-player-change-image" data-url="<?php echo make_url('characters#list_images') ?>" value="Imagens" style="position:relative; top: -30px" />
</div>
<?php $next_level_menu = Menu::find(41); ?>
<?php if (is_menu_accessible($next_level_menu, $player)): ?>
	<div align="center" style="margin-left: -22px">
		<a href="<?php echo make_url($next_level_menu->href) ?>" class="btn btn-sm btn-primary" style="width: 242px"><?php echo t($next_level_menu->name) ?></a>
	</div>
<?php endif ?>
<div style="color: #FFFFFF; width: 240px; float: left; text-align: left">
	<?php
		$ability	= $player->ability();
		$speciality	= $player->speciality();
		$pet   		= $player->get_active_pet();
	?>
	<div style="width: 288px !important; clear: left; float: left; position: relative; left: -26px;">
		<a href="<?=make_url('techniques#abilities_and_specialities');?>" style="cursor:pointer">
		<div style="float: left">
			<img src="<?php echo image_url("battle/left.png" ) ?>" />
			<div class="technique-popover" data-source="#ability-container-<?php echo $player->id ?>" data-title="<?php echo $ability->description()->name ?>" data-trigger="click" data-placement="bottom" style="position: absolute; top: 33px; left: 17px; z-index: 100"><?php echo $ability->image() ?></div>
			<div id="ability-container-<?php echo $player->id ?>" class="technique-container">
				<?php echo $ability->tooltip($player) ?>
			</div>
		</div>
		</a>
		<?php if ($pet) { ?>
		<?php $pet_item = $pet->item() ?>
			<div style="position: absolute; top: 35px; left: 75px;">
				<a href="<?=make_url('characters#pets');?>" style="cursor:pointer">
					<div class="technique-popover" data-source="#pet-container-<?php echo $player->id ?>" data-title="<?php echo $pet_item->description()->name ?>" data-trigger="click" data-placement="bottom">
						<?=$pet_item->image();?>
					</div>
					<div id="pet-container-<?php echo $player->id ?>" class="technique-container">
						<?php echo $pet_item->tooltip($player) ?>
					</div>
				</a>
			</div>
		<?php } else { ?>
			<div style="position: absolute; top: 35px; left: 75px;">
				<a href="<?=make_url('characters#pets');?>" style="cursor:pointer">
					<img src="<?php echo image_url("battle/center.png" ) ?>" border="0"/>
				</a>
			</div>
		<?php } ?>

		<div style="position: absolute; width: 288px; top: 75px; text-align: center; z-index: 1">
			<b style="font-size: 13px !important"><?php echo $player->name ?></b>
		</div>
		<a href="<?=make_url('techniques#abilities_and_specialities');?>" style="cursor:pointer">
		<div style="float: right">
			<img src="<?php echo image_url("battle/right.png" ) ?>" />
			<div class="technique-popover buff" data-source="#speciality-container-<?php echo $player->id ?>" data-title="<?php echo $speciality->description()->name ?>" data-trigger="click" data-placement="bottom" style="position: absolute; top: 33px; left: 207px; z-index: 100"><?php echo $speciality->image() ?></div>
			<div id="speciality-container-<?php echo $player->id ?>" class="technique-container">
				<?php echo $speciality->tooltip($player) ?>
			</div>
		</div>
		</a>
	</div>
	<select id="character-change-headline" class="form-control input-sm select2">
		<option value="0"><?=t('characters.no_headline');?></option>
		<?php foreach ($player->user()->headlines() as $user_headline): ?>
			<option value="<?=$user_headline->id;?>" <?=($player->headline_id == $user_headline->headline_id ? 'selected' : '');?>><?=$user_headline->headline()->description()->name;?></option>
		<?php endforeach ?>
	</select>
	<?php if (!$tutorial) { ?>
		<a href="<?=make_url('events#tutorial');?>">
			<div class="tutorial cursor_pointer"></div>
		</a>
	<?php } else { echo '<br />'; } ?>
	<?=partial('shared/metamask', [
		'user'	=> $user
	]);?>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao">
			<b class="amarelo"><?php echo t('global.anime') ?></b>
			<b class=""><?=$player->character()->anime()->description()->name;?></b>
		</div>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo"><?php echo t('guilds.faction') ?></b>
			<b class=""><?=$player->faction()->description()->name;?></b>
		</div>
	</div>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Estrelas</b>
			<b class=""><?php echo $user->credits ? highamount($user->credits) : "-" ?></b>
		</div>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Pts. Evento</b>
			<b class=""><?php echo $user->round_points ? highamount($user->round_points) : "-" ?></b>
		</div>
	</div>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao">
			<b class="amarelo"><?php echo t('global.points') ?></b>
			<b class=""><?php echo $ranking ? highamount($ranking->score) : "-" ?></b>
		</div>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Rank Geral</b>
			<b class=""><?php echo $ranking ? highamount($ranking->position_general) . "&ordm;" : "-" ?></b>
		</div>
	</div>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Rank Fac????o</b>
			<b class=""><?php echo $ranking ? highamount($ranking->position_faction) . "&ordm;" : "-" ?></b>
		</div>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Rank Conta</b>
			<b class=""><?php echo $ranking_account ? highamount($ranking_account->position_general) . "&ordm;" : "-" ?></b>
		</div>
	</div>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Rank Org.</b>
			<b class=""><?php echo $ranking_guild ? highamount($ranking_guild->position_general) . "&ordm;" : "-" ?></b>
		</div>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo">Rank Conquista</b>
			<b class=""><?php echo $ranking_achievement ? highamount($ranking_achievement->position_general) . "&ordm;" : "-" ?></b>
		</div>
	</div>
	<div class="bg_menu_esquerdo">
		<div class="menu_esquerdo_divisao" style="width: 80px;">
			<b class="amarelo" data-toggle="tooltip" title="<?=make_tooltip('Multiplicador de Exp');?>">Exp</b>
			<b class=""><?=EXP_RATE;?>x</b>
		</div>
		<div class="menu_esquerdo_divisao" style="width: 80px;">
			<b class="amarelo" data-toggle="tooltip" title="<?=make_tooltip('Multiplicador de ' . t('currencies.' . $player->character()->anime_id));?>"><?=t('currencies.' . $player->character()->anime_id);?></b>
			<b class=""><?=MONEY_RATE;?>x</b>
		</div>
		<div class="menu_esquerdo_divisao" style="width: 80px;">
			<b class="amarelo" data-toggle="tooltip" title="<?=make_tooltip('Multiplicador de Drop');?>">Drop</b>
			<b class=""><?=DROP_RATE;?>x</b>
		</div>
	</div>
	<?php if ($_SESSION['universal']) { ?>
		<div class="bg_menu_esquerdo">
			<?php
			$timeout	= now() - (15 * 60);
			$online		= Recordset::query("SELECT `id` FROM `players` WHERE `last_activity` > {$timeout}", false)->num_rows;
			$queueds	= Recordset::query("SELECT `id` FROM `players` WHERE `is_pvp_queued` = 1", false)->num_rows;
			?>
			<div class="menu_esquerdo_divisao">
				<b class="amarelo">Online</b>
				<b class=""><?=highamount($online);?></b>
			</div>
			<div class="menu_esquerdo_divisao">
				<b class="amarelo">Fila PvP</b>
				<b class=""><?=highamount($queueds);?></b>
			</div>
		</div>
	<?php } ?>
	<div class="bg_menu_esquerdo">
		<?php $battles_pvp	= Recordset::query("SELECT `id` FROM `battle_pvps` WHERE `finished_at` IS NOT NULL ", false)->num_rows; ?>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo" data-toggle="tooltip" title="<?=make_tooltip('Total de Batalhas PvP');?>">Batalhas PvP</b>
			<b class=""><?=highamount($battles_pvp);?></b>
		</div>
		<?php $battles_npc	= Recordset::query("SELECT `id` FROM `battle_npcs` WHERE `finished_at` IS NOT NULL ", false)->num_rows; ?>
		<div class="menu_esquerdo_divisao">
			<b class="amarelo" data-toggle="tooltip" title="<?=make_tooltip('Total de Batalhas NPC');?>">Batalhas NPC</b>
			<b class=""><?=highamount($battles_npc);?></b>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
