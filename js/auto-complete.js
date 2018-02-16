/*!
 * Auto Complete 5.1
 * April 13, 2010
 * Corey Hart @ http://www.codenothing.com
 */ 
jQuery(function($){
	// Setup maxHeight for IE6
	$('#main_search').autocomplete({
		ajax: 'autocomplete.php',
   		select: function(event, ui) { alert(ui.item.id); }
	});

});
