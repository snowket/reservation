/**
 * @author Pro-Service
*/

(function() {
	tinymce.create('tinymce.plugins.MailsubsPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceMailsubs', function() {
				ed.windowManager.open({
					file : url + '/mailsubs.php',
					width : 450 + parseInt(ed.getLang('mailsubs.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('mailsubs.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('mailsubs', {title : 'mailsubs.desc', cmd : 'mceMailsubs',image : url + '/img/mailsubs.gif',width:25});
		},

		getInfo : function() {
			return {
				longname :  'Mailsubs',
				author :    'Pro-Service',
				authorurl : 'http://proservice.ge',
				infourl :   '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('mailsubs', tinymce.plugins.MailsubsPlugin);
})();