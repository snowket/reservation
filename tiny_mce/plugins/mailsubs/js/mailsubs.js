tinyMCEPopup.requireLangPack();

var MailsubsDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(title) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

		tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
			src : tinyMCEPopup.getWindowArg('plugin_url') + '/img/dot.gif',
			alt : title,
			width: 40,
			height: 15,
			title : title,
			border : '1',
			style: 'background: #ccc; vertical-align:middle'
		}));

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(MailsubsDialog.init, MailsubsDialog);
