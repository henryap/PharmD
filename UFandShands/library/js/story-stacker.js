$(function(){
		var $featured_area = $('#featured-area');
		var $featured_item = $('#featured-area div#stacker-control div.featitem');
		var $slider_control = $('#featured-area div#stacker-control');
		var $image_container = $('div#s1 > div');
		var ordernum;
		var pause_scroll = false;
			
    // $featured_item.find('img').fadeTo("fast", 0.7);
		$slider_control.find("div.featitem.active img").fadeTo("fast", 1);
		$image_container.css("background-color","#000000");
		
		$image_container.hover(
			function () {
				$(this).find("img").fadeTo("fast", 0.7);
			}, 
			function () {
				$(this).find("img").fadeTo("fast", 1);
			}
		);
		
		function gonext(this_element){
      // $slider_control.find("div.featitem.active img").fadeTo("fast", 0.7);
			$slider_control.find("div.featitem.active").removeClass('active');
			this_element.addClass('active');
			$slider_control.find("div.featitem.active img").fadeTo("fast", 1);
			ordernum = this_element.find("span.order").html();
			$('#s1').cycle(ordernum - 1);
		} 
		
		$featured_item.click(function() {
			clearInterval(interval);
			gonext($(this)); 
			return false;
		});
		
		var auto_number;
		var interval;
		
		$featured_item.bind('autonext', function autonext(){
			if (!(pause_scroll)) gonext($(this)); 
			return false;
		});
 
		interval = setInterval(function () {
			auto_number = $slider_control.find("div.featitem.active span.order").html();
			if (auto_number == $featured_item.length) auto_number = 0;
			$featured_item.eq(auto_number).trigger('autonext');
		}, sliderSpeed);
});
	
$(document).ready(function() {
	$('#s1').cycle({
		timeout: 0, 
		speed: 300,
		fx: 'fade'
	});
});