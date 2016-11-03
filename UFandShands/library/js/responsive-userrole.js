$(document).ready(function() {

    //create span toggle in user-role h3 a
    $("#user-role h3 a").prepend('<span class="user-role_toggle hide-for-large"><b>Toggle</b></span>');
 
    //user role nav
    $('.user-role_toggle').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().find('ul').slideToggle();
        $(this).toggleClass('flyout_toggle_active');
    });

    // opens children of parent if child is active
   // $('.children .current_page_item').parent().slideToggle();
    //opens children if parent is active
   // $('.page_item.parent.current_page_item').children('.children').slideToggle();
    //opens grandparent child if grandchild is active
   // $('.current_page_ancestor .current_page_parent .current_page_item').parents('.current_page_parent').parent().slideToggle();

 });