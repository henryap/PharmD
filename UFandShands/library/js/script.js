 $(document).ready(function() {

    // Disable css file icons from images linked to docs (e.g. doc, pdf, xls, etc)
    $("a:has(img)").addClass('no-icon');
   
    // PrettyPhoto
    function ufandshands_lightbox() {

      //$("a[rel^='prettyPhoto'],.gallery a").prettyPhoto({
      $("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png'], a[rel^='prettyvideo']").prettyPhoto({
        animationSpeed:'fast',
        slideshow:5000,
        theme:'pp_default',
        show_title:false,
        social_tools:false,
        overlay_gallery: true,
        markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a class="pp_close" href="#">Close</a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
											{pp_social} \
                                            <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
						<div class="pp_bottom"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>'
      });

    }

    if(jQuery().prettyPhoto) {

      ufandshands_lightbox(); 

    }
    
	function responsive_menu_child_toggle() {
		$(this).parent().find('ul').slideToggle();

	} // end responsive_menu_child_toggle
	
	
	//apollo tab system
	$("#tab-one").show();
	$(".content-tabs li a").click(function (e){
		e.preventDefault();
		var parent = $(this).parent("li");
		var tab_data_id = $(this).attr("href");
		
		if(parent.hasClass("active")){
			//do nothing
		} else {
			$(".content-tabs li").removeClass("active");
			$(".apollo-tabs").fadeOut();
			parent.addClass("active");
			$("#"+tab_data_id).fadeIn();
		}
		
	});

});


