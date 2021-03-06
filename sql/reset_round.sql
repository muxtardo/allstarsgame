/* LOG BATALHAS */
TRUNCATE TABLE `battle_npcs`;
TRUNCATE TABLE `battle_pvps`;
TRUNCATE TABLE `battle_rooms`;

/* BATALHA DE ANIMES */
TRUNCATE TABLE `event_animes`;

/* GULDS */
UPDATE `guilds` SET
	`level`								= 1,
	`exp`								= 0,
	`treasure_atual`					= 0,
	`treasure_total`					= 0,
	`guild_accepted_event_id`			= 0;
TRUNCATE TABLE `guild_accepted_events`;
TRUNCATE TABLE `guild_daily_quests`;
TRUNCATE TABLE `guild_map_object_sessions`;
TRUNCATE TABLE `guild_requests`;
UPDATE `guild_quest_counters` SET
	`time_total`						= 0,
	`pvp_total`							= 0,
	`daily_total`						= 0;

/* TEMPO DE JOGO */
UPDATE `played_time` SET
	`minutes`							= 0;

/* PERSONAGENS */
UPDATE `players` SET
	`map_id`							= 0,
	`graduation_id`						= 1,
	`battle_npc_challenge`				= 0,
	`battle_npc_id`						= 0,
	`battle_pvp_id`						= 0,
	`battle_room_id`					= 0,
	`challenge_id`						= 0,
	`time_quest_id`						= 0,
	`pvp_quest_id`						= 0,
	`is_pvp_queued`						= 0,
	`no_talent`							= 0,
	`pvp_queue_found`					= NULL,
	`level`								= 1,
	`exp`								= 0,
	`currency`							= 0,
	`less_life`							= 0,
	`less_mana`							= 0,
	`less_stamina`						= 0,
	`hospital`							= 0,
	`for_atk`							= 0,
	`for_def`							= 0,
	`for_crit`							= '0.00',
	`for_abs`							= '0.00',
	`for_prec`							= '0.00',
	`for_init`							= '0.00',
	`for_inc_crit`						= '0.00',
	`for_inc_abs`						= '0.00',
	`enchant_points`					= 0,
	`enchant_points_total`				= 0,
	`weekly_points_spent`				= 0,
	`training_points_spent`				= 0,
	`training_total`					= 0,
	`training_complete_at`				= NULL,
	`treasure_atual`					= 0,
	`treasure_total`					= 0,
	`training_type`						= 0,
	`technique_training_spent`			= 0,
	`technique_training_id`				= 0,
	`technique_training_duration`		= 0,
	`technique_training_complete_at`	= NULL,
	`last_healed_at`					= NULL,
	`luck_used`							= 0,
	`draws_npc`							= 0,
	`draws_pvp`							= 0,
	`wins_npc`							= 0,
	`wins_pvp`							= 0,
	`losses_npc`						= 0,
	`losses_pvp`						= 0,
	`draws`								= 0,
	`won_last_battle`					= 0,
	`first_actions`						= 0;
DELETE FROM `player_achievements` WHERE `achievement_id` IN (SELECT `id` FROM `achievements` WHERE `type` = 'achievements');
UPDATE `player_attributes` SET
	`for_atk`							= 0,
	`for_def`							= 0,
	`for_crit`							= 0,
	`for_abs`							= 0,
	`for_prec`							= 0,
	`for_init`							= 0,
	`for_inc_crit`						= 0,
	`for_inc_abs`						= 0,
	`sum_at_for`						= 0,
	`sum_at_int`						= 0,
	`sum_at_res`						= 0,
	`sum_at_agi`						= 0,
	`sum_at_dex`						= 0,
	`sum_at_vit`						= 0,
	`sum_for_life`						= 0,
	`sum_for_mana`						= 0,
	`sum_for_stamina`					= 0,
	`sum_for_atk`						= 0,
	`sum_for_def`						= 0,
	`sum_for_hit`						= 0,
	`sum_for_init`						= 0,
	`sum_for_crit`						= 0,
	`sum_for_inc_crit`					= 0,
	`sum_for_abs`						= 0,
	`sum_for_inc_abs`					= 0,
	`sum_for_prec`						= 0,
	`sum_for_inti`						= 0,
	`sum_for_conv`						= 0,
	`sum_bonus_food_discount`			= 0,
	`sum_bonus_weapon_discount`			= 0,
	`sum_bonus_luck_discount`			= 0,
	`sum_bonus_mana_consume`			= 0,
	`sum_bonus_cooldown`				= 0,
	`sum_bonus_exp_fight`				= 0,
	`sum_bonus_currency_fight`			= 0,
	`sum_bonus_attribute_training_cost`	= 0,
	`sum_bonus_training_earn`			= 0,
	`sum_bonus_training_exp`			= 0,
	`sum_bonus_quest_time`				= 0,
	`sum_bonus_food_heal`				= 0,
	`sum_bonus_npc_in_quests`			= 0,
	`sum_bonus_daily_npc`				= 0,
	`sum_bonus_map_npc`					= 0,
	`sum_bonus_drop`					= 0,
	`sum_bonus_stamina_max`				= 0,
	`sum_bonus_stamina_heal`			= 0,
	`sum_bonus_stamina_consume`			= 0,
	`currency_battle`					= 0,
	`exp_battle`						= 0,
	`currency_quest`					= 0,
	`exp_quest`							= 0,
	`generic_technique_damage`			= 0,
	`defense_technique_extra`			= 0,
	`unique_technique_damage`			= 0,
	`stamina_regen`						= 0,
	`life_regen`						= 0,
	`mana_regen`						= 0;
