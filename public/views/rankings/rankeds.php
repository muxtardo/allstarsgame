<?php echo partial('shared/title', array('title' => 'rankings.rankeds.title', 'place' => 'rankings.rankeds.title')) ?>
<div class="barra-secao barra-secao-<?php echo $player->character()->anime_id ?>">
	<p>Filtro do Ranking</p>
</div>
<form id="ranking-players-filter-form" method="post">
	<table width="725" border="0" cellpadding="0" cellspacing="0" class="filtros">
		<tr>
			<td align="center">
				<b><?php echo t('characters.create.labels.anime') ?></b><br />
				<select name="anime_id" id="anime_id" class="form-control" style="width:130px">
					<option value="0"><?=t('global.all');?></option>
					<?php foreach ($animes as $anime): ?>
					<option value="<?=$anime->id;?>" <?php if ($anime->id == $anime_id): ?>selected="selected"<?php endif; ?>><?=$anime->description()->name;?></option>
					<?php endforeach; ?>
				</select>
			</td>
            <td align="center">
				<b>Liga</b><br />
				<select name="league_id">
					<option value="0"><?php echo t('global.all') ?></option>
					<?php foreach ($leagues as $league): ?>
						<option value="<?php echo $league->league ?>" <?php if ($league->league == $league_id): ?>selected="selected"<?php endif ?>><?php echo $league->league ?></option>
					<?php endforeach ?>
				</select>
			</td>
			<td align="center">
				<b><?php echo t('characters.select.labels.faction') ?></b><br />
				<select name="faction_id">
					<option value="0"><?php echo t('global.all') ?></option>
					<option value="1" <?php if (1 == $faction_id): ?>selected="selected"<?php endif ?>>Herói</option>
					<option value="2" <?php if (2 == $faction_id): ?>selected="selected"<?php endif ?>>Vilões</option>
				</select>
			</td>
			<td align="center">
				<b><?php echo t('characters.select.labels.graduation') ?></b><br />
				<select name="graduation_id" style="width: 150px">
					<option value="0"><?php echo t('global.all') ?></option>
					<?php foreach ($graduations as $graduation): ?>
						<?php if ($anime_id): ?>
							<option value="<?php echo $graduation->id ?>" <?php if ($graduation->id == $graduation_id): ?>selected="selected"<?php endif ?>><?php echo $graduation->description()->name ?></option>
						<?php else: ?>
							<option value="<?php echo $graduation['id'] ?>" <?php if ($graduation['id'] == $graduation_id): ?>selected="selected"<?php endif ?>><?php echo $graduation['name'] ?></option>
						<?php endif ?>
					<?php endforeach ?>
				</select>
			</td>
			<td align="center">
				<b><?php echo t('rankings.players.header.nome') ?></b><br />
				<input type="text" name="name" class="form-control" value="<?=$name;?>" style="width:120px"/>
			</td>
			<td align="center">
				<a href="javascript:;" class="btn btn-primary filter" style="margin-top: 16px"><?php echo t('buttons.filtrar') ?></a>
			</td>
		</tr>
	</table>
	<br />
	<br />
	<input type="hidden" name="page" value="<?php echo $page ?>" />
		<?php foreach ($players as $p): ?>
		<?php
				if ($anime_id){
					if($p->position_anime==1){
						$cor_fundo = "#f9e1a7";
						$cor	   = "ouro";
						$class	   = "league-img-1";
					}
					if($p->position_anime==2){
						$cor_fundo = "#dddddd";
						$cor	   = "prata";
						$class	   = "league-img-2";
					}
					if($p->position_anime==3){
						$cor_fundo = "#f89b52";
						$cor	   = "bronzeado";
						$class	   = "league-img-3";
					}
					if($p->position_anime > 3){
						$cor_fundo = "#232323";
						$cor	   = "branco";
						$class	   = "league-img-4";
					}
				}else{
					if($p->position_general==1){
						$cor_fundo = "#f9e1a7";
						$cor	   = "ouro";
						$class	   = "league-img-1";
					}
					if($p->position_general==2){
						$cor_fundo = "#dddddd";
						$cor	   = "prata";
						$class	   = "league-img-2";
					}
					if($p->position_general==3){
						$cor_fundo = "#f89b52";
						$cor	   = "bronzeado";
						$class	   = "league-img-3";
					}
					if($p->position_general > 3){
						$cor_fundo = "#232323";
						$cor	   = "branco";
						$class	   = "league-img-4";
					}
				}	
			?>
			<div class="ability-speciality-box" style="width: 175px !important; height: 270px !important; padding-bottom: 40px">
				<div>
					<div class="image">
						<div class="<?php echo $class?>"><?php echo $p->character_theme()->first_image()->small_image() ?></div>
						<div style="position: absolute; left: 90px; top: -1px; border-radius: 10px; border-top-right-radius: 2px; border-bottom-left-radius: 2px; background-repeat: no-repeat; background-position: center center; background-color: <?php echo $cor_fundo?>; padding: 5px;">
							<?php if ($anime_id): ?>
								<b style="font-size:18px" class="<?php echo $cor?>"><?php echo $p->position_anime ?>º</b>
							<?php else: ?>
								<b style="font-size:18px" class="<?php echo $cor?>"><?php echo $p->position_general ?>º</b>
							<?php endif ?>
						</div>
					</div>
					<div class="name" style="height: 45px !important;">
						<span class="amarelo">
							<?php if (is_player_online($p->player_id)): ?>
								<img src="<?php echo image_url("on.png" ) ?>"/>
							<?php else: ?>
								<img src="<?php echo image_url("off.png" ) ?>"/>
							<?php endif ?>
							<?php echo $p->name ?></b><br />
						</span>
						<img src="<?php echo image_url( $p->faction_id.".png" ) ?>" width="25"/>
					</div>
					<div class="description" style="height: auto; font-size:11px">
						<span style="font-size:12px"><?php echo $p->anime()->description()->name ?> / <?php echo $p->graduation()->description()->name ?></span><br />
						Level <?php echo $p->level ?>
					</div>
					<div class="details">
						<b class="verde" style="font-size: 14px">Liga <?php echo $p->league_id ?></b><br />
						<b class="laranja" style="font-size: 14px">Rank <?php echo $p->rank == 0 ? "All-Star" : $p->rank?></b>
					</div>
					<div class="button" style="position:relative; top: 15px;">
					</div>
				</div>
			</div>
		<?php endforeach ?>
	<div class="break"></div>
	<?php echo partial('shared/paginator', ['pages' => $pages, 'current' => $page + 1]) ?>
</form>