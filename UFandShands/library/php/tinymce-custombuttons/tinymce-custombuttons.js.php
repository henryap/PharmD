<?php 
    require_once('buttons.php');
    require_once('custombutton.php');
    require_once('formfield.php');
    
    header('Content-type: text/javascript');
    
?>

(function() {
    tinymce.create('tinymce.plugins.shortCodeButtons', {
        init : function(ed, url) {
	
<?php 
    $shortCodes = loadCustomButtons();
    
    foreach ($shortCodes as $shortCode) {
?>
            ed.addButton('ufh-<?php echo $shortCode->shortCodeTag ?>', {
                title : '<?php echo $shortCode->title ?>',
                image : url + '<?php echo $shortCode->icon ?>',
                onclick : function() {
		   <?php if (count($shortCode->fields) > 0) { ?>
		    tb_show("<?php echo $shortCode->title ?>", url + "/shortcode-form.php?shortcode=<?php echo $shortCode->shortCodeTag ?>&TB_iframe=true",""); 
		   <?php } else { ?>
		   	tinyMCE.execCommand("mceInsertContent",false, "[<?php echo $shortCode->shortCodeTag ?>]" + tinyMCE.activeEditor.selection.getContent() + "<span id='caret_pos_holder'></span><?php echo ($shortCode->enclosing ? '[/' . $shortCode->shortCodeTag . ']' : ''); ?>");
			tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]); //select the span
			tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]);
		   <?php } ?>
                }
            });
<?php } ?>
	    
        }
        
    });
    tinymce.PluginManager.add('shortcodebuttons', tinymce.plugins.shortCodeButtons);
    

})();


