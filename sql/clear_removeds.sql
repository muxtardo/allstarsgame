DELETE FROM users WHERE removed = 1;
DELETE FROM user_changes WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_characters WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_character_themes WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_character_theme_images WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_daily_quests WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_headlines WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_history_mode_groups WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_history_mode_npcs WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_history_mode_subgroups WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_objectives WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_player_items WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_quest_counters WHERE user_id NOT IN (SELECT id FROM users);
DELETE FROM user_stats WHERE user_id NOT IN (SELECT id FROM users);

DELETE FROM players WHERE user_id NOT IN (SELECT id FROM users) OR removed = 1;
DELETE FROM player_achievements WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_achievement_stats WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_attributes WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_attribute_percents WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_battle_counters WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_battle_pvp_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_battle_pvps WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_battle_stats WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_challenges WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_changes WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_character_abilities WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_character_specialities WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_combat_quests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_daily_quests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_fidelities WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_friend_lists WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_friend_requests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_gift_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_items WHERE player_id NOT IN (SELECT id FROM players) OR removed = 1;
DELETE FROM player_item_attributes WHERE player_item_id NOT IN (SELECT id FROM player_items);
DELETE FROM player_item_gems WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_item_stats WHERE player_item_id NOT IN (SELECT id FROM player_items);
DELETE FROM player_kills WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_luck_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_map_animes WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_map_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_pet_quests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_positions WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_pvp_quests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_quest_counters WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_rankeds WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_star_items WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_stats WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_store_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_time_quests WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_treasure_logs WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_tutorials WHERE player_id NOT IN (SELECT id FROM players);
DELETE FROM player_wanteds WHERE player_id NOT IN (SELECT id FROM players);

DELETE FROM private_messages WHERE from_id NOT IN (SELECT id FROM players) OR to_id NOT IN (SELECT id FROM players);

DELETE FROM guilds WHERE player_id NOT IN (SELECT id FROM users) OR removed = 1;
DELETE FROM guild_daily_quests WHERE guild_id NOT IN (SELECT id FROM guilds);
DELETE FROM guild_players WHERE guild_id NOT IN (SELECT id FROM guilds);
DELETE FROM guild_quest_counters WHERE guild_id NOT IN (SELECT id FROM guilds);
DELETE FROM guild_requests WHERE guild_id NOT IN (SELECT id FROM guilds);

update players set guild_id = 0 WHERE guild_id NOT IN (SELECT id FROM guilds);