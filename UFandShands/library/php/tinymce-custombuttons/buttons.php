<?php

function loadCustomButtons() {
    $buttonArr = array();
    
    $button = new CustomButton();
    $button->title = 'Left Column';
    $button->shortCodeTag = 'left';
    $button->icon = '/images/left-col.png';
    $button->enclosing = true;
    array_push($buttonArr, $button); 
    
    
    $button = new CustomButton();
    $button->title = 'Right Column';
    $button->shortCodeTag = 'right';
    $button->icon = '/images/right-col.png';
    $button->enclosing = true;
    array_push($buttonArr, $button);   
    
    
    $button = new CustomButton();
    $button->title = 'Google Map';
    $button->shortCodeTag = 'googlemap';
    $button->icon = '/images/maps.png';
    $button->description = '<img src="/wp-content/themes/UFandShands/library/php/tinymce-custombuttons/images/shortcode-google-map.png" class="alignright" alt="Google Maps"/><p>Insert an interactive Google Map into the body of your content. Simply paste the full address of your location into the address field below.</p><p>Additional options include the ability to set the width and height of the embedded map to make it easier to fit into your body.</p>';
    $button->buttonSeparator = '|';
    $button->addField(new FormField('Address*', FormField::Text, 'address', null, 'Required. The address of the location. e.g., 1600 SW Archer Rd, Gainesville, Fl'));
    //$button->addField(new FormField('Zoom', FormField::Text, 'zoom', '13', 'The starting zoom level.'));
    $button->addField(new FormField('Width', FormField::Text, 'width', '100%', 'Width of the map. Default 100%.'));
    $button->addField(new FormField('Height', FormField::Text, 'height', '400px', 'Height of the map. Default 400px.'));
    array_push($buttonArr, $button);
    
    
    $button = new CustomButton();
    $button->title = 'Video';
    $button->shortCodeTag = 'video';
    $button->icon = '/images/video.png';
    $button->enclosing = true;
    array_push($buttonArr, $button);   
    
    
    $button = new CustomButton();
    $button->title = 'FLV';
    $button->shortCodeTag = 'flv';
    $button->icon = '/images/flv.png';
    $button->enclosing = true;
    array_push($buttonArr, $button);   
    
    
    
    $button = new CustomButton();
    $button->title = 'RSS Feed';
    $button->shortCodeTag = 'rss';
    $button->icon = '/images/rss.png';
	$button->description = '<img src="/wp-content/themes/UFandShands/library/php/tinymce-custombuttons/images/shortcode-rss.png" class="alignright" alt="RSS"/><p>Insert an RSS feed into the body of your content.</p><p>RSS feeds are listings of content that another website would like people to subscribe to or share. You can embed these feeds on your website, and everytime the feed is updated by it\'s owner, your subscription is automatically updated.</p><p> Some examples of popular feeds include:</p><p>- <a href="http://news.health.ufl.edu/feed/">HSC News</a> (http://news.health.ufl.edu/feed/)<br />- <a href="http://www.nih.gov/news/feed.xml">NIH News</a> (http://www.nih.gov/news/feed.xml)<br />- <a href="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/erss.cgi?rss_guid=12GmDGiSiGA8am4MBnaej7WxNLc8aAfIeU1_UehW0wPTWlAMck">Pubmed articles</a> (Guzick DS, http://eutils.ncbi.nlm.nih.gov/entrez/eutils/erss.cgi?rss_guid=12GmDGiSiGA8am4MBnaej7WxNLc8aAfIeU1_UehW0wPTWlAMck)';
    $button->addField(new FormField('Feed URL', FormField::Text, 'feed', '', 'The URL to the RSS feed. e.g., http://news.health.ufl.edu/feed/'));
    $button->addField(new FormField('Number of items to display', FormField::Text, 'num', '5'));
    $button->addField(new FormField('Display summary of item', FormField::Checkbox, 'summary', 'false'));
    $button->addField(new FormField('Display date of item', FormField::Checkbox, 'date', 'false'));
    array_push($buttonArr, $button);
    
    
    $button = new CustomButton();
    $button->title = 'SWF';
    $button->shortCodeTag = 'swf';
    $button->icon = '/images/swf.png';
    $button->enclosing = true;
    //$button->addField(new FormField('Width', FormField::Text, 'width', '100%'));
    //$button->addField(new FormField('Height', FormField::Text, 'height', '400'));
    array_push($buttonArr, $button);
    
    
    $button = new CustomButton();
    $button->title = 'HTML Sitemap';
    $button->shortCodeTag = 'html-sitemap';
    $button->icon = '/images/sitemap.png';
    $button->enclosing = false;
    array_push($buttonArr, $button);
    
    
    $button = new CustomButton();
    $button->title = 'Tag Cloud';
    $button->shortCodeTag = 'tagcloud';
    $button->icon = '/images/tagcloud.png';
	$button->description = '<p>Insert a listing of all of your website\'s tags (or categories). To learn more about tags in general, please visit our <a href="http://webservices.ahc.ufl.edu/help-support/how-to/categories-and-tags/" target="_new" >How To: Categories and Tags</a> website.</p><img src="/wp-content/themes/UFandShands/library/php/tinymce-custombuttons/images/shortcode-tagcloud.png" alt="Tag Cloud"/>';
    $button->addField(
		new FormField('Format', FormField::DropDown, 'format', 'flat', null,
		    array(
			new FieldOption('Flat', 'flat'),
			new FieldOption('Columns', 'columns')
		    )
		));
    $button->addField(
		new FormField('Taxonomy', FormField::DropDown, 'taxonomy', 'post_tag', null,
		    array(
			new FieldOption('Tags', 'post_tag'),
			new FieldOption('Categories', 'category')
		    )
		));
    $button->addField(new FormField('Number of tags to display', FormField::Text, 'num', '45'));
    //$button->addField(new FormField('Format', FormField::Text, 'format', 'flat'));
    $button->addField(new FormField('Smallest font size', FormField::Text, 'smallest', '8'));
    $button->addField(new FormField('Largest font size', FormField::Text, 'largest', '22'));
    //$button->addField(new FormField('Order by', FormField::Text, 'orderby', 'name'));
    $button->addField(
		new FormField('Order', FormField::DropDown, 'order', 'ASC', null,
		    array(
			new FieldOption('Ascending', 'ASC'),
			new FieldOption('Descending', 'DESC')
		    )
		));
    
    $button->addField(new FormField('Columns', FormField::Text, 'columns', '4', 'Only applies in Columns format.'));
    array_push($buttonArr, $button);
    
    
    $button = new CustomButton();
    $button->title = 'Gallery';
    $button->shortCodeTag = 'gallery';
    $button->icon = '/images/gallery.png';
    array_push($buttonArr, $button);
    
	$button = new CustomButton();
		$button->title = 'Recent Posts';
		$button->shortCodeTag = 'widget';
		$button->icon = '/images/recent-posts.png';
		$button->description = 'Insert a listing of your recent posts';
		$button->buttonSeparator = '|';
		$button->addField(new FormField('Name of Widget', FormField::Hidden, 'widget_name', 'UFCOM_recent_posts', 'This should be hidden'));
		$button->addField(new FormField('Specific category', FormField::Categories, 'specific_category_id'));
		$button->addField(new FormField('Title', FormField::Text, 'title', 'Recent Posts', 'Heading title for this section.'));
		$button->addField(new FormField('Number of posts', FormField::Text, 'numberofposts', '3', 'How many posts to display.'));
		$button->addField(new FormField('Show post excerpt', FormField::Checkbox, 'showexcerpt', 'true'));
		$button->addField(new FormField('Show thumbnails', FormField::Checkbox, 'showthumbnails', 'true'));
		$button->addField(new FormField('Show dates', FormField::Checkbox, 'showdate', 'true'));
		$button->addField(new FormField('Show RSS icon', FormField::Checkbox, 'showrssicon', 'true'));
    array_push($buttonArr, $button);
  
/*  disabled for now -- too complicated for normal users
    $button = new CustomButton();
    $button->title = 'Chart';
    $button->shortCodeTag = 'chart';
    $button->icon = '/images/charts.png';
    $button->addField(new FormField('Data', FormField::Text, 'data'));
    $button->addField(new FormField('Colors', FormField::Text, 'colors'));
    $button->addField(new FormField('Size', FormField::Text, 'size', '650x250'));
    $button->addField(new FormField('Background', FormField::Text, 'bg', 'ffffff'));
    $button->addField(new FormField('Title', FormField::Text, 'title'));
    $button->addField(new FormField('Labels', FormField::Text, 'labels'));
    $button->addField(new FormField('Advanced', FormField::Text, 'advanced'));
    $button->addField(new FormField('Type', FormField::Text, 'type', 'pie'));
    array_push($buttonArr, $button);
*/

    return $buttonArr;
}
?>
