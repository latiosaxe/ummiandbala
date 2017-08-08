jQuery(document).ready(function($) {

/* 	=============================
   	Variables 
   	============================= */
   	
	$variations_form = $('form.variations_form');
	$img_container = $('#jckWooThumbs_img_wrap');
	$swatches = ($('#swatches-and-photos-css').length > 0) ? true : false;
	
/* 	=============================
   	On Doc Ready 
   	============================= */

	triggerSlider();
	
/* 	=============================
   	Found Variation 
   	============================= */
   	
   	// This triggers a show_variations event, if it hasn't happened already and a default var is set for the product

	$variations_form.on( 'show_variation', function( event, variation ) {
		if(!$img_container.hasClass('loading')){
			
			$img_container.addClass('loading');
			$img_container.removeClass('reset');
			
			// If swatches plugin is installed.
			if($swatches){
				$var_id = $('input[name=variation_id]').val();
			} else {
				$var_id = variation.variation_id;
			}
			
			$args = {};			
			$args.var_id = $var_id;
		
			loadNewImages($args);
			
		}
	   
	});
	
/* 	=============================
   	Reset Images 
   	============================= */
   	
   	$variations_form.on('reset_image', function(){
		jck_wt_reset_imgs();
	});
	
	function jck_wt_reset_imgs(){
		if(!$img_container.hasClass('reset') && !$img_container.hasClass('loading')){		
		
			$img_container.addClass('loading');	
			$img_container.addClass('reset');
				
			$var_id = $img_container.attr('data-parentid');
			$args = {};			
			$args.var_id = $var_id;		
			
			loadNewImages($args);
		}
	}
	
	if($swatches){
		$variations_form.on('change', function(){
			$var_id = $('input[name=variation_id]').val();
			if($var_id == ""){
				jck_wt_reset_imgs();
			}
		});
	}
/* 	=============================
   	Functions 
   	============================= */
   	
   	function triggerSlider(){
   		var thumbOr = (jck_wt_vars.options.thumbnailLayout == 'above' || jck_wt_vars.options.thumbnailLayout == 'below') ? 'horizontal' : 'vertical';
   		var thumbsFitIn = (thumbOr == 'vertical') ? true : false;
   		
   		$("."+jck_wt_vars.slug).css('display', 'block');
   		
	   	$("."+jck_wt_vars.slug).royalSlider({
			autoScaleSlider: true,
			autoScaleSliderWidth: jck_wt_vars.options.sliderRatio.width, // Dynamic
			autoScaleSliderHeight: jck_wt_vars.options.sliderRatio.height, // Dynamic
			imageScalePadding: 0,
			imageScaleMode: jck_wt_vars.options.scaleMode,
			keyboardNavEnabled: true,
			arrowsNav: jckTrueFalse(jck_wt_vars.options.enableArrows),
			arrowsNavAutoHide: jckTrueFalse(jck_wt_vars.options.arrowsAutohide),
			transitionType: jck_wt_vars.options.imageTransition,
			slidesOrientation: jck_wt_vars.options.slideDirection,
			controlNavigation: (jckTrueFalse(jck_wt_vars.options.enableNavigation) ? jck_wt_vars.options.navigationType : "none"),
			slidesSpacing: parseInt(jck_wt_vars.options.slideSpacing, 10),
			transitionSpeed: parseInt(jck_wt_vars.options.slideSpeed, 10),
			autoHeight: false,
			thumbs: {
				fitInViewport: thumbsFitIn,
				spacing: parseInt(jck_wt_vars.options.thumbnailSpacing),
				orientation: thumbOr,
				autoCenter: jckTrueFalse(jck_wt_vars.options.enableCentering),
				transitionSpeed: parseInt(jck_wt_vars.options.thumbnailSpeed),
				arrows: jckTrueFalse(jck_wt_vars.options.enableThumbArrows),
				arrowsAutoHide: jckTrueFalse(jck_wt_vars.options.thumbArrowsAutohide)
			}
		});
		
		triggerEffects();
		
   	}
   	
   	// ! Trigger Effects
   	
   	function triggerEffects(){
   		var $slider = $("."+jck_wt_vars.slug).data('royalSlider');
   		
   		if($slider) {
	   		if(jckTrueFalse(jck_wt_vars.options.enableLightbox)) triggerFullscreen();
	   		if(jckTrueFalse(jck_wt_vars.options.enableZoom)) triggerZoom();
		} else {
			triggerEffects();
		}
   	}
   	
   	// ! Zoom Functions
   	
   	function initZoom(){
	   	var $slider = $("."+jck_wt_vars.slug).data('royalSlider');
	   	
	   	$slide = $($slider.currSlide.content);
		$slideImg = $slide.find('img');
		$slideImg.addClass('currZoom');
	    var lrgImg = $slideImg.attr('data-jckLargeImg');
	    $slideImg.ImageZoom({
	    	type: jck_wt_vars.options.zoomType, 
	    	bigImageSrc: lrgImg, 
	    	zoomSize: [jck_wt_vars.options.zoomDimensions.width,jck_wt_vars.options.zoomDimensions.height],
	    	zoomViewerClass: 'shape'+jck_wt_vars.options.innerShape,
	    	position: jck_wt_vars.options.zoomPosition
	    });
   	}
   	
   	function destroyZoom(){
	   	if($('.currZoom').length > 0){
	   		var $zoom = $('.currZoom').data('imagezoom');
	   		$('.currZoom').removeClass('currZoom');
	   		$zoom.destroy();
   		}
   	}
   	
   	function triggerZoom(){
   		var $slider = $("."+jck_wt_vars.slug).data('royalSlider');
   		
   		destroyZoom();   		
   		initZoom();
   		
   		$slider.ev.on('rsAfterSlideChange', function(event) {	
   			initZoom();
		});
		
		$slider.ev.on('rsBeforeMove', function(event, type, userAction ) {
			// if it's not the last or first slide, destroy it.
			if($slider.currSlideId+1 != $slider.numSlides && $slider.currSlideId != 0){
				destroyZoom();
			}
		});
   	}
   	
   	// ! Fullscreen Functions
   	
   	function triggerFullscreen(){
	   	var $slider = $("."+jck_wt_vars.slug).data('royalSlider');
	   	
	   	setTimeout(function(){
		   	initPrettyPhoto();
	   	}, 500);
		
		$slider.ev.on('rsAfterSlideChange', function(event) {	
   			initPrettyPhoto();
		});
   	}
   	
   	function initPrettyPhoto(){
	   	$("a[rel^='prettyPhoto']").prettyPhoto({
                social_tools: false,
                theme: 'pp_woocommerce',
                horizontal_padding: 20,
                opacity: 0.8,
                deeplinking: false
        });
   	}
   	
   	// ! Image Loader
   	
   	function loadNewImages($args, callback){
   	
   		$currShowing = $img_container.attr('data-showing');
   		
   		if($currShowing != $var_id){
   	
		   	var $slider = $("."+jck_wt_vars.slug).data('royalSlider');
					
			var ajaxargs = {
				'action': 			'load_images',
				'nonce':   			jck_wt_vars.nonce,
				'variation':		$args.var_id,
			}
		
			$.ajax({
				url: jck_wt_vars.ajaxurl,
				data: ajaxargs
			}).success(function(data) {
				if(data.response == 'success'){
					// Destroy current slider
					$slider.destroy();
					// Insert new content
					$img_container.html(data.content);
					// Restart slider
					triggerSlider();
					// Change the "showing" var ID
					$img_container.attr('data-showing', $args.var_id).removeClass('loading');
					
					// Run a callback, if required
					if(callback != undefined) {
						callback(data);
					}
				}
			});
		
		} else {
			$img_container.removeClass('loading');
		}
   	}
   	
   	/* Helpers */
   	
   	function jckTrueFalse(val){
	   	return (val == 1) ? true : false;
   	}

});