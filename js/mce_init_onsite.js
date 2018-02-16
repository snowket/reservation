
tinyMCE.init({
	mode : "specific_textareas",
    textarea_trigger : "wysiwyg",
	theme : "simple",
	plugins : "advhr,advimage,convertor,advlink,paste,noneditable",
	theme_advanced_buttons1_add_before : "",
	theme_advanced_buttons1_add : "fontselect,fontsizeselect",
	theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,convertor,separator",
	theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor,styleprops",
	theme_advanced_buttons3_add_before : "",
	theme_advanced_buttons3_add : "media,advhr,separator,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	extended_valid_elements : "hr[class|width|size|noshade|style|bgcolor|color],font[face|size|color|style],span[class|align|style]",
	theme_advanced_resize_horizontal : false,
	theme_advanced_resizing : true,
	apply_source_formatting : true
});

