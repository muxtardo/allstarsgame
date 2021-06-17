<div id="chat">
	<div class="title">
        Chat All-Stars
	</div>
    <div class="messages">
        <div class="wait">Conectando...</div>
    </div>
    <div class="selector">
        <ul>
            <li data-channel="world" data-cmd="w">Mundo</li>
            <li data-channel="faction" data-cmd="f"><?=$player->faction()->description()->name;?></li>
            <?php if ($player->guild_id): ?>
                <li data-channel="guild" data-cmd="g">Organização</li>
            <?php endif; ?>
            <?php if ($player->battle_pvp_id): ?>
                <li data-channel="battle" data-cmd="b">Batalha</li>
            <?php endif; ?>
            <?php if ($_SESSION['universal']): ?>
                <li data-channel="system" data-cmd="s">Sistema</li>
            <?php endif; ?>
        </ul>
        <div class="selector-trigger">Mundo</div>
        <input type="text" id="message" autocomplete="off" name="message" <?php if (!$_SESSION['universal']) { ?>maxlength="60"<?php } ?> />
        <input type="checkbox" id="as" checked="checked" class="auto-scroll" />
    </div>
</div>
<?php
$color	= '';
switch ($player->faction_id) {
	case 1:		$color	= '#2c8ee5';	break;
	case 2:		$color	= '#f1270f';	break;
	case 3:		$color	= '#f4ffff';	break;
}

$icon_img	= image_url('factions/icons/big/' . $player->faction_id . '.png');
$icon_name	= $player->faction()->description()->name;
$icon		= '<img style="width: 16px; vertical-align: -5px;" src="' . $icon_img . '" title="' . $icon_name . '" /> ';

if ($_SESSION['universal']) {			// Admin
	$color	= '#ffb34f';
	$icon	= '<span class="fa fa-star fa-fw" style="vertical-align: -1px;"></span> ';
} elseif (in_array($player->id, [])) {	// Mod
	$color	= '#1abc9c';
	$icon	= '<span class="fa fa-star fa-fw" style="vertical-align: -1px;"></span> ';
}

$guild          = $player->guild();
$chat_data	    = [
    'uid'           => (int)$player->id,
	'user_id'       => (int)$player->user_id,
	'faction'       => (int)$player->faction_id,
	'guild'         => (int)$player->guild_id,
	'guild_owner'   => $guild ? $player->id == $guild->leader()->id : FALSE,
	'battle'        => (int)$player->battle_pvp_id,
	'gm'            => $_SESSION['universal'],
	'color'         => $color,
	'icon'          => $icon,
	'name'          => $player->name
];

$key            = CHAT_KEY;
$iv             = substr($key, 0, 16);
$registration   = openssl_encrypt(json_encode($chat_data), 'AES-256-CBC', $key, 0, $iv);
?>
<script type="text/javascript">
	(function () {
		var __chat_socket	= io.connect('<?=CHAT_SERVER;?>');
		var has_type		= false;
		var	channel			= 'world';
		var	real_channel	= 'world';
		var	pvt_dest		= 0;
		var	last_pvt_index	= 0;
		var	trigger_pvt		= false;
		var pvt_data		= null;
		var last_msg		= null;
		var blocked			= [];
		var pm_total		= 0;
		var chat_max_length	= 120;

		window.chat_embeds	= [];
		window.chat_decoded	= [];

		function resize_selector() {
			var	tw	= $('#chat .selector-trigger').outerWidth() + 15;
			$('#chat input[name=message]').css({
				paddingLeft: tw
			});
		}

		function diff_in_secs(d1, d2) {
			var diff		= d2 - d1,
				sign		= diff < 0 ? -1 : 1,
				milliseconds,
				seconds,
				minutes,
				hours,
				days;

			diff	/= sign;
			diff	= (diff-(milliseconds=diff%1000))/1000;
			diff	= (diff-(seconds=diff%60))/60;
			diff	= (diff-(minutes=diff%60))/60;
			days	= (diff-(hours=diff%24))/24;

			return seconds;
		}

		__chat_socket.on('error', function () {
			$('#chat .messages').html(
				'<div style="padding: 10px">Ocorreu um problema ao conectar ao chat.<br /><br />Você pode ter algum firewall(isso inclui programas anti-hack de jogos on-line)' +
				' ou anti-vírus bloqueando o chat.<br /><br />Se sua rede está conectada através de um proxy, o proxy pode estar bloqueando as conexões ou não suporta conexões via websocket</div>'
			);
		});

		__chat_socket.on('connect', function () {
			__chat_socket.emit('register', {
                data: '<?=$registration;?>'
			});

			$('#chat .messages .wait').remove();
		});

		__chat_socket.on('blocked-broadcast', function (data) {
			blocked	= data;
		});
		__chat_socket.on('pvt-broadcast', function (data) {
			var	container	= $('.chat-pvt .r');

			pvt_data	= data;

			if (!container.length) {
				if (!data.length) {
					return;
				}

				var	l	= $(document.createElement('DIV')).addClass('l');
				var	r	= $(document.createElement('DIV')).addClass('r');
				var	c	= $(document.createElement('DIV')).addClass('chat-pvt');

				c.append(l, r);
				container	= r;

				$(document.body).append(c);

				c.on('click', function () {
					function dispatch_pvt_read(id) {
						__chat_socket.emit('pvt-was-read', {index: id});
					}

					if (this.shown) {
						$('.reply-box').remove();
						this.shown	= false;

						return;
					}

					this.shown			= true;
					var	_this			= this;

					var	msg_container	= $(document.createElement('DIV')).addClass('reply-box');
					var	msg_reply		= $(document.createElement('A')).html('Responder').addClass('reply');
					var	msg_next		= $(document.createElement('A')).html(pvt_data.length == 1 ? 'Fechar' : 'Próxima').addClass(pvt_data.length == 1 ? 'close-pv' : 'next');
					var	msg_from		= $(document.createElement('SPAN')).html(pvt_data[0].from).addClass('from');
					var	msg_text		= $(document.createElement('SPAN')).html(pvt_data[0].message).addClass('text');

					msg_reply.on('click', function () {
						$('#chat .selector-trigger')
							.html(pvt_data[0].from)[0].shown = false;

						channel		= 'private';
						pvt_dest	= pvt_data[0].id;

						$('#chat input[name=message]').focus();
						resize_selector();
					});

					if (pvt_data.length == 1) {
						msg_next.on('click', function () {
							dispatch_pvt_read(last_pvt_index);

							msg_container.remove();
							c.remove();
						});
					} else {
						msg_next.on('click', function () {
							msg_reply.remove();
							msg_next.remove();
							msg_from.remove();
							msg_text.remove();

							// No próximo broadcast ele recarrega =)
							trigger_pvt	= true;
							_this.shown	= false;

							msg_container.append('<div class="wait">Aguarde...</div>');

							dispatch_pvt_read(last_pvt_index);
							pm_total--;
						});
					}


					msg_container.append(msg_from, msg_text, msg_reply, msg_next);
					$(document.body).append(msg_container);
				});
			}

			container.html(data.length);

            if (data.length > pm_total) {
				pm_total	= data.length;
				$(document.body).append('<audio autoplay><source src="' + resource_url('media/pm.mp3') + '" type="audio/mp3" /></audio>');
			}

			if (!data.length && container.length) {
				pm_total	= 0;

				container.remove();
			}

			last_pvt_index	= data[0].index;

			if (trigger_pvt) {
				$('.reply-box').remove();

				if (data.length) {
					container.trigger('click');
				}

				trigger_pvt	= false;
			}

			if (!parseInt($.cookie('chat_show'))) {
				$('.chat-pvt').css({bottom: 30});
			} else {
				$('.chat-pvt').css({bottom: 340});
			}
		});

		__chat_socket.on('broadcast', function (data) {
			if (data.decodes) {
				data.decodes.forEach(function (decoded) {
					chat_decoded[decoded[0]]	= decoded[1];
				});
			}

			if (data.channel == 'system' || data.channel == 'warn') {
				$('#chat .messages').append('<div class="chat-message chat-' + data.channel + '"><div>Aviso de sistema</div><div>' + data.message + '</div></div>')

				return;
			}

			// GLobal user block -->
				var is_blocked	= false;

				blocked.forEach(function (id) {
					if (parseInt(data.user_id) == parseInt(id)) {
						is_blocked	= true;
					}
				});

				if (is_blocked) {
					return;
				}
			// <--

            if (data.gm && data.channel != 'world') {
                data.icon = '<span class="fa fa-star fa-fw"></span> ';
            }

            $('#chat .messages').append(
				'<div class="chat-message chat-' + data.channel + '' + (data.gm ? ' chat-gm' : '') + '" ' + (!(data.channel == real_channel) ? 'style="display: none;"' : '') + '>' +
                    '<span ' + (data.color ? 'style="color: ' + data.color + '!important"' : '') + ' class="chat-user" data-id="' + data.id + '" data-from="' + data.from + '">' +
                        (data.icon || '') + data.from +
                    ':</span>' +
                    '<span>' + data.message + '</span>' +
                '</div>');

			$('#chat .messages .chat-user').each(function() {
				if (this.with_callback) {
					return;
				}

				this.with_callback	= true;
				var	_				= $(this);

				_.on('click', function() {
					if (channel == 'block') {
						$('#chat input[name=message]').val($(this).data('from')).focus();

						return;
					}

					$('#chat .selector-trigger')
						.html(this.innerHTML)[0].shown = false;

					$('#chat .selector ul').animate({opacity: 0}, function () {
						$(this).hide()
					});

					channel		= 'private';
					pvt_dest	= _.data('id');

					$('#chat input[name=message]').focus();
					resize_selector();
				});
			});

			if (has_type) {
				$('#chat .messages').scrollTop(1000000);

				has_type	= false;
			}
			if ($('#chat .auto-scroll:checked').length) {
				$('#chat .messages').scrollTop(1000000);
			}
		});

        $(document).ready(function(e) {
			$('#chat input[name=message]').on('keyup', function(e) {
				var	message	= $(this);

				if (e.keyCode == 13 && this.value) {
					<?php if (!$_SESSION['universal']) { ?>
						var now	= new Date();
						if (last_msg && diff_in_secs(last_msg, now) < 10) {
							return;
						}
					<?php } ?>

					var broadcast_data	= {
						message:	this.value,
						channel:	channel,
						dest:		pvt_dest,
						embeds: 	chat_embeds
					};

					__chat_socket.emit('message', broadcast_data);

					this.value	= '';
					chat_embeds	= [];
					has_type	= true;

					if (channel == 'private') {
						$('#chat .selector ul li').each(function () {
							if ($(this).data('channel') == real_channel) {
								$(this).trigger('click');
							}
						});
					} else {
                        <?php if (!$_SESSION['universal']) { ?>
                            message.attr('disabled', 'disabled');
                            last_msg	= now;
                            var _iv1	= setInterval(function () {
                                var	now	= new Date();

                                if (last_msg && diff_in_secs(last_msg, now) < 10) {
                                    message.attr('placeholder', 'Aguarde ' + (10 - diff_in_secs(last_msg, now)) + ' segundo(s)');
                                } else {
                                    message.removeAttr('disabled').attr('placeholder', '');
                                    clearInterval(_iv1);
                                }
                            }, 1000);
                        <?php } ?>
                    }
				}

				if (e.keyCode == 32) {
					$('#chat .selector ul li').each(function () {
						var _	= $(this);

						if (message.val().match(new RegExp('\^/' + _.data('cmd')))) {
							_.trigger('click');

							message.val('');
						}
					});

					if (message.val().match(/^@[^\s]+/)) {
						var	dest	= message.val().replace(/[@<>]/img, '');

						$('#chat .selector-trigger').html(dest)[0].shown = false;

						message.val('');

						channel		= 'private';
						pvt_dest	= dest;

						resize_selector();
					}

					if (message.val().match('^/block')) {
						$('#chat .selector-trigger').html('Bloquear')[0].shown = false;

						channel	= 'block';

						message.val('');
						resize_selector();
					}
				}
			}).on('focus', function () {
				$('#chat #as').stop().animate({opacity: 0});
			}).on('blur', function () {
				$('#chat #as').stop().animate({opacity: 1});
			}).on('pvt-switch', function (e, dest) {
				$('#chat .selector-trigger').html(dest)[0].shown = false;

				channel		= 'private';
				pvt_dest	= dest;

				resize_selector();
			});

			$('#chat .selector ul li').on('click', function () {
				$('#chat .selector-trigger').html(this.innerHTML)[0].shown = false;
				$('#chat .selector ul').animate({opacity: 0}, function () {
					$(this).hide()
				});

				channel			= $(this).data('channel');
				real_channel	= channel;

				<?php if (!$_SESSION['universal']) { ?>
                	$('#chat #message').attr('maxlength', chat_max_length);
				<?php } ?>

				$('#chat .messages .chat-message').hide();

				$.cookie('chat_channel', channel);

				$('#chat .messages .chat-' + channel).show();
				$('#chat .messages .chat-warn').show();
				$('#chat .messages .chat-system').show();

				$('#chat input[name=message]').focus();
				resize_selector();
			});

			$('#chat .selector-trigger').on('click', function () {
				if (!this.shown) {
					$('#chat .selector ul').show().animate({opacity: 1});

					this.shown	= true;
				} else {
					$('#chat .selector ul').animate({opacity: 0}, function () { $(this).hide() });

					this.shown	= false;
				}
			});

            if ($.cookie('chat_channel')) {
				var	current		= $.cookie('chat_channel');
				var was_found	= false;

				$('#chat .selector ul li').each(function () {
					var _		= $(this);

					if (_.data('channel') == current) {
						_.trigger('click');

						was_found	= true;
					}
				});

				if (!was_found) {
					$('#chat .selector ul li').each(function () {
						var _		= $(this);

						if (_.data('channel') == 'world') {
							_.trigger('click');
						}
					});
				}
			} else {
				$('#chat .selector ul li').each(function () {
					var _		= $(this);

					if (_.data('channel') == 'world') {
						_.trigger('click');
					}
				});
			}
		});

		var	__pvt_iv	= setInterval(function() {
			__chat_socket.emit('pvt-query');
		}, 2000)

		var	__block_iv	= setInterval(function() {
			__chat_socket.emit('blocked-query');
		}, 2000)

		$('#chat .title').on('click', function () {
			if (parseInt($.cookie('chat_show'))) {
				$('#chat').animate({height: 35});
				$('.chat-pvt').animate({bottom: 30});

				$.cookie('chat_show', 0);
			} else {
				$('#chat').animate({height: 350});
				$('.chat-pvt').animate({bottom: 340});

				$.cookie('chat_show', 1);
			}

			resize_selector();
		});

		$('#chat .auto-scroll').on('click', function () {
			if (this.checked) {
				$.cookie('chat_as', 1);
			} else {
				$.cookie('chat_as', 0);
			}
		});

		if (!parseInt($.cookie('chat_show'))) {
			$('#chat').css('height', 35);
		}

		if (parseInt($.cookie('chat_as'))) {
			$('#chat .auto-scroll')[0].checked = true;
		}
	})();
</script>