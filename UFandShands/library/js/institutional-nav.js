 $(document).ready(function() {
	   
	  // Hoverintent for Default Drop Down Navigation
	  function institutionalNavHoverOver(){
	    $(this).find('.sub-mega').stop().fadeTo('fast', 1).show(); 	  
	  }
	  	
	  function institutionalNavHoverOut(){
	    $(this).find('.sub-mega').stop().fadeTo('fast', 0, function() {
	      $(this).hide();
	    });
	  }
    
	  var configInstitutionalNav = {
	    sensitivity: 1,       // number = sensitivity threshold (must be 1 or higher)
	    interval: 50,        // number = milliseconds for onMouseOver polling interval
	    over: institutionalNavHoverOver,  // function = onMouseOver callback (REQUIRED)
	    timeout: 500,         // number = milliseconds delay before onMouseOut
	    out: institutionalNavHoverOut    // function = onMouseOut callback (REQUIRED)
	  };
	  
    $("#institutional-nav .sub-mega").css({'opacity':'0'});
	  $('#institutional-nav li').hoverIntent(configInstitutionalNav);
	
});