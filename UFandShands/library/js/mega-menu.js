$(document).ready(function() {
	  
	  // Hoverintent for Default Drop Down Navigation
	  function megaHoverOver(){
	  	// $(this).find(".sub .children li:nth-child(4n+1)").css({'clear' : 'both'});
	    $(this).find(".sub").stop().fadeTo('fast', 1).show();
	  };
	  	
	  function megaHoverOut(){
	    $(this).find(".sub").stop().fadeTo('fast', 0, function() {
	      $(this).hide();
	    });
	  }
	
	  var config = {
	    sensitivity: 1,       // number = sensitivity threshold (must be 1 or higher)
	    interval: 50,        // number = milliseconds for onMouseOver polling interval
	    over: megaHoverOver,  // function = onMouseOver callback (REQUIRED)
	    timeout: 500,         // number = milliseconds delay before onMouseOut
	    out: megaHoverOut     // function = onMouseOut callback (REQUIRED)
	  };
	  
    $("#primary-nav .sub").css({'opacity':'0'});

    // Default Implementation
	  $("#primary-nav ul li").hoverIntent(config);
	  
	  // Debug helper (keeps sub menu open for inspection via click event)
	  // * Comment out when finished styling *
	  
	  /*
	    
	  $("#primary-nav ul li").click(function(e){
	   	e.preventDefault();
	   	if($(this).find(".sub").is(":hidden")) {
	   		$(this).find(".sub").stop().fadeTo('fast', 1).show();
			} else {
				$(this).find(".sub").stop().fadeTo('fast', 0, function() {
	  	 		$(this).hide();
	    	});
			}
	  });
	  
	   */

	  // End Debug

});