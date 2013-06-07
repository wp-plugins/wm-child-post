/*WM Child Post FAQ Slide Script*/
jQuery('document').ready(function($){
	$('#wmchild-faq article.wmcp h3.post-title').click(function(){
		$(this).toggleClass('expand').siblings('.wm-details').slideToggle();
	});
});