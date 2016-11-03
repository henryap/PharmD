<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function ufandshands_peoplelist($atts) {
    extract(shortcode_atts(array(
	    'directory' => 'radiology_fac.frag'
    ), $atts));
    
    if (isset($directory)) {
	 echo <<<END1
<div id="directory-listing">
<div class="directory_abc">
<a href="#a">A</a> 
<a href="#b">B</a>  
<a href="#c">C</a> 
<a href="#d">D</a> 
<a href="#e">E</a>   
<a href="#f">F</a>   
<a href="#g">G</a>   
<a href="#h">H</a>   
<a href="#i">I</a>   
<a href="#j">J</a>   
<a href="#k">K</a>   
<a href="#l">L</a>   
<a href="#m">M</a>   
<a href="#n">N</a>   
<a href="#o">O</a>   
<a href="#p">P</a>   
<a href="#q">Q</a>   
<a href="#r">R</a>   
<a href="#s">S</a>   
<a href="#t">T</a>   
<a href="#u">U</a>   
<a href="#v">V</a>   
<a href="#w">W</a>   
<a href="#x">X</a>   
<a href="#y">Y</a>   
<a href="#z">Z</a> 
</div>
END1;
	
	$content = file_get_contents('x/com/radiology/frags/' . $directory);
	print ($content); 

    }
}

add_shortcode('people-listing','ufandshands_peoplelist'); 
?>
