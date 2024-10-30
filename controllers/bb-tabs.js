jQuery(document).ready(function() {
	jQuery('.single_content').hide();
	jQuery('.navi li:first').addClass('active');
	jQuery('.single_content:first').show();
	jQuery('.navi li').click(function() {
		jQuery('.navi li').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.single_content').hide();
		var activeTab = jQuery(this).find('a').attr('title');
		jQuery(activeTab).fadeIn(500);
	});
});