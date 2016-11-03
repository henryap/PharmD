 $(document).ready(function() {
	     
	  // Hoverintent for Default Drop Down Navigation
	  function defaultHoverOver(){
	    $(this).find(".children").stop().fadeTo('fast', 1).show(); 	  
	  };
	  	
	  function defaultHoverOut(){
	    $(this).find(".children").stop().fadeTo('fast', 0, function() {
	      $(this).hide();
	    });
	  }
	
	  var config = {
	    sensitivity: 1,       // number = sensitivity threshold (must be 1 or higher)
	    interval: 50,        // number = milliseconds for onMouseOver polling interval
	    over: defaultHoverOver,  // function = onMouseOver callback (REQUIRED)
	    timeout: 500,         // number = milliseconds delay before onMouseOut
	    out: defaultHoverOut     // function = onMouseOut callback (REQUIRED)
	  };
	  
    $("#primary-nav .children").css({'opacity':'0'});

    // Default Implementation
	  $("#primary-nav li").hoverIntent(config);

	  // Debug helper (keeps sub menu open for inspection via click event)
	  // * Comment out when finished styling * 
	  
	  /*
	  
	  $("#primary-nav li").click(function(e){
	  	e.preventDefault();
	  	if($(this).find(".children").is(":hidden")) {
	  		$(this).find(".children").stop().fadeTo('fast', 1).show();
			} else {
				$(this).find(".children").stop().fadeTo('fast', 0, function() {
	      	$(this).hide();
	    	});
			}
	  });
	  
	  */
	 
	  // End Debug
	
});
















