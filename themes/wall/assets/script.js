/**
 * 
 */
;(function($){
	$(document).ready(function(){
		//*
		 $('.cvm-playlist-wall').masonry({
		 	itemSelector: '.cvm-playlist-item',
		 	columnWidth: 200,
		 	gutter:15,
		 	horizontalOrder: true,
		 	fitWidth: true,
		 	resize:true
		 });
		//*/
		
		$.each( $('.cvm-playlist-wall img.cvm-preload'), function(){
			
			var img_src = $(this).data('src'),
				self 	= this;
			
			$('<img />',{
				'src' : img_src
			}).load(function(){
				$(self)
					.attr('src', img_src)
					.css('opacity', 0)
					.removeClass('cvm-preload')
					.addClass('cvm-loaded')
					.animate({
							'opacity':1
						},{
							'duration':800
					});
			})
			
		});
		
	});
})(jQuery);