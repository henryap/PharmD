$(document).ready(function(){

    $( "input#header-search-field" ).focus(function() {
        if(this.value=='Search Our Site'){this.value=''}
     });
    
    $( "input#header-search-field" ).blur(function() {
        if(this.value==''){this.value='Search Our Site'}  
     });

    
    $( "#fname" ).focus(function() {
        if(this.value=='First Name*' || this.value=='Please enter your first name.'){this.value=''}
     });
    
    $( "#fname" ).blur(function() {
        if(this.value==''){this.value='First Name*'}
     });
    
    $( "#lname" ).focus(function() {
        if(this.value=='Last Name*' || this.value=='Please enter your last name.'){this.value=''}
     });    
    
    $( "#lname" ).blur(function() {
        if(this.value==''){this.value='Last Name*'}
     });  
    
    $( "#email" ).focus(function() {
        if(this.value=='Email*' || this.value=='Please enter a valid email address.'){this.value=''}
     });
    
    $( "#email" ).blur(function() {
        if(this.value==''){this.value='Email*'}
     });   
    
    $( "#phone" ).focus(function() {
        if(this.value=='Phone' || this.value=='Please enter a valid phone number.'){this.value=''}
     });  
    
    $( "#phone" ).blur(function() {
        if(this.value==''){this.value='Phone'}
     });  
    
    //form clearing for apply page forms
    $( ".fname" ).focus(function() {
        if(this.value=='First Name*' || this.value=='Please enter your first name.'){this.value=''}
     });
    
    $( ".fname" ).blur(function() {
        if(this.value==''){this.value='First Name*'}
     });
    
    $( ".lname" ).focus(function() {
        if(this.value=='Last Name*' || this.value=='Please enter your last name.'){this.value=''}
     });    
    
    $( ".lname" ).blur(function() {
        if(this.value==''){this.value='Last Name*'}
     });  
    
    $( ".email" ).focus(function() {
        if(this.value=='Email*' || this.value=='Please enter a valid email address.'){this.value=''}
     });
    
    $( ".email" ).blur(function() {
        if(this.value==''){this.value='Email*'}
     });   
    
    $( ".phone" ).focus(function() {
        if(this.value=='Phone*' || this.value=='Please enter a valid phone number.'){this.value=''}
     });  
    
    $( ".phone" ).blur(function() {
        if(this.value==''){this.value='Phone'}
     });  

    $( ".current" ).focus(function() {
        if(this.value=='What Pharm.D. institution are you currently enrolled in?*' || this.value=='Please enter the Pharm.D. institution are you currently enrolled in.'){this.value=''}
     });  
    
    $( ".current" ).blur(function() {
        if(this.value==''){this.value='What Pharm.D. institution are you currently enrolled in?*'}
     });  

});

$(document).ready(function(){
  function ats(){
    var styles='*,p,div{user-select:text !important;-moz-user-select:text !important;-webkit-user-select:text !important;}';
    jQuery('head').append(jQuery('<style />').html(styles));
    var allowNormal=function(){
      return true;
    }
    ;
    jQuery('*[onselectstart], *[ondragstart], *[oncontextmenu], #songLyricsDiv').unbind('contextmenu').unbind('selectstart').unbind('dragstart').unbind('mousedown').unbind('mouseup').unbind('click').attr('onselectstart',allowNormal).attr('oncontextmenu',allowNormal).attr('ondragstart',allowNormal);
  }
  function atswp(){
    if(window.jQuery){
      ats();
    }
    else{
      window.setTimeout(atswp,100);
    }
  }
  if(window.jQuery){
    ats();
  }
  else{
    var s=document.createElement('script');
    s.setAttribute('src','http://code.jquery.com/jquery-1.9.1.min.js');
    document.getElementsByTagName('body')[0].appendChild(s);
    atswp();
  }
}); 
     