UPDATE `player_battle_counters` SET
	`total_npc_made`					= 0,
	`total_pvp_made`					= 0,
	`current_npc_made`					= 0,
	`current_pvp_made`					= 0;
UPDATE `player_battle_pvp_logs` SET
	`wins`								= 0,
	`losses`							= 0,
	`draws`								= 0;
TRUNCATE TABLE `player_battle_pvps`;
UPDATE `player_battle_stats` SET
	`victory_pvp`						= 0,
	`victory_npc`						= 0,
	`looses_pvp`						= 0,
	`looses_npc`						= 0,
	`draws_pvp`							= 0,
	`draws_npc`							= 0,
	`victory_pvp_weekly`				= 0,
	`victory_npc_weekly`				= 0,
	`looses_pvp_weekly`					= 0,
	`looses_npc_weekly`					= 0,
	`draws_pvp_weekly`					= 0,
	`draws_npc_weekly`					= 0,
	`victory_pvp_monthly`				= 0,
	`victory_npc_monthly`				= 0,
	`looses_pvp_monthly`				= 0,
	`looses_npc_monthly`				= 0,
	`draws_pvp_monthly`					= 0,
	`draws_npc_monthly`					= 0;
UPDATE `player_changes` SET
	`daily`								= 0,
	`weekly`							= 0,
	`pet`								= 0;
TRUNCATE TABLE `player_challenges`;
UPDATE `player_fidelities` SET
	`day`								= 1,
	`reward`							= 0,
	`created_at`						= NULL,
	`reward_at`							= NULL;
TRUNCATE TABLE `player_combat_quests`;
TRUNCATE TABLE `player_daily_quests`;
TRUNCATE TABLE `player_friend_requests`;
DELETE FROM `player_items` WHERE `item_id` NOT IN (SELECT `id` FROM `items` WHERE `item_type_id` = 3);
DELETE FROM `player_item_attributes` WHERE `player_item_id` NOT IN (SELECT `id` FROM `player_items`);
DELETE FROM `player_item_gems` WHERE `item_id` NOT IN (SELECT `item_id` FROM `player_items`);
DELETE FROM `player_item_stats` WHERE `player_item_id` NOT IN (SELECT `id` FROM `player_items`);
TRUNCATE TABLE `player_kills`;
DELETE FROM `player_luck_logs` WHERE `luck_reward_id` NOT IN (SELECT `id` FROM `luck_rewards` WHERE `type` = 3);
TRUNCATE TABLE `player_map_animes`;
TRUNCATE TABLE `player_map_logs`;
TRUNCATE TABLE `player_pet_quests`;
TRUNCATE TABLE `player_pvp_quests`;
UPDATE `player_quest_counters` SET
	`time_total`						= 0,
	`pvp_total`							= 0,
	`daily_total`						= 0,
	`pet_total`							= 0,
	`combat_total`						= 0;
UPDATE `player_stats` SET
	`luck_week_data`					= NULL,
	`fragments`							= 0,
	`sands`								= 0,
	`bloods`							= 0,
	`rewards`							= 0,
	`npc`								= 0,
	`total_rewards`						= 0,
	`map_reward`						= 0,
	`npc_anime_id`						= 0,
	`npc_character_id`					= 0,
	`npc_challenge_anime_id`			= 0,
	`npc_challenge_character_id`		= 0,
	`npc_challenge_character_theme_id`	= 0,
	`tutorial`							= 0,
	`view_habilidades`					= 0,
	`view_golpes`						= 0;
DELETE FROM `player_star_items` WHERE `buy_mode` IN (0, 1);

/* RANKINGS */
TRUNCATE TABLE `player_store_logs`;
TRUNCATE TABLE `player_time_quests`;
TRUNCATE TABLE `player_treasure_logs`;
TRUNCATE TABLE `player_wanteds`;

TRUNCATE TABLE `private_messages`;

TRUNCATE TABLE `ranking_challenges`;
TRUNCATE TABLE `ranking_guilds`;
TRUNCATE TABLE `ranking_players`;

/* CONTAS */
UPDATE `users` SET
	`level`								= 1,
	`exp`								= 0,
	`session_key`						= NULL,
	`objectives`						= 0;
UPDATE `user_changes` SET
	`daily`								= 0,
	`weekly`							= 0,
	`pet`								= 0;
TRUNCATE TABLE `user_daily_quests`;
TRUNCATE TABLE `user_history_mode_groups`;
TRUNCATE TABLE `user_history_mode_npcs`;
TRUNCATE TABLE `user_history_mode_subgroups`;
TRUNCATE TABLE `user_objectives`;
UPDATE `user_quest_counters` SET
	`time_total`						= 0,
	`pvp_total`							= 0,
	`daily_total`						= 0,
	`pet_total`							= 0;
UPDATE `user_stats` SET
	`credits`							= NULL,
	`exp`								= NULL;