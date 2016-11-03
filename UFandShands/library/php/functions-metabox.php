<?php


/*-----------------------------------------------------------------------------------*/
/*	Custom Write Panels
/*-----------------------------------------------------------------------------------*/

/* http://webdesignfan.com/custom-write-panels-in-wordpress/ */

// custom meta boxes

// Add meta box to editor
function ufandshands_meta_add_box() {
    $metaBoxes = ufandshands_getMetaBoxes();
    
    foreach ($metaBoxes as $metaBox) {
	    add_meta_box($metaBox['id'], $metaBox['title'], 'display_html', $metaBox['page'], $metaBox['context'], $metaBox['priority'], $metaBox);
    }
}
add_action('admin_menu', 'ufandshands_meta_add_box');



// Callback function to show fields in meta box
function display_html($post, $metaBox) {
    //global $meta_box, $post; // get the variables from global $meta_box and $post

    // Use nonce for verification to check that the person has adequate priveleges
    echo '<input type="hidden" name="my_first_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	// create the table which the options will be displayed in
    echo '<table class="form-table">';

    foreach ($metaBox['args']['fields'] as $field) { // do this for each array inside of the fields array
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>', // create a table row for each option
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {

            case 'text': // the HTML to display for type=text options
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '
', $field['desc'];
                break;
                
            case 'menu': // the HTML to display for type=text options
                ufandshands_nav_menu_drop_down('custom_meta_page_contentmenu', 'default-content-menu', true);
                break;    
            
            case 'file': // the HTML to display for type=file options
                echo '
                <script>
                jQuery(document).ready(function($){
                $("#', $field['id'], '-button").on("click", function() {
			        formfield = $("#', $field['id'], '").attr("name");
			 
			        tb_show( "", "media-upload.php?type=image&amp;TB_iframe=true");
			        
			        //store old send to editor function
			        window.restore_send_to_editor = window.send_to_editor;
			        
			        // Display the Image link in TEXT Field
			        window.send_to_editor = function(html) { 
			            fileurl = $(html).attr("href");  
			            
			            $("#', $field['id'], '").val(fileurl);
			            
			            tb_remove(); 
			            window.send_to_editor = window.restore_send_to_editor;
			        } 
			        
			        return false;
			    });
			    });
			    </script>
                <input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:47%" /><button id="', $field['id'], '-button">Upload</button>', 
                '<br>', 
                $field['desc'];
                break;         

            case 'textarea': // the HTML to display for type=textarea options
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '
', $field['desc'];
                break;

            case 'select': // the HTML to display for type=select options
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;

            case 'radio': // the HTML to display for type=radio options
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;

            case 'checkbox': // the HTML to display for type=checkbox options
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />', '
', $field['desc'];
                break;
        }
        echo     '<td>',
            '</tr>';
    }

    echo '</table>';
}

// Save data from meta box
function ufandshands_meta_save_data($post_id) {
    // verify nonce -- checks that the user has access
    if (!wp_verify_nonce($_POST['my_first_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // creates an array of all the metaboxes that we're saving
    $metaBoxes = ufandshands_getMetaBoxes();
    
    foreach ($metaBoxes as $metaBox) {
	foreach ($metaBox['fields'] as $field) { // save each option
	    $old = get_post_meta($post_id, $field['id'], true);
	    $new = $_POST[$field['id']];

	    if ($new && $new != $old) { // compare changes to existing values
		update_post_meta($post_id, $field['id'], htmlspecialchars($new));
	    } elseif ('' == $new && $old) {
		delete_post_meta($post_id, $field['id'], $old);
	    }
	}
    }
}
add_action('save_post', 'ufandshands_meta_save_data'); // save the data



// define the metabox for misc options
function ufandshands_getMetaBoxes() {
    $prefix = 'custom_meta_';
    
    //ger all menus
    $menus = wp_get_nav_menus();
	
	//array for all menus to work in SELECT
	//$contentMenus = array();
	$glanceMenus = array();
	
	foreach($menus as $menu){
		
		/*
		if(strpos($menu->name,'Content') !== false) {
			$contentMenus[$menu->slug] = $menu->name;
		}*/
		
		//glance menu
		if(strpos($menu->name,'Glance') !== false) {
			$glanceMenus[$menu->slug] = $menu->name;
		}	
	}
	
	
    //$formattedMenus = array('standard' => 'Option One', 'custom' => 'Option Two');
    
    $metaBoxes = array();
    
    // slider options metabox
    $metaBoxes[] = array(
		    'id' => 'ufandshands_slider_options', // the id of our meta box
		    'title' => 'Featured Content Slider Options (optional) <a href="/wp-admin/themes.php?page=options-framework">Enable in Theme Options</a>', // the title of the meta box
		    'page' => 'post', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'high', // keep it near the top
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Button Text',
			  'desc' => 'Enter the text that will appear as a button',
			  'id' => $prefix . 'featured_content_button_text',
			  'type' => 'text',
			  'std' => 'Read More',
			),
				array(
			  'name' => 'Disable Image Effects',
				'desc' => 'Remove the border and shadow effects applied to half-image images <br />&emsp;&emsp;(Recommended size is <strong>450px x 305px</strong>). ',
			  'id' => $prefix . 'image_effect_disabled',
			  'type' => 'checkbox',
			),

				array(
			  'name' => 'Full Width Image',
				'desc' => 'Image will use 100% of the allowed width <br />&emsp;&emsp;(Recommended size is <strong>930px x 325px</strong>).',
			  'id' => $prefix . 'image_type',
			  'type' => 'checkbox',
			),

				array(
			  'name' => 'Disable Image Captions',
				'desc' => 'Disable the caption box from appearing on <em>full width images</em> (contains title, excerpt)',
			  'id' => $prefix . 'featured_content_disable_captions',
			  'type' => 'checkbox',
			),
		
		)
		  
	  );
  

    // post subtitle meta box
    $metaBoxes[] = array(
        'id' => 'ufandshands_post_subtitle_option', // the id of our meta box
        'title' => 'Subtitle <small>(optional)</small>', // the title of the meta box
        'page' => 'post', // display this meta box on post editing screens
        'context' => 'normal',
        'priority' => 'low', 
        'fields' => array( // all of the options inside of our meta box
      array(
        'name' => 'Subtitle text',
        'desc' => 'Enter the text that will appear as a secondary title',
        'id' => $prefix . 'post_subtitle',
        'type' => 'text',
      ),

    )
    
    );
    
    // page subtitle meta box
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_subtitle_option', // the id of our meta box
		    'title' => 'Subtitle <small>(optional)</small>', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Subtitle text',
			  'desc' => 'Enter the text that will appear as a secondary title',
			  'id' => $prefix . 'page_subtitle',
			  'type' => 'text',
			),

	  )
	  
    );
    
    // hide widgets
    $metaBoxes[] = array(
        'id' => 'ufandshands_post_hideevents_option', // the id of our meta box
        'title' => 'Hide Widgets', // the title of the meta box
        'page' => 'page', // display this meta box on post editing screens
        'context' => 'normal',
        'priority' => 'high', 
        'fields' => array( // all of the options inside of our meta box
      array(
        'name' => 'Check to hide Banner Logos',
        'desc' => '',
        'id' => $prefix . 'hide_logos',
        'type' => 'checkbox',
        'state' => 'checked'
      ),
      array(
        'name' => 'Check to hide Events List',
        'desc' => '',
        'id' => $prefix . 'hide_events',
        'type' => 'checkbox',
        'state' => 'checked'
      ),
      array(
        'name' => 'Check to hide Application Procedure',
        'desc' => '',
        'id' => $prefix . 'hide_appprocedure',
        'type' => 'checkbox',
        'state' => 'checked'
      ),

    )
    
    );
    
    
    // Banner Logo 1
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_logo1_option', // the id of our meta box
		    'title' => 'Banner Logo 1', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Banner Logo 1',
			  'desc' => 'leave blank for default logo',
			  'id' => $prefix . 'page_logo1',
			  'type' => 'file'
			),

	  )
	  
    );
    
    // Banner Logo 2
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_logo2_option', // the id of our meta box
		    'title' => 'Banner Logo 2', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Banner Logo 2',
			  'desc' => 'leave blank for default logo',
			  'id' => $prefix . 'page_logo2',
			  'type' => 'file'
			),

	  )
	  
    );
    
    // Banner Background Color
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_bannercolor_option', // the id of our meta box
		    'title' => 'Banner Background HEX Color', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'HEX #',
			  'desc' => 'Enter the HEX# to override the banner background',
			  'id' => $prefix . 'page_bannercolor',
			  'type' => 'text',
			),

	  )
	  
    );
    
    // Form Background Color
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_formcolor_option', // the id of our meta box
		    'title' => 'Form Background HEX Color', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'HEX #',
			  'desc' => 'Enter the HEX# to override the form background',
			  'id' => $prefix . 'page_formcolor',
			  'type' => 'text',
			),

	  )
	  
    );
    
    // Banner Background
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_bannerbg_option', // the id of our meta box
		    'title' => 'Banner Background Image', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Custom Photo',
			  'desc' => 'leave blank for default',
			  'id' => $prefix . 'page_bannerbg',
			  'type' => 'file'
			),

	  )
	  
    );
    
    // Mobile Banner Background
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_mobilebannerbg_option', // the id of our meta box
		    'title' => 'Mobile Banner Background Image', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Custom Photo',
			  'desc' => 'leave blank for default',
			  'id' => $prefix . 'page_mobilebannerbg',
			  'type' => 'file'
			),

	  )
	  
    );
    
    // banner form
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_bannerform_option', // the id of our meta box
		    'title' => 'Banner Form', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'IFRAME or HTML Form',
			  'desc' => '',
			  'id' => $prefix . 'page_bannerform',
			  'type' => 'textarea',
			),

	  )
	  
    );
    
    // tab system
    $metaBoxes[] = array(
        'id' => 'ufandshands_post_tabsystem_option', // the id of our meta box
        'title' => 'Tab System', // the title of the meta box
        'page' => 'page', // display this meta box on post editing screens
        'context' => 'normal',
        'priority' => 'high', 
        'fields' => array( // all of the options inside of our meta box
      array(
        'name' => 'Check to hide the Tabs',
        'desc' => '',
        'id' => $prefix . 'hide_tabsystem',
        'type' => 'checkbox',
        'state' => 'checked'
      ),
      array(
        'name' => 'Tab 1 Title',
        'desc' => '',
        'id' => $prefix . 'tab1_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Tab 1 HTML',
        'desc' => '',
        'id' => $prefix . 'tab1_html',
        'type' => 'textarea'
      ),
      array(
        'name' => 'Tab 2 Title',
        'desc' => '',
        'id' => $prefix . 'tab2_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Tab 2 HTML',
        'desc' => '',
        'id' => $prefix . 'tab2_html',
        'type' => 'textarea'
      ),
      array(
        'name' => 'Tab 3 Title',
        'desc' => '',
        'id' => $prefix . 'tab3_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Tab 3 HTML',
        'desc' => '',
        'id' => $prefix . 'tab3_html',
        'type' => 'textarea'
      ),
      array(
        'name' => 'Tab 4 Title',
        'desc' => '',
        'id' => $prefix . 'tab4_title',
        'type' => 'text'
      ),
      array(
        'name' => 'Tab 4 HTML',
        'desc' => '',
        'id' => $prefix . 'tab4_html',
        'type' => 'textarea'
      ),

    )
    
    );
    


 $metaBoxes[] = array(
        'id' => 'ufandshands_application', // the id of our meta box
        'title' => 'Application System', // the title of the meta box
        'page' => 'page', // display this meta box on post editing screens
        'context' => 'normal',
        'priority' => 'default', 
        'fields' => array( // all of the options inside of our meta box
      array(
        'name' => 'Step 1 HTML',
        'desc' => '',
        'id' => $prefix . 'step1_html',
        'type' => 'textarea'
      ),
      array(
        'name' => 'Step 2 HTML',
        'desc' => '',
        'id' => $prefix . 'step2_html',
        'type' => 'textarea'
      ),
      array(
        'name' => 'Step 3 HTML',
        'desc' => '',
        'id' => $prefix . 'step3_html',
        'type' => 'textarea'
      ),

    )
    
    );

    // glance menu
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_glancemenu_option', // the id of our meta box
		    'title' => 'Sidebar List', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Choose a list',
			  'desc' => '',
			  'id' => $prefix . 'page_glancemenu',
			  'type' => 'select',
			  'options' => $glanceMenus
			),
            array(
                'name' => 'Check to hide Glance Menu',
                'desc' => '',
                'id' => $prefix . 'hide_glance',
                'type' => 'checkbox',
                'state' => 'checked'
            ),

	  )
	  
    );
    
    // glance box1
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_glancebox1_option', // the id of our meta box
		    'title' => 'Sidebar Glance Box 1', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Override Global Glance Box 1',
			  'desc' => '',
			  'id' => $prefix . 'page_glancebox1',
			  'type' => 'textarea',
			),

	  )
	  
    );
    
    // glance box2
    $metaBoxes[] = array(
		    'id' => 'ufandshands_page_glancebox2_option', // the id of our meta box
		    'title' => 'Sidebar Glance Box 2', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Override Global Glance Box 2',
			  'desc' => '',
			  'id' => $prefix . 'page_glancebox2',
			  'type' => 'textarea',
			),

	  )
	  
    );

    
    /*
    $metaBoxes[] = array(
	    'id' => 'ufandshands_page_select_option',
	    'title' => 'Test Select',
	    'page' => 'page',
	    'context' => 'normal',
		'priority' => 'low',
	    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Choose an option',
			  'desc' => 'leave blank for default',
			  'id' => $prefix . 'page_select',
			  'type' => 'select',
			  'options' => array(
	          	'standard' => __( 'Option One' ),
			  	'custom'   => __( 'Option Two' ),
			  	'none'     => __( 'Option Three' ),
			  )
			),
		)
	);	
	*/
    
    // add noindex option
    $metaBoxes[] = array(
		    'id' => 'ufandshands_noindex_option', // the id of our meta box
		    'title' => 'Hide from Search Engine', // the title of the meta box
		    'page' => 'page', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Noindex',
			  'desc' => 'Hide the content of this page from search engines',
			  'id' => $prefix . 'noindex',
			  'type' => 'checkbox',
			),

	  )
    );
    // add noindex option
    $metaBoxes[] = array(
		    'id' => 'ufandshands_noindex_option', // the id of our meta box
		    'title' => 'Hide from Search Engine', // the title of the meta box
		    'page' => 'post', // display this meta box on post editing screens
		    'context' => 'normal',
		    'priority' => 'low', 
		    'fields' => array( // all of the options inside of our meta box
			array(
			  'name' => 'Noindex',
			  'desc' => 'Hide the content of this page from search engines',
			  'id' => $prefix . 'noindex',
			  'type' => 'checkbox',
			),

	  )
    );
    
    if (is_super_admin()) {
	// add custom js option
	$metaBoxes[] = array(
			'id' => 'ufh_custom_js_body_top', // the id of our meta box
			'title' => 'Custom JS top of body (Superadmin only)', // the title of the meta box
			'page' => 'page', // display this meta box on post editing screens
			'context' => 'normal',
			'priority' => 'low', 
			'fields' => array( // all of the options inside of our meta box
			    array(
			      'name' => 'Javascript',
			      'desc' => 'Inject custom javascript at the top of the body',
			      'id' => $prefix . 'custom_js_body_top',
			      'type' => 'textarea',
			    ),

	      )
	);
	// add custom js option
	$metaBoxes[] = array(
			'id' => 'ufh_custom_js_body_top', // the id of our meta box
			'title' => 'Custom JS top of body (Superadmin only)', // the title of the meta box
			'page' => 'post', // display this meta box on post editing screens
			'context' => 'normal',
			'priority' => 'low', 
			'fields' => array( // all of the options inside of our meta box
			    array(
			      'name' => 'Javascript',
			      'desc' => 'Inject custom javascript at the top of the body',
			      'id' => $prefix . 'custom_js_body_top',
			      'type' => 'textarea',
			    ),

	      )
	);
    }
    return $metaBoxes;
}
?>
