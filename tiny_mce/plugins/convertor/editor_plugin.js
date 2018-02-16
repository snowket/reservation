/**
 * $Id: editor_plugin_src.js 42 2006-08-08 14:32:24Z spocke $
 *
 * @author Pro-Service
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
    tinymce.PluginManager.requireLangPack('convertor');
	tinymce.create('tinymce.plugins.ConvertorPlugin', {
	
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceConvertor', function() {
				ed.windowManager.open({
					file : url + '/convertor.htm',
					width :  500,
					height : 300,
					inline : 1
				}, {
					plugin_url : url
				});
            });
			// Register buttons
			ed.addButton('convertor', {title : 'convertor.desc',cmd : 'mceConvertor',image : url + '/img/convertor.gif'});		
		},

		getInfo : function() {
			return {
				longname : 'convertor',
				author : 'Proservice.ge',
				authorurl : 'http://www.proservice.ge',
				infourl : '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('convertor', tinymce.plugins.ConvertorPlugin);
})();