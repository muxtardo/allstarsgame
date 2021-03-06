<?php
require '_config.php';

Recordset::query('TRUNCATE TABLE ranking_challenges;');

$factions		= Recordset::query('SELECT id FROM factions WHERE active = 1');
$challenges	= Recordset::query('SELECT id FROM challenges WHERE active=1');
foreach ($challenges->result_array() as $challenge) {
    foreach ($factions->result_array() as $faction) {
        $players	= Recordset::query('
            SELECT
                a.id,
                a.name,
                a.headline_id,
                a.graduation_id,
                c.anime_id,
                a.character_theme_id,
                a.faction_id,
                a.level,
                d.sorting AS graduation_level,
                MAX(e.quantity) as quantity,
                e.challenge_id

            FROM
                players a JOIN character_themes b ON b.id=a.character_theme_id
                JOIN characters c ON c.id=a.character_id
                JOIN graduations d ON d.id=a.graduation_id
                JOIN player_challenges e ON e.player_id=a.id

            WHERE
                a.faction_id=' . $faction['id'].' AND a.banned = 0 AND e.challenge_id='.$challenge['id'].'
                GROUP BY a.id
        ');
        foreach($players->result_array() as $player) {
            Recordset::insert('ranking_challenges', [
                'player_id'				=> $player['id'],
                'anime_id'				=> $player['anime_id'],
                'character_theme_id'	=> $player['character_theme_id'],
                'graduation_id'			=> $player['graduation_id'],
                'headline_id'			=> $player['headline_id'],
                'faction_id'			=> $player['faction_id'],
                'name'					=> $player['name'],
                'level'					=> $player['level'],
                'score'					=> $player['quantity'],
                'challenge_id'			=> $player['challenge_id']
            ]);
        }

        $position	= 1;
        $players	= Recordset::query('SELECT id, score FROM ranking_challenges WHERE challenge_id = ' . $challenge['id'] . ' AND faction_id = ' . $faction['id'] . '  ORDER BY `score` DESC, `level` DESC');
        foreach($players->result_array() as $player) {
			Recordset::update('ranking_challenges', [
				'position_faction'	=> $position++
			], [
				'id'				=> $player['id']
			]);
        }

        $position	= 1;
        $players	= Recordset::query('SELECT id, score FROM ranking_challenges WHERE challenge_id = ' . $challenge['id'] . '  ORDER BY `score` DESC, `level` DESC');
        foreach($players->result_array() as $player) {
			Recordset::update('ranking_challenges', [
				'position_general'	=> $position++
			], [
				'id'				=> $player['id']
			]);
        }
    }
}
echo "[Ranking Challenges] Cron executada com sucesso!\n";
