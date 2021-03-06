<?php
$user			= false;
$player			= false;
$article		= false;
$with_battle	= false;
$is_profile		= false;

$language = Language::find($_SESSION['language_id']);
if (!$language) {
	$_SESSION['language_id'] = 1;
	$language = Language::find($_SESSION['language_id']);
}

if ($_SESSION['user_id']) {
	$user	= User::get_instance();
	if ($_SESSION['player_id']) {
		$player	= Player::get_instance();

		// Tras informações sobre a fidelidade
		$player_fidelity_topo = PlayerFidelity::find_first("player_id=".$player->id);

		// Verifica se está em batalha
		if ($player && ($player->battle_npc_id || $player->battle_pvp_id) && preg_match('/battle/', $controller)) {
			$with_battle	= true;
		}

		// Tras o nome dos ataques do anime/tema
		$equipments		= [];
		$techniques		= $player->character_theme()->attacks();
		foreach ($techniques as $technique) {
			$equipments[$technique->id]	= $technique->description()->name;
		}
	}
}

// Ta vendo um pefil?
if (preg_match('/profile/', $controller)) {
	$is_profile		= true;
}

// Verifica se o link é de noticia
if (preg_match('/read_news/', $action)) {
	$article_id = $params[0];
	$article = SiteNew::find_first('id = ' . $article_id);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0' name='viewport'>
	<link rel="shortcut icon" href="<?=image_url('favicon.ico');?>" type="image/x-icon" />

    <title><?=GAME_NAME;?> - Seja o Herói de nossa História</title>
    <meta name="description" content="<?=GAME_NAME;?> é o novo jogo para fãs de anime, em nosso jogo você será um dos personagens emblemáticos dos principais animes que fizeram e fazem parte de nossa vida." />
    <meta name="keywords" content="aasg, naruto, boruto, one, piece, cdz, anime, all, stars, game, jogo, online" />

	<!-- Facebook Dynamic OG Tags -->
	<?php if (!preg_match('/read_news/', $action)) { ?>
		<meta property="og:url" content="<?=make_url('/')?>" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?=GAME_NAME;?> - Seja o Herói de nossa História" />
		<meta property="og:description" content="<?=GAME_NAME;?> é o novo jogo para fãs de anime, em nosso jogo você será um dos personagens emblemáticos dos principais animes que fizeram e fazem parte de nossa vida." />
	<?php } else { ?>
		<meta property="og:url" content="<?=make_url('home#read_news/' . $article->id)?>" />
		<meta property="og:title" content="<?=$article->title;?>" />
		<meta property="og:description" content="<?=str_limit(strip_tags($article->description), 100, '');?>" />
		<meta property="og:type" content="article" />
		<meta property="article:author" content="<?=$article->user()->name;?>" />
		<meta property="article:section" content="<?=$article->type;?>" />
		<meta property="article:published_time" content="<?=$article->created_at;?>" />
	<?php } ?>

	<!-- Facebook OG Tags -->
	<meta property="og:image" itemprop="image" content="<?=image_url('social/cover.jpg');?>" />
	<meta property="og:locale" content="<?=str_replace('-', '_', $language->header);?>" />
	<meta property="fb:app_id" content="<?=FB_APP_ID;?>" />

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/bootstrap/css/bootstrap.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/select2/css/select2.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/select2/css/select2-bootstrap.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/animate/css/animate.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/typeahead/css/typeahead.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/bootstrap-tour/css/bootstrap-tour.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/font-awesome/css/font-awesome.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?=asset_url('libs/sweetalert2/css/sweetalert2.css');?>" />

	<!-- App CSS -->
	<link rel="stylesheet" type="text/css" href="<?=asset_url('css/layout.css');?>" />

	<!-- Plugins JS -->
	<script type="text/javascript" src="<?=asset_url('libs/jquery/js/jquery.js');?>"></script>
	<script type="text/javascript" src="<?=asset_url('libs/jquery/js/jquery.ui.js');?>"></script>
	<script type="text/javascript" src="<?=asset_url('libs/jquery/js/jquery.ui.touch-punch.min.js');?>"></script>
	<script type="text/javascript" src="<?=asset_url('libs/jquery/js/jquery.devrama.slider.js');?>"></script>
	<script type="text/javascript" src="<?=asset_url('libs/jquery/js/jquery.cookie.js');?>"></script>
    <script type="text/javascript" src="<?=asset_url('libs/socket.io/js/socket.io.js');?>"></script>
	<script type="text/javascript" src="https://unpkg.com/@metamask/detect-provider/dist/detect-provider.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/web3/3.0.0-rc.5/web3.min.js"></script>

	<!-- App JS -->
	<script type="text/javascript">
		var	_site_url				= "<?=SITE_URL;?>";
		var	_site_version			= "<?=GAME_VERSION;?>";

		var	_rewrite_enabled		= <?=(REWRITE_ENABLED ? 'true' : 'false');?>;
		var _language				= "<?=$language->header;?>";

		// NFT / MetaMask
		const haveWallet			= <?=($user && $user->wallet ? "'{$user->wallet}'" : 'null');?>;
		let currentAccount			= null;
		let currentChain			= null;
		const appState				= "<?=FW_ENV;?>";
		const tokenAddress			= {
			address:	"0xB508EC43B75e4869E247c19c2C05158d90f4f99D",
			token:		"AASG"
		};

		<?php if ($player) { ?>
			var _current_anime			= <?=$player->character()->anime_id;?>;
			var _current_graduation		= <?=$player->graduation()->sorting;?>;
			var _current_guild			= <?=$player->guild_id;?>;
			var _current_player			= <?=$player->id;?>;

			var _is_guild_leader		= <?=(($player->guild_id && $player->guild_id == $player->guild()->player_id) ? 'true' : 'false');?>;
			var _equipments_names		= <?=json_encode($equipments);?>;
			var _graduations			= [];

			var _chat_register			= "<?=$player->chatRegister();?>";
		<?php } ?>

		var	_check_pvp_queue		= <?=($player && $player->is_pvp_queued ? 'true': 'false');?>;

		// Servers
		var _chat_server			= "<?=CHAT_SERVER;?>";
		var _highlights_server		= "<?=HIGHLIGHTS_SERVER;?>";

		$(document).ready(function() {
        	// I18n.default_locale		= _language;
        	// I18n.translations		= <?=Lang::toJSON()?>;

		});
    </script>
	<script type="text/javascript" src="<?=asset_url('js/i18n.js');?>"></script>
	<script type="text/javascript" src="<?=asset_url('js/i18n.js');?>"></script>

	<style type="text/css">
		.grecaptcha-badge { z-index: 1; }
	</style>
	<?php if (FW_ENV != 'dev') { ?>
		<script data-ad-client="ca-pub-6665062829379662" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<?php } ?>
</head>
<body>
<div id="fb-root"></div>
<!-- Topo -->
<?php if (!$_SESSION['player_id']) { ?>
	<div id="background-topo">
		<div id="logo">
			<a href="<?=make_url();?>">
				<img src="<?=image_url('logo.png');?>" />
			</a>
		</div>
	</div>
<?php } else { ?>
	<div id="background-topo2" style="background-image: url(<?=image_url($player->character_theme()->header_image(true));?>)">
		<div class="bg" style="background-image: url(<?=image_url($player->character_theme()->header_image(true));?>)"></div>
		<div class="info">
			<?=top_exp_bar($player, $user);?>
		</div>
		<div class="menu">
			<?php if ($_SESSION['universal'] && $_SESSION['orig_user_id']) { ?>
				<a style="position: absolute; right: 160px; bottom: 135px" class="btn btn-xs btn-primary" href="<?=make_url('support#revert');?>">
					Reverter para usuário original
				</a>
			<?php } ?>
			<div class="values">
				<div class="life absolute"><span class="c"><?=highamount($player->for_life());?></span></div>
				<div class="mana absolute"><span class="c"><?=highamount($player->for_mana());?></span></div>
				<?php
				$staminaPercent = floor(($player->for_stamina() / $player->for_stamina(true)) * 100);
				if ($staminaPercent <= 33)								$staminaColor = "vermelho";
				else if ($staminaPercent > 34 && $staminaPercent <= 66)	$staminaColor = "laranja";
				else													$staminaColor = "verde";
				?>
				<div style="cursor: pointer;" class="stamina absolute requirement-popover" data-source="#tooltip-stamina" data-title="Recuperação de Stamina" data-trigger="hover" data-placement="bottom">
					<div id="tooltip-stamina" class="status-popover-container">
						<div class="status-popover-content">
							<div class="item-vip-list">
								<form id="vip-form-431" onsubmit="return false">
									<input type="hidden" name="id" value="431" />
									<button type="button" class="btn btn-primary btn-sm btn-block buy" data-id="431" style="margin-bottom: 5px;">
										<?=t('vips.restore_energy', [
											'amount'	=> 50,
											'price'		=> highamount(2000),
											'currency'	=> t('currencies.' . $player->character()->anime_id)
										]);?>
									</button>
								</form>
								<form id="vip-form-432" onsubmit="return false">
									<input type="hidden" name="id" value="432" />
									<button type="button" class="btn btn-primary btn-sm btn-block buy" data-id="432">
										<?=t('vips.restore_energy', [
											'amount'	=> 100,
											'price'		=> highamount(4),
											'currency'	=> t('currencies.credits')
										]);?>
									</button>
								</form>
							</div>
						</div>
					</div>
					<span class="c <?=$staminaColor;?>"><?=highamount($player->for_stamina());?></span>/<span class="m"><?=$player->for_stamina(true);?></span>
				</div>
				<div class="currency absolute"><?=highamount($player->currency);?></div>
				<div class="relogio absolute">
					<img style="cursor: help;" src="<?=image_url('icons/relogio.png');?>" class="requirement-popover" data-source="#tooltip-relogio" data-title="<?=t('popovers.titles.routines');?>" data-trigger="hover" data-placement="bottom" />
					<div id="tooltip-relogio" class="status-popover-container">
						<div class="status-popover-content">
							<?=t('popovers.description.routines', [
								'mana' => t('formula.for_mana.' . $player->character_theme()->anime()->id)
							]);?>
						</div>
					</div>
				</div>
				<div class="gift absolute">
					<?php if (!$player_fidelity_topo->reward) { ?>
						<a href="<?=make_url('events#fidelity')?>" class="badge <?=(!$player_fidelity_topo->reward ? 'pulsate_icons' : '');?>">
							<i class="fa fa-exclamation fa-fw"></i>
						</a>
					<?php } ?>
					<a href="<?=make_url('events#fidelity')?>">
						<img src="<?=image_url('icons/' . ($player_fidelity_topo->reward ? 'gift-off.png' : 'gift-on.png'));?>" class="requirement-popover" data-source="#tooltip-gift" data-title="<?=t('fidelity.topo_title');?>" data-trigger="hover" data-placement="bottom" />
					</a>
					<div id="tooltip-gift" class="status-popover-container">
						<div class="status-popover-content">
							<?=($player_fidelity_topo->reward ? t('fidelity.topo_description2') : t('fidelity.topo_description', [
									'link' => make_url('events#fidelity')
							]));?>
						</div>
					</div>
				</div>
				<div class="queue absolute">
					<?php $rankedOpen = Ranked::isOpen(); ?>
					<?php if ($rankedOpen) { ?>
						<a href="<?=make_url('battle_pvps')?>" class="badge <?=($rankedOpen ? 'pulsate_icons' : '');?>">
							<i class="fa fa-exclamation fa-fw"></i>
						</a>
					<?php } ?>
					<a href="<?=make_url('battle_pvps')?>">
						<img src="<?=image_url('icons/queue-' . ($player->is_pvp_queued ? 'on' : 'off') . '.png');?>" class="requirement-popover" data-source="#tooltip-queue" data-title="<?=t('popovers.titles.queue' . ($rankedOpen ? '_ranked' : ''));?>" data-trigger="hover" data-placement="bottom" />
					</a>
					<?php $queueInfo = BattleQueue::info(); ?>
					<div id="tooltip-queue" class="status-popover-container">
						<div id="tooltip-queue-data" class="status-popover-content">
							<?=t('popovers.description.queue.' . ($player->is_pvp_queued ? 'queued' : 'normal'), [
								'waiting_time'	=> format_time($queueInfo['estimatedTime'])['string'],
								'players'		=> highamount($queueInfo['queueCount'])
							]);?>
							<?php if (is_menu_accessible(Menu::find(54), $player)) { ?>
								<hr />
								<div align="center">
									<?php if ($player->is_pvp_queued) { ?>
										<a href="javascript:;" class="btn btn-sm btn-block btn-danger">Sair da fila</a>
									<?php } else { ?>
										<a href="javascript:;" class="btn btn-sm btn-block btn-primary">Entrar na fila</a>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="mensagem absolute">
					<?php
					$newMessages	= sizeof(PrivateMessage::find('removed=0 AND to_id=' . $player->id . ' AND read_at IS NULL'));
					if ($newMessages) {
					?>
						<a href="<?=make_url('private_messages');?>" class="badge <?=($newMessages ? 'pulsate_icons' : '');?>">
							<i class="fa fa-exclamation fa-fw"></i>
						</a>
					<?php } ?>
					<a href="<?=make_url('private_messages');?>"><img src="<?=image_url('icons/email.png');?>" /></a>
				</div>
				<div class="friend absolute">
					<?php
					$friendRequests	= sizeof(PlayerFriendRequest::find('friend_id=' . $player->id));
					if ($friendRequests) {
					?>
						<a href="<?=make_url('friend_lists#search');?>" class="badge <?=($friendRequests ? 'pulsate_icons' : '');?>">
							<i class="fa fa-exclamation fa-fw"></i>
						</a>
					<?php } ?>
					<a href="<?=make_url('friend_lists#search')?>"><img src="<?=image_url('icons/friend.png');?>" /></a>
				</div>
				<div class="vip absolute">
					<a href="<?=make_url('vips');?>">
						<img src="<?=image_url('icons/vip-' . ($user->vip ? 'on' : 'off') . '.png');?>" class="requirement-popover" data-source="#tooltip-vip" data-title="<?=t('popovers.titles.credits');?>" data-trigger="hover" data-placement="bottom" />
					</a>
					<div id="tooltip-vip" class="status-popover-container">
						<div class="status-popover-content">
							Você possui <b><?=highamount($user->credits);?></b> estrelas.
						</div>
					</div>
				</div>
				<div class="logout absolute">
					<a href="<?=make_url('users#logout');?>" name="Logout" title="Logout">
						<img src="<?=image_url('icons/logout.png');?>" border="0" alt="Logout" />
					</a>
				</div>
			</div>
			<div class="menu-content">
				<ul>
					<?php global $raw_menu_data; ?>
					<?php foreach ($raw_menu_data as $menu_category) { ?>
						<li class="hoverable">
							<img src="<?=image_url('menu-icons/' . $menu_category['id'] . (!sizeof($menu_category['menus']) ? '-D' : '') . '.png');?>">
							<span><?=t($menu_category['name']);?></span>
							<?php if (sizeof($menu_category['menus'])) { ?>
								<ul>
									<?php foreach ($menu_category['menus'] as $menu) { ?>
										<li><a href="<?=$menu['href'];?>" <?=($menu['external'] ? 'target="_blank"' : '');?>><?=t($menu['name']);?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
						</li>
					<?php } ?>
					<li id="inventory-trigger" data-text="<?=t('global.wait');?>">
						<img src="<?=image_url('menu-icons/10.png');?>">
						<span><?=t('menus.inventory');?></span>
						<ul id="inventory-container"></ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="cloud"></div>
	</div>
<?php } ?>
<!-- Topo -->

<!-- Conteúdo -->
<div id="conteudo" class="<?=($player ? 'with-player' : '');?> <?=($with_battle ? 'with-battle' : '');?>">
	<div id="pagina">
		<div id="colunas">
			<?php if (!$player || !$with_battle) { ?>
				<div id="esquerda" class="<?=($player ? 'with-player' : '');?>">
					<?php if ($player) { ?>
						<?php if (!$player->map_id && !$is_profile) { ?>
							<?=partial('shared/left_character', [
								'user'		=> $user,
								'player'	=> $player
							]);?>
						<?php } else { ?>
							<div style="height: 680px;"></div>
						<?php } ?>
						<div style="clear:both; float: left"></div>
					<?php } else { ?>
						<div id="menu">
							<?php if (!$_SESSION['loggedin']) { ?>
								<div id="login" style="background-image:url('<?=image_url('bg-login.png');?>');">
									<div id="form-login">
										<form method="post" onsubmit="return false">
											<input type="email" name="email" placeholder="Digite seu Email" class="in-login" />
											<input type="password" name="password" placeholder="Digite sua Senha" class="in-senha" />
											<div style="width: 114px; margin: 0 auto; margin-top: 28px;">
												<a href="<?=make_url('users/reset_password');?>" style="float: left; margin: 0 2px;">
													<img src="<?=image_url('buttons/bt-senha.png');?>" data-toggle="tooltip" title="<?=make_tooltip('Esqueci minha Senha', 125);?>" />
												</a>
												<button type="submit" class="play-button g-recaptcha" data-sitekey="<?=$recaptcha['site'];?>" data-callback="doLogin"></button>
												<a href="<?=$fb_url;?>" style="float: left; margin: 0 2px;">
													<img src="<?=image_url('buttons/bt-face.png');?>" data-toggle="tooltip" title="<?=make_tooltip('Entrar com Facebook', 125);?>" />
												</a>
												<div class="break"></div>
											</div>
										</form>
									</div>
								</div>
							<?php } else { ?>
								<?php if ($_SESSION['player_id']) { ?>
									<div id="login" style="background-image:url('<?=image_url('bg-login-' . $player->character()->anime_id . '.png');?>');">
									</div>
								<?php } else { ?>
									<div id="login" style="background-image:url('<?=image_url('bg-login-0.png');?>');">
										<div id="form-login">
											<div style="left: -7px; position: relative; top: 7px; min-height: 48px;">
												<div class="fb-like" data-href="http://www.facebook.com/<?=FB_PAGE_USER;?>" data-send="false" data-layout="box_count" data-width="70" data-show-faces="false"></div>
											</div>
											<div style="position: relative; top: 25px; left: -7px;">
												<b><span style="color: #ede4af"><?=highamount($user->credits)?></span><br /> Estrelas</b>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<div id="menu-conteudo">
								<div id="menu-topo"></div>
								<div id="menu-repete">
									<?php
									global $menu_data;
									foreach ($menu_data as $menu_category) {
										if (sizeof($menu_category['menus'])) {
											echo '<img src="' . image_url('menus/' . $_SESSION['language_id'] . '/' . $menu_category['id'] . '_' . ($player ? $player->character()->anime_id : rand(1, 6)) . '.png') . '" />';
											foreach ($menu_category['menus'] as $menu) {
												if ($menu['hidden']) continue;

												echo '<li><a href="' . $menu['href'] . '" ' . ($menu['external'] ? 'target="_blank"' : '') . '>' . t($menu['name']) . '</a></li>';
											}
										}
									}
									?>
									<div class="clearfix"></div>
								</div>
								<div id="menu-fim"></div>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<div id="direita" class="<?=($player ? 'with-player' : '');?>">
				@yield
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php if (!$with_battle && $player) { ?>
			<div class="esquerda-gradient" ></div>
		<?php } ?>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>
<?=partial('shared/footer', ['player' => $player]);?>

<?php if (FW_ENV != 'dev') { ?>
	<div style="display: none;">
		<script id="_wau38g">var _wau = _wau || []; _wau.push(["dynamic", "j0ycq84tlk", "38g", "c4302bffffff", "small"]);</script>
		<script async src="//waust.at/d.js"></script>
	</div>
<?php } ?>

<div class="box-cookies hide">
	<p class="msg-cookies">Este site usa cookies para garantir que você obtenha a melhor experiência.</p>
	<button class="btn btn-primary btn-cookies">Aceitar!</button>
</div>

<?php if ($player) { ?>
	<?=partial('shared/chat', ['player' => $player]);?>
	<script type="text/javascript" src="<?=asset_url('js/highlights.js');?>"></script>
<?php } ?>

<!-- Plugins JS -->
<script type="text/javascript" src="<?=asset_url('libs/bootstrap/js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('libs/select2/js/select2.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('libs/bootstrap-tour/js/bootstrap-tour.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('libs/typeahead/js/typeahead.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('libs/bootbox/js/bootbox.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('libs/sweetalert2/js/sweetalert2.min.js');?>"></script>

<!-- App JS -->
<script type="text/javascript" src="<?=asset_url('js/global.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/users.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/characters.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/friends.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/graduations.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/achievement.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/techniques.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/trainings.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/shop.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/luck.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/reset_password.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/talents.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/inventory.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/battles.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/battle_npcs.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/battle_pvps.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/hospital.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/rankings.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/home.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/support.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/quests.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/equipments.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/private_messages.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/pets.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/events.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/history_mode.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/challenge.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/maps.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/guilds.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/vips.js');?>"></script>
<script type="text/javascript" src="<?=asset_url('js/ranked.js');?>"></script>
<!-- <script type="text/javascript" src="<?=asset_url('js/blockadblock.js');?>"></script> -->
<!-- <script type="text/javascript" src="<?=asset_url('js/metamask.js');?>"></script> -->

<!-- External Plugins -->
<?php if (FW_ENV != 'dev') { ?>
	<script type="text/javascript" src="//www.google.com/recaptcha/api.js" async defer></script>
	<script async defer crossorigin="anonymous"
		src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1
				&version=v10.0&appId=<?=FB_APP_ID;?>&autoLogAppEvents=1"
		nonce="z3ba4zPG">
	</script>
<?php } ?>

<?php
// if ($player) {
// 	$redis = new Redis();
// 	$redis->pconnect(REDIS_SERVER);
// 	$redis->auth(REDIS_PASS);
// 	$redis->select(REDIS_DATABASE);

// 	$have_queue	= FALSE;
// 	$queues		= $redis->lRange("aasg_od_invites", 0, -1);
// 	foreach ($queues as $queue) {
// 		$targets = array_unique(
// 			array_merge(
// 				$redis->lRange("od_targets_" . $queue, 0, -1),
// 				$redis->lRange("od_accepts_" . $queue, 0, -1)
// 			)
// 		);

// 		if (in_array($player->id, $targets)) {
// 			$have_queue				= true;
// 			$guild_dungeon	= $redis->get("od_id_" . $queue);
// 			break;
// 		}
// 	}
// 	if ($have_queue) {
// 		echo '<script type="text/javascript">
// 			createInviteModal(' . $guild_dungeon . ');
// 		</script>';
// 	}
// }
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2({
			theme: "bootstrap",
		});
	});
</script>
</body>
</html>
