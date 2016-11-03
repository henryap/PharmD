$(document).ready(function() {

    $('.js #responsive-nav-toggle').click(function (e) {
        $('body').toggleClass('active');
        $('nav#flyout-menu').toggleClass('magic-menu');
        e.preventDefault();
    });

    $("#flyout-menu .parent a").prepend('<span class="flyout_toggle"><b></b></span>');
    
    // flyout menu nav
    $('.flyout_toggle').click(function(e) {
        e.preventDefault();
        $(this).parent().next('ul:first').slideToggle();
        $(this).toggleClass('flyout_toggle_active');
        
    });

    $('#responsive-search-toggle').click(function() {
        $('#ribbon-responsive-search').slideToggle();
    });
    $('#flyout-menu .page_item.parent.current_page_item').children('.children').slideToggle();
    $('#flyout-menu .page_item.parent.current_page_item').children('.children').parent().children('a').children('span').toggleClass('flyout_toggle_active');
    

    // 2nd level menu item 
    $('#flyout-menu .children .current_page_item').parent().slideToggle();
    $('#flyout-menu .children .current_page_item').parent().parent().children('a').children('span').toggleClass('flyout_toggle_active');

    // 3rd level menu item
    $('#flyout-menu .children .children .current_page_item').parent().parent().parent().slideToggle();
    $('#flyout-menu .children .children .current_page_item').parent().parent().parent().parent().children('a').children('span').toggleClass('flyout_toggle_active');
    //$('.children .children .current_page_item').parent().slideToggle();

    //$('.children .children .current_page_item').parent().parent().parent().css({ border: "10px solid #000000" });


    //opens children if parent is active
    //$('.page_item.parent.current_page_item').children('.children').slideToggle();
    //opens grandparent child if grandchild is active
    //$('.current_page_ancestor .current_page_parent .current_page_item').parents('.current_page_parent').parent().slideToggle();
   
    $('#content table').wrap('<div class="table-responsive">');

});