<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
header('Content-Type: text/html; charset=' . get_bloginfo('charset'));

require_once('buttons.php');
require_once('custombutton.php');
require_once('formfield.php');

$customButtonName = null;
if (isset($_GET['shortcode']))
    $customButtonName = $_GET['shortcode'];

$shortCode = getShortCodeButton();
?>
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US"> 
    <head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<?php
wp_admin_css( 'global', true );
wp_admin_css( 'wp-admin', true );
echo "<link rel='stylesheet' href='" . get_template_directory_uri() . "/admin/css/shortcode-admin-style.css'>";

?> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript">
	    jQuery(function() {
		jQuery('#submitBtn').click(function () {
		    try {
		    window.parent.tinyMCE.execCommand("mceInsertContent",false, <?php echo generateShortCode() ?>);
		    window.parent.tinyMCE.activeEditor.selection.select(window.parent.tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]); //select the span
		    window.parent.tinyMCE.activeEditor.dom.remove(window.parent.tinyMCE.activeEditor.dom.select('span#caret_pos_holder')[0]);
		    }
		    catch (e) {} 
		    finally {
		    window.parent.tb_remove();
		    }
		});
	    });
	</script>
    </head>
    <body id="media-upload">
	<div id="wphead">
	<h1>Adding <?php echo $shortCode->title; ?></h1>
	</div>
	<form class="media-upload-form type-form">
	    <div id="media-items">
		<div class="media-item media-blank">
		    <?php echo displayDescription(); ?>
		    <table class="describe">
			<tbody>
			<?php echo generateForm(); ?>
			</tbody>
		    </table>
		</div>
	    </div>
	</form>
    </div>
</body>
</html>




<?php

function generateForm() {
    global $shortCode;

    $formHtml = null;
    foreach ($shortCode->fields as $field) {
	if ($field->fieldType != FormField::Hidden) {
	    $formHtml .= "<tr><th valign='top' class='label' scope='row'><span class='alignleft'><label>{$field->label}</label></span>";
	    $formHtml .= "</th><td class='field'> ";
	}
	
	$formHtml .= generateField($field);
		
	if ($field->fieldType != FormField::Hidden)
	    "</td></tr>";
    }
    $formHtml .= '<tr><td><input type="button" value="Add Shortcode" id="submitBtn" class="button" /></td></tr>';

    return $formHtml;
}

function displayDescription() {
    global $shortCode;
    $description = '';
    if (isset($shortCode->description)) {
	$description = '<p>' . $shortCode->description . '</p>';
    }

    return $description;
}

function generateField($field) {

    switch ($field->fieldType) {
	case FormField::Text:
	    $inputHtml .= "<input type='text' id='{$field->attributeName}' value='{$field->defaultValue}' size='55' />";
	    break;

	case FormField::Checkbox:
	    $inputHtml .= "<input type='checkbox' id='{$field->attributeName}' " . (isset($field->defaultValue) && $field->defaultValue == 'true' ? "checked='checked'" : "") . "/>";
	    break;

	case FormField::DropDown:
	    $inputHtml .= "<select id='{$field->attributeName}'>";
	    foreach ($field->options as $option) {
		$inputHtml .= "<option value='{$option->value}' " . ($field->defaultValue == $option->value ? 'selected="selected"' : '') . ">{$option->name}</option>";
	    }
	    $inputHtml .= "</select>";
	    break;
	    
	case FormField::Hidden:
	    $inputHtml .= "<input type='hidden' id='{$field->attributeName}' value='{$field->defaultValue}' />";  
	    break;
	
	case FormField::Categories:
    		$inputHtml .= "<select id='{$field->attributeName}'>";
		require_once(getenv("DOCUMENT_ROOT") . '/wp-includes/category.php');
	    	$categoriesArr = array();
		$categories = get_categories('hide_empty=0&orderby=name');
		$inputHtml .= '<option value="">All categories</option>';
		foreach ($categories as $category_specific) {
		    $inputHtml .= "<option value='{$category_specific->cat_name}' " . ($field->defaultValue == $category_specific->cat_name ? 'selected="selected"' : '') . ">{$category_specific->cat_name}</option>";
		}
		
		$inputHtml .= "</select>";
	    break;
    }
    
    if (isset($field->description) && ($field->fieldType != FormField::Hidden)) 
		$inputHtml .= "<br /><span class='description'>" . $field->description . "</span>";
    
    return $inputHtml;
}


function generateShortCode() {
    global $shortCode;
    $js = null;

    $js = '"[' . $shortCode->shortCodeTag . ' ';

    foreach ($shortCode->fields as $field) {
	
	switch ($field->fieldType) {
	    case FormField::Text:
		$js .= "{$field->attributeName}='\" + $('#{$field->attributeName}').val() + \"' ";
		break;
	    
	    case FormField::Checkbox:
		$js .= "{$field->attributeName}='\" + $('#{$field->attributeName}').is(':checked') + \"' ";
		break;
	    
	    case FormField::DropDown:
	    case FormField::Categories:
		$js .= "{$field->attributeName}='\" + $('#{$field->attributeName} option:selected').val() + \"' ";
		break;
	    
    	    case FormField::Hidden:
		$js .= "{$field->attributeName}='\" + $('#{$field->attributeName}').val() + \"' ";
		break;
	    
	}
    }

    $js .= ']" + window.parent.tinyMCE.activeEditor.selection.getContent() + "<span id=\"caret_pos_holder\"></span>"';

    if ($shortCode->enclosing)
	$js .= '+ "[/' . $shortCode->shortCodeTag . ']"';


    echo $js;
}

function getShortCodeButton() {
    global $customButtonName;
    $selectedButton = null;
    $buttonArr = loadCustomButtons();

    foreach ($buttonArr as $button) {
	if ($button->shortCodeTag == $customButtonName) {
	    $selectedButton = $button;
	    break;
	}
    }

    return $selectedButton;
}
?>
