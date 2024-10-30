jQuery(function(){
	jQuery.fn.bb_scrollToTop = function() {
		jQuery(this).hide().removeAttr("href");
		if(jQuery(window).scrollTop()!="0"){
			jQuery(this).fadeIn("slow")
		}
		var scrollDiv=jQuery(this);
		jQuery(window).scroll(function() {
			if(jQuery(window).scrollTop()=="0") {
				jQuery(scrollDiv).fadeOut("slow")
			}
			else{
				jQuery(scrollDiv).fadeIn("slow")
			}
		});
		jQuery(this).click(function() {
			jQuery("html, body").animate({scrollTop:0},"slow")
		})
	}
});
jQuery(document).ready(function() {
	jQuery.fn.adjustPanel = function(){ 
		jQuery(this).find("ul, .subpanel").css({ 'height' : 'auto'});
		
		var windowHeight = jQuery(window).height();
		var panelsub = jQuery(this).find(".subpanel").height();
		var panelAdjust = windowHeight - 100;
		var ulAdjust =  panelAdjust - 25;
		
		if ( panelsub >= panelAdjust ) {
			jQuery(this).find(".subpanel").css({ 'height' : panelAdjust });
			jQuery(this).find("ul").css({ 'height' : ulAdjust});
		}
		else if ( panelsub < panelAdjust ) {
			jQuery(this).find("ul").css({ 'height' : 'auto'});
		}
	};
	
	jQuery("#popular-posts").adjustPanel();
	jQuery("#latest-posts").adjustPanel();
	jQuery("#latest-comments").adjustPanel();
	jQuery("#share").adjustPanel();
	jQuery("#admin").adjustPanel();
	
	jQuery(window).resize(function () { 
		jQuery("#popular-posts").adjustPanel();
		jQuery("#latest-posts").adjustPanel();
		jQuery("#latest-comments").adjustPanel();
		jQuery("#share").adjustPanel();
		jQuery("#admin").adjustPanel();
	});
	jQuery("#popular-posts a:first, #latest-posts a:first, #latest-comments a:first, #admin a:first, #share a:first").click(function() { 
		if(jQuery(this).next(".subpanel").is(':visible')){ 
			jQuery(this).next(".subpanel").hide();
			jQuery("#bottom-bar li a").removeClass('active');
		}
		else {
			jQuery(".subpanel").hide();
			jQuery(this).next(".subpanel").toggle();
			jQuery("#bottom-bar li a").removeClass('active');
			jQuery(this).toggleClass('active');
		}
		return false;
	});
	jQuery(document).click(function() {
		jQuery(".subpanel").hide();
		jQuery("#bottom-bar li a").removeClass('active');
	});
	jQuery('.subpanel ul').click(function(e) { 
		jQuery.stopPropagation();
	});
	jQuery("#bb_toTop").bb_scrollToTop();
});
