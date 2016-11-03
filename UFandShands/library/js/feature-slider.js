$(function() {
    function changeSlide( newSlide ) {
        // cancel any timeout
        clearTimeout( slideTimeout );
        
        // change the currSlide value
        currSlide = newSlide;
        
        // make sure the currSlide value is not too low or high
        if ( currSlide > maxSlide ) currSlide = 0;
        else if ( currSlide < 0 ) currSlide = maxSlide;
 

        // adjust amount of slide position change for responsive 
        $slideWidth = -320; 
        if ( $(document).width() > 600  ) $slideWidth = -600; 
        if ( $(document).width() > 900  ) $slideWidth = -930; 

        // animate the slide reel
        $slideReel.animate({
            left : currSlide * $slideWidth
        }, 1200, 'swing', function() {
            // hide / show the arrows depending on which frame it's on
            if ( currSlide == 0 ) $slideLeftNav.hide();
            else $slideLeftNav.show();
            
            if ( currSlide == maxSlide ) $slideRightNav.hide();
            else $slideRightNav.show();
            
            // set new timeout if active
            if ( activeSlideshow ) slideTimeout = setTimeout(nextSlide, sliderSpeed);
            
            $(this).find('.slide .excerpt').stop().fadeOut();
            if ( $(this).find('.slide-' + (currSlide + 1) + ' .excerpt').hasClass('hide-for-large') && ($(document).width() > 900 )) { 
                // do nothing
            } else {
                $(this).find('.slide-' + (currSlide + 1) + ' .excerpt').stop().fadeIn();
            }
        });
        
        // animate the navigation indicator
        $activeNavItem.animate({
            left : currSlide * 149
        }, 1200, 'swing');
    }
    
    function nextSlide() {
        changeSlide( currSlide + 1 );
    }
    
    // define some variables / DOM references
    var activeSlideshow = true,
    currSlide = 0,
    slideTimeout,
    $slideshow = $('#slideshow'),
    $slideReel = $slideshow.find('#slideshow-reel'),
    maxSlide = $slideReel.children().length - 1,
    $slideLeftNav = $slideshow.find('#slideshow-left'),
    $slideRightNav = $slideshow.find('#slideshow-right'),
    $activeNavItem = $slideshow.find('#active-nav-item');
    

    // detects window resize and moves to the next slide to correct for position within resized
    var id;
    $(window).resize(function() {
        clearTimeout(id);
        id = setTimeout(doneResizing, 500);
    });

    function doneResizing(){
        changeSlide( currSlide + 1 );
    }


    // $(window).resize(function(e){
    //     changeSlide( currSlide + 1 );
    // });
    // set navigation click events
    
    // left arrow
    $slideLeftNav.click(function(ev) {
        ev.preventDefault();
        
        activeSlideshow = false;
        
        changeSlide( currSlide - 1 );
    });
    
    // right arrow
    $slideRightNav.click(function(ev) {
        ev.preventDefault();
        
        activeSlideshow = false;
        
        changeSlide( currSlide + 1 );
    });
    
    // main navigation
    $slideshow.find('#slideshow-nav a.nav-item').click(function(ev) {
        ev.preventDefault();
        
        activeSlideshow = false;
        
        changeSlide( $(this).index() );
    });
    
    // set the dynamic width
    
    var slider_nav_stop = $('.nav-item');
    var slider_nav_width = slider_nav_stop.width() * slider_nav_stop.length;
    $('#slideshow-nav').css({'width' : slider_nav_width, 'visibility' : 'visible'});
    
    // start the animation
    
    slideTimeout = setTimeout(nextSlide, sliderSpeed);
    
    // test to see if fade in or not due to being full width image
    if ( $( ".slide-1 .excerpt" ).hasClass( "hide-for-large" ) && ($(document).width() > 900 )  ) { 
        // do nothing
    } else {
        $('.slide-1 .excerpt').fadeIn(); 
    }
    
    // show thumbs on hover
    
    $(".slider-thumb ").hide();
    $("a.nav-item").hover(
        function(){
          if($.browser.msie) {
            $(this).find('.slider-thumb').stop().show();
          } else {
            $(this).find('.slider-thumb').stop().fadeTo(500, 1).show();
          }
        },
        function(){
          if($.browser.msie) {
            $(this).find('.slider-thumb').stop().hide();
          } else {
            $(this).find('.slider-thumb').stop().fadeTo(250, 0).hide();
          }
        });
});
