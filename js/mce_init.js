tinyMCE.init({
	// General options
    mode : "specific_textareas",
    editor_selector : "mceEditor",
	theme : "advanced",
	plugins : "safari,pagebreak,style,table,advhr,advimage,filemanager,imagemanager,advlink,emotions,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,template,convertor",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,fullscreen",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,convertor,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|preview,|,forecolor,backcolor,styleprops",
	theme_advanced_buttons3 : "tablecontrols,|,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,template,pagebreak",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false,

    imagemanager_handle: 'image', 
    filemanager_handle: 'file,media', 

	// Example content CSS (should be your site CSS)
	content_css : "../css/style_pcms.css",

    // Drop lists for link/image/media/template dialogs
	template_external_list_url : "tiny_mce/template_list.js"
});



