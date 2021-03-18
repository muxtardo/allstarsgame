<?php echo partial('shared/title', array('title' => 'quests.pvp.status.title', 'place' => 'quests.pvp.status.title')) ?>
<div class="msg-container">
	<div class="msg_top"></div>	
	 <div class="msg_repete">
		<div class="msg" style="background:url(<?php echo image_url('msg/'. $player->character()->anime_id . '-3.png')?>); background-repeat: no-repeat;">
		</div>
		<div class="msgb" style="position:relative; margin-left: 231px; text-align: left; top: -37px">
			<b><?php echo $quest->description()->name ?></b>
			<div class="content">
				<?php if(!$can_finish): ?>
				<ul>
					<?php if ($quest->req_same_level): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_same_level', ['count' => $quest->req_same_level, 'have' => $player_quest->req_same_level]) ?></li>
					<?php endif ?>
					<?php if ($quest->req_low_level): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_low_level', ['count' => $quest->req_low_level, 'have' => $player_quest->req_low_level]) ?></li>
					<?php endif ?>
					<?php if ($quest->req_kill_wo_amplifier): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_kill_wo_amplifier', ['count' => $quest->req_kill_wo_amplifier, 'have' => $player_quest->req_kill_wo_amplifier]) ?></li>
					<?php endif ?>
					<?php if ($quest->req_kill_wo_buff): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_kill_wo_buff', ['count' => $quest->req_kill_wo_buff, 'have' => $player_quest->req_kill_wo_buff]) ?></li>
					<?php endif ?>
					<?php if ($quest->req_kill_wo_ability): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_kill_wo_ability', ['count' => $quest->req_kill_wo_ability, 'have' => $player_quest->req_kill_wo_ability]) ?></li>
					<?php endif ?>
					<?php if ($quest->req_kill_wo_speciality): ?>
						<li><?php echo t('quests.pvp.status_conditions.req_kill_wo_speciality', ['count' => $quest->req_kill_wo_speciality, 'have' => $player_quest->req_kill_wo_speciality]) ?></li>
					<?php endif ?>
				</ul>
				<?php else: ?>
					<h3>
						<?php echo t('quests.finish_pvp_text', ['currency' => $quest->currency(), 'exp' => $quest->exp(), 'currency_name' => t('currencies.' . $player->character()->anime_id)]) ?>
						<?php if ($player_quest->reward_pet_id): ?>
							<?php echo t('quests.finish_pet_text', ['pet' => Item::find($player_quest->reward_pet_id)->description()->name]) ?>
						<?php endif ?>
						<?php if ($player_quest->reward_equipment): ?>
							<?php echo t('quests.finish_equipment_text') ?>
						<?php endif ?>
					</h3>

					<a id="pvp-quest-finish" class="btn btn-sm btn-primary" href="javascript:;"><?php echo t('quests.finish') ?></a>
				<?php endif ?>
			</div>
		</div>		
	</div>
	<div class="msg_bot"></div>
	<div class="msg_bot2"></div>
</div>
<br />
<?php if (!$can_finish): ?>
	<div align="center">
		<a id="pvp-quest-cancel" class="btn btn-sm btn-danger" href="javascript:;"><?php echo t('quests.cancel') ?></a>
	</div>	
<?php endif ?>
