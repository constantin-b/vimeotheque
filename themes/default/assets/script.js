/**
 * Theme Default
 */

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};

;(function( exports, $ ){	

    themeDefault = function(){
    	$('.cvm-vim-playlist.default:not(.loaded)').CVM_Player_Default();
		
		$.each( $('.cvm-vim-playlist.default:not(.loaded)'), function(i, p){
			$(this).find('.playlist-visibility').on( 'click', function(e){
				e.preventDefault();
				if( $(this).hasClass('collapse') ){
					$(this).removeClass('collapse').addClass('expand');
					$(p).find('.cvm-playlist').slideUp();
				}else{
					$(this).removeClass('expand').addClass('collapse');
					$(p).find('.cvm-playlist').slideDown();
				}
			})
			
			if( $(p).is( '.left, .right' ) ){
				var playlist = $(p).find('.cvm-playlist-wrap'),
					videoPlayer = $(p).find( '.cvm-player' ),
					c = $(p).attr('class');

				var f = function(){
					if( $(playlist).find('.cvm-playlist').outerWidth() < 350 ){
						$(p).removeClass('left right');
					}else{
						var h = $(videoPlayer).outerHeight();
						$(playlist).css({height:h});
						$(p).addClass(c);
					}
				}

				f();	
				$(window).resize(f);				
			}
			
            $(this).addClass('loaded');
		});
    }

    exports.themeDefault = themeDefault;
    
})( vimeotheque, jQuery );

;(function($){	
	$(document).ready(function(){	
		vimeotheque.themeDefault();
	});	
})( jQuery );