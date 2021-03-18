<?php echo partial('shared/title', array('title' => 'menus.quests_account', 'place' => 'menus.quests_account')) ?>
<?php if(!$player_tutorial->missoes_conta){?>
<script>
$(function () {
	 $("#conteudo.with-player").css("z-index", 'initial');
	 $(".info").css("z-index", 'initial');
	 $("#background-topo2").css("z-index", 'initial');
	
    var tour = new Tour({
	  backdrop: true,
	  page: 22,
	 
	  steps: [
	  {
		element: ".msg-container",
		title: "Trabalho em Equipe",
		content: "Essas Missões são compartilhadas entre todos os personagens da sua conta, e ao completá-las você irá receber Experiência de Conta!",
		placement: "top"
	  },{
		element: ".msg-container",
		title: "Atenção",
		content: "No dia que você criou seu personagem você não irá ter nenhuma Missão Diária, mas à meia noite você já irá receber suas quatro primeiras missões!",
		placement: "bottom"
	  }
	]});
	//Renicia o Tour
	tour.restart();
	
	// Initialize the tour
	tour.init(true);
	
	// Start the tour
	tour.start(true);
	
});
</script>	
<?php }?>
<?php
	echo partial('shared/info', [
		'id'		=> 1,
		'title'		=> 'quests.daily.help_title3',
		'message'	=> t('quests.daily.help_description')
	]);
?>
<br />
<?php 
	foreach ($quests as $quest):
		
		if(!$quest->complete){
		
		$player_quest = DailyQuest::find('id='.$quest->daily_quest_id,['cache' => true]);
		$personagem = Character::find($quest->character_id, array('cache' => true));
		$anime 		= Anime::find($quest->anime_id, array('cache' => true));
		$currency 	= DailyQuest::find($quest->daily_quest_id, array('cache' => true));
?>
	<div class="ability-speciality-box" data-id="<?php echo $quest->id ?>" style="height: 270px !important;">
	<div>
		<div class="image">
			<img src="<?php echo image_url('daily/'.$quest->daily_quest_id.'.png') ?>" />

		</div>
		<div class="name <?php echo $currency->dificuldade?>" style="height: 15px !important;">
			Missão Diária <?php echo $quest->daily_quest_id ?>
		</div>
		<div class="description" style="height: 60px !important;">
		<?php 
			switch($quest->daily_quest_id){
				case 32:
				case 42:
				case 41:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Fragmentos das Almas</span> em combates PVP / NPC com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 33:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Sangue de Deus</span> em combates PVP com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 34:
				case 40:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Areia Estelar</span> em combates PVP com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 35:
				case 44:
				case 43:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Equipamentos</span> em combates PVP / NPC com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 36:
				case 45:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Páginas Perdidas</span> em combates PVP / NPC com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 37:
				case 46:
				case 47:
					$descricao = "Roube ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Tesouros</span> em combates PVP com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 38:
				case 48:
				case 49:
					$descricao = "Mate ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Procurados</span> em combates PVP com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				case 39:
				case 50:
				case 51:
					$descricao = "Drope ". ($quest->total > 10 ? 10 : $quest->total) ." de ".$player_quest[0]->total." <span class='laranja'>Mascotes</span> em combates PVP / NPC com Personagem do Anime <span class='laranja'>". $anime->description()->name ."</span>";
				break;
				
			}
		?>
		<?php echo $descricao?><br />
		</div>
		<div class="details">
			<span class="amarelo_claro" style="font-size: 16px; margin-left: 5px; top: 2px; position: relative"><?php echo $currency->currency?> Exp de Conta</span>
		</div>
		<div class="change-mission" style="margin-top: 10px">
			<?php if(!$quest->complete){?>
				<a data-id="<?php echo $quest->id ?>" data-quest="<?php echo $quest->daily_quest_id ?>" class="btn btn-sm btn-primary account_quests_change">
					
					<?php 
						if($buy_mode_change){
							if($buy_mode_change->daily == 0){
								echo "Trocar grátis";							
							}elseif($buy_mode_change->daily > 0 && $buy_mode_change->daily < 5){
								
								$valor_change = $buy_mode_change->daily * 500;
								
								echo "Trocar por ".$valor_change .' '. t('currencies.' . $player->character()->anime_id);
					
							}elseif($buy_mode_change->daily > 4){
								
								if($buy_mode_change->daily > 4  && $buy_mode_change->daily < 10){
									$valor_change = 1;
								}elseif($buy_mode_change->daily > 9  && $buy_mode_change->daily < 15){
									$valor_change = 2;
								}elseif($buy_mode_change->daily > 14  && $buy_mode_change->daily < 20){	
									$valor_change = 3;
								}elseif($buy_mode_change->daily >= 20){
									$valor_change = 5;
								}
								echo "Trocar por ". $valor_change. " Estrela(s)";
							}
						}else{
							echo "Trocar grátis";
						}
					?>
					
				</a>
			<?php }?>	
		</div>
	</div>
</div>
<?php }?>
<?php endforeach ?>
<?php
	if(sizeof($quests)){
?>	
<div class="clearfix" align="center" style="position:relative; top:10px;">
	<a id="account_quests_finish" class="btn btn-sm btn-primary"><?php echo t('quests.daily.finish') ?></a>
</div>					
<?php } ?>
