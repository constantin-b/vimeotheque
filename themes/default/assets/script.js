/**
 * Theme Default
 */

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.themes = vimeotheque.themes || {};

;(function( exports, $ ){	

    themeDefault = function(){
    	$('.cvm-vim-playlist.default:not(.loaded)').VimeoPlaylist();
		
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
					videoPlayer = $(p).find( '.vimeotheque-player' ),
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
    
}( vimeotheque.themes, jQuery ));

;(function($){	
	$(document).ready(function(){	
		vimeotheque.themes.themeDefault();
	});	
}( jQuery ));