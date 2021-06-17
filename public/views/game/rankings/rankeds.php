<style type="text/css">
	.select2-container--bootstrap {
		margin-bottom: 0;
	}
</style>
<?=partial('shared/title', [
	'title'	=> 'rankings.rankeds.title',
	'place'	=> 'rankings.rankeds.title'
]);?>
<div class="barra-secao barra-secao-<?=$player->character()->anime_id;?>">
	<p>Filtro do Ranking</p>
</div>
<form id="ranking-players-filter-form" method="post">
	<table width="725" border="0" cellpadding="0" cellspacing="0" class="filtros">
		<tr>
			<td align="center">
				<b><?=t('characters.create.labels.anime');?></b><br />
				<select name="anime_id" id="anime_id" class="form-control input-sm select2" style="width:130px">
					<option value="0"><?=t('global.all');?></option>
					<?php foreach ($animes as $anime): ?>
					<option value="<?=$anime->id;?>" <?php if ($anime->id == $anime_id): ?>selected="selected"<?php endif; ?>><?=$anime->description()->name;?></option>
					<?php endforeach; ?>
				</select>
			</td>
            <td align="center">
				<b>Liga</b><br />
				<select name="league_id" class="form-control input-sm" style="width: 80px;">
					<option value="0"><?=t('global.all');?></option>
					<?php foreach ($rankeds as $ranked): ?>
						<option value="<?=$ranked->id;?>" <?php if ($ranked->id == $ranked_id): ?>selected="selected"<?php endif ?>><?=$ranked->id;?></option>
					<?php endforeach ?>
				</select>
			</td>
			<td align="center">
				<b><?=t('characters.select.labels.faction');?></b><br />
				<select name="faction_id" class="form-control input-sm" style="width: 85px;">
					<option value="0"><?=t('global.all');?></option>
					<?php foreach ($factions as $faction): ?>
						<option value="<?=$faction->id;?>"<?php if ($faction->id == $faction_id): ?>selected="selected"<?php endif ?>><?=$faction->description()->name;?></option>
          			<?php endforeach ?>
				</select>
			</td>
			<td align="center">
				<b><?=t('characters.select.labels.graduation');?></b><br />
				<select name="graduation_id" class="form-control input-sm" style="width: 130px">
					<option value="0"><?=t('global.all');?></option>
					<?php if ($anime_id): foreach ($graduations as $graduation): ?>
						<?php if ($anime_id): ?>
							<option value="<?=$graduation->id;?>" <?php if ($graduation->id == $graduation_id): ?>selected="selected"<?php endif ?>><?=$graduation->description($anime_id)->name;?></option>
						<?php else: ?>
							<option value="<?=$graduation['id'];?>" <?php if ($graduation['id'] == $graduation_id): ?>selected="selected"<?php endif ?>><?=$graduation['name'];?></option>
						<?php endif; ?>
					<?php endforeach; endif; ?>
				</select>
			</td>
			<td align="center">
				<b><?=t('rankings.players.header.nome');?></b><br />
				<input type="text" name="name" class="form-control input-sm" value="<?=$name;?>" style="width:120px"/>
			</td>
			<td align="center">
				<a href="javascript:;" class="btn btn-sm btn-primary filter" style="margin-top: 14px"><?=t('buttons.filtrar');?></a>
			</td>
		</tr>
	</table>
	<br /><br />
	<input type="hidden" name="page" value="<?=$page;?>" />
	<?php
	foreach ($players as $p) {
		if ($faction_id) {
			if($p->position_faction == 1) {
				$cor_fundo = "#f9e1a7";
				$cor	   = "ouro";
				$class	   = "league-img-1";
			}
			if ($p->position_faction == 2) {
				$cor_fundo = "#dddddd";
				$cor	   = "prata";
				$class	   = "league-img-2";
			}
			if ($p->position_faction == 3) {
				$cor_fundo = "#f89b52";
				$cor	   = "bronzeado";
				$class	   = "league-img-3";
			}
			if ($p->position_faction > 3) {
				$cor_fundo = "#232323";
				$cor	   = "branco";
				$class	   = "league-img-4";
			}
		} else {
			if ($p->position_general == 1) {
				$cor_fundo = "#f9e1a7";
				$cor	   = "ouro";
				$class	   = "league-img-1";
			}
			if ($p->position_general == 2) {
				$cor_fundo = "#dddddd";
				$cor	   = "prata";
				$class	   = "league-img-2";
			}
			if ($p->position_general == 3) {
				$cor_fundo = "#f89b52";
				$cor	   = "bronzeado";
				$class	   = "league-img-3";
			}
			if ($p->position_general > 3){
				$cor_fundo = "#232323";
				$cor	   = "branco";
				$class	   = "league-img-4";
			}
		}
	?>
		<div class="ability-speciality-box" style="width: 175px !important; height: 250px !important; padding-bottom: 40px">
			<div class="image" align="center">
				<div class="<?=$class;?>">
					<div class="position">
						<?php if ($faction_id) { ?>
							<b class="<?=$cor;?>"><?=highamount($p->position_faction);?>º</b>
						<?php } else { ?>
							<b class="<?=$cor;?>"><?=highamount($p->position_general);?>º</b>
						<?php } ?>
					</div>
					<?=$p->character_theme()->first_image()->small_image();?>
				</div>
			</div>
			<div class="name" style="height: 45px !important;">
				<div class="amarelo" style="margin-bottom: 6px;">
					<img src="<?=image_url((is_player_online($p->player_id) ? 'on' : 'off') . ".png");?>" />
					<b><?=$p->name;?></b>
				</div>
				<img src="<?=image_url('factions/icons/big/' . $p->faction_id . ".png");?>" width="25" />
			</div>
			<div class="description" style="height: auto; font-size:11px">
				<span style="font-size:12px">
					<?=$p->anime()->description()->name;?><br />
					<?=$p->graduation()->description($p->anime()->id)->name;?>
				</span><br />
				Nível <?=highamount($p->level);?>
			</div>
			<div class="details">
				<div class="technique-popover buff" data-source="#ranking-container-<?=$p->id;?>" data-title="Resumo na Liga" data-trigger="click" data-placement="bottom">
					<b class="verde" style="font-size: 14px">Liga <?=$p->ranked_id;?></b><br />
					<b class="laranja" style="font-size: 14px"><?=$p->ranked_tier()->description()->name;?></b>
					<div id="ranking-container-<?=$p->id;?>" class="technique-container">
						<div class="status-popover-content" style="min-width: 150px;">
							Vitórias: <span class="verde" style="float: right;"><?=highamount($p->wins);?></span><br />
							Empates: <span class="cinza" style="float: right;"><?=highamount($p->draws);?></span><br />
							Derrotas: <span class="vermelho" style="float: right;"><?=highamount($p->losses);?></span><br />
							Pontos de Liga: <span class="laranja" style="float: right;"><?=highamount($p->score);?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="button" style="position:relative; top: 15px;"></div>
		</div>
	<?php } ?>
	<div class="break"></div>
	<?=partial('shared/paginator', [
		'pages'		=> $pages,
		'current'	=> $page + 1
	]);?>
</form>