/* global OC, OCP, OCA, $, _, Handlebars */

(function(OC, OCP, OCA, $, _, Handlebars) {
	'use strict';

	OCA.VideoCalls = OCA.VideoCalls || {};
	OCA.VideoCalls.Admin = OCA.VideoCalls.Admin || {};
	OCA.VideoCalls.Admin.StunServer = {

		TEMPLATE: '<div class="stun-server">' +
		'	<input type="text" name="stun_server" placeholder="stunserver:port" value="{{server}}" />' +
		'	<a class="icon icon-delete" title="' + t('spreed', 'Delete server') + '"></a>' +
		'	<a class="icon icon-add" title="' + t('spreed', 'Add new server') + '"></a>' +
		'	<span class="icon icon-checkmark-color hidden" title="' + t('spreed', 'Saved') + '"></span>' +
		'</div>',
		$list: undefined,
		template: undefined,

		init: function() {
			this.template = Handlebars.compile(this.TEMPLATE);
			this.$list = $('div.stun-servers');
			this.renderList();
		},

		renderList: function() {
			var servers = this.$list.data('servers');

			_.each(servers, function(server) {
				this.$list.append(
					this.renderServer(server)
				);
			}.bind(this));

			if (servers.length === 0) {
				this.addNewTemplate('stun.nextcloud.com:443');
			}
		},

		addNewTemplate: function(server) {
			server = _.isString(server) ? server : '';
			var $server = this.renderServer(server);
			this.$list.append($server);
			return $server;
		},

		deleteServer: function(e) {
			e.stopPropagation();

			var $server = $(e.currentTarget).parents('div.stun-server').first();
			$server.remove();

			this.saveServers();

			if (this.$list.find('div.stun-server').length === 0) {
				OC.Notification.showTemporary(t('spreed', 'You deleted all STUN servers. As it is almost always needed, a default STUN server was added.'));
				var $newServer = this.addNewTemplate('stun.nextcloud.com:443');
				this.temporaryShowSuccess($newServer);
			}

		},

		saveServers: function() {
			var servers = [],
				$error = [],
				$success = [],
				self = this;

			this.$list.find('input').removeClass('error');
			this.$list.find('.icon-checkmark-color').addClass('hidden');

			this.$list.find('input').each(function() {
				var server = this.value;
				
				// Remove HTTP or HTTPS protocol, if provided
				if (server.startsWith('https://')) {
					server = server.substr(8);
				} else if (server.startsWith('http://')) {
					server = server.substr(7);
				}
				
				var parts = server.split(':');
				
				if (parts.length !== 2) {
					$(this).addClass('error');
				} else {
					if (parts[1].match(/^([1-9]\d{0,4})$/) === null ||
						parseInt(parts[1]) > Math.pow(2, 16)) { //65536
						$error.push($(this));
					} else {
						servers.push(this.value);
						$success.push($(this).parent('div.stun-server'));
					}
				}
			});

			OCP.AppConfig.setValue('spreed', 'stun_servers', JSON.stringify(servers), {
				success: function() {
					_.each($error, function($server) {
						$server.addClass('error');
					});
					_.each($success, function($server) {
						self.temporaryShowSuccess($server);
					});
				}
			});
		},

		temporaryShowSuccess: function($server) {
			var $icon = $server.find('.icon-checkmark-color');
			$icon.removeClass('hidden');
			setTimeout(function() {
				$icon.addClass('hidden');
			}, 2000);
		},

		renderServer: function(server) {
			var $template = $(this.template({
				server: server
			}));

			$template.find('a.icon-add').on('click', this.addNewTemplate.bind(this));
			$template.find('a.icon-delete').on('click', this.deleteServer.bind(this));
			$template.find('input').on('change', this.saveServers.bind(this));

			return $template;
		}

	};


})(OC, OCP, OCA, $, _, Handlebars);

$(document).ready(function(){
	OCA.VideoCalls.Admin.StunServer.init();
});
