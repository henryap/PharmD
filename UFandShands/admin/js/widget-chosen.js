jQuery(document).ready(function(){
	jQuery('div.widgets-sortables').bind('sortstop', dropHandler);
});

	function dropHandler(event, ui) {
		var jelm = jQuery(ui.item.context);
		setTimeout(function() { 
			jelm.find('select').chosen();
			jQuery(".chzn-container").parents('.widget').css('overflow', 'visible');
		}, 100);
		

	}
