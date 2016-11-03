 $(document).ready(function() {
   
	var p = 0, t = "", d = "", i=1;
	$('.tabs > p').each( function () {
		if($(this).text() !== ''){
			d = $(this).html();
			$(this).wrap("<div id='"+i+"'></div>");
			p++;
			i++;
		}
	});
	i=1;
	if($('.tabs > ul > li').length == p ){
		$('.tabs ul li').each( function (){
			t = $(this).text();
			$(this).text('');
			$(this).html("<a href='#"+i+"'>"+t+"</a>");
			i++;
		});
		$(".tabs").tabs();
	}else{
		$('.tabs').prepend("<div class='red-alert'><strong>[tabs] ERROR</strong>: The number of bulletted items do not match the number of paragraphs. Please make sure there is one bullet item for every paragraph.</div>");
	}
});
