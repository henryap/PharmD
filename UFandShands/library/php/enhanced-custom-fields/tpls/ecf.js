jQuery(function ($) {
	if ($('form#post input[type=file]').length) {
		$('form#post').attr('enctype', 'multipart/form-data');
	}
	
	$('.clone-ecf').live('click', function () {
	    var src_row = $(this).parents('tr')
	    var new_row = src_row.clone();
	    new_row.find('td:first').html('');
	    new_row.find('td:last').html('');
	    new_row.find('input, textarea, select').eq(0).each(function () {
	        var name = $(this).attr('name');
	        var pieces = /_(.*)_.*/.exec(name);
	        
	    });
	    
		new_row.insertAfter(src_row);
		return false;
	});
});