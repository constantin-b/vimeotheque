/**
 * Playlist creation/editing script 
 */
;(function($){
	
	$(document).ready(function(){
		
		var submitted 	= false,
			om			= $('#cvm_check_playlist').html(),
			message 	= $('#cvm_check_playlist');
		
		$('#playlist_id').keydown(function(){
			$(message).html(om);
		});
		
		$('#cvm_verify_playlist').click(function(e){
			e.preventDefault();
			$(this).addClass('loading');
			
			if( submitted ){
				return;
			}
			submitted = true;
			$(message).html( VMTQ_MSG.messages.wait );
			
			var playlist_id 	= $('#playlist_id').val(),
				playlist_type 	= $('#playlist_type').val();
			
			var data = {
				'action' 	: 'cvm_check_playlist',
				'id'		: playlist_id,
				'album_user': $('#album_user').val(),
				'type'		: playlist_type
			};
			
			$.ajax({
				type 	: 'post',
				url 	: ajaxurl,
				data	: data,
				success	: function( response ){
					$(message).html( response );
					submitted = false;
				}
			});			
		});
		
		$('#playlist_type').change(function(){
			var v = $(this).val(),
				data = $(this).find(':selected').data();

			toggleOwner( 'album' == v );
			toggleNewVideos( 1 == data.import_new_videos );
			

			$('#new-videos-after-import')

		});
		
		var v = $('#playlist_type').val();
		toggleOwner( 'album' == v );	

		var data = $(this).find(':selected').data();
		toggleNewVideos( data.import_new_videos );	
	});	
	
	
	var toggleOwner = function( show ){
		if( show ){
			$('#album_owner').show();
		}else{
			$('#album_owner').hide();
		}
	}

	var toggleNewVideos = function( show ){
		show = parseInt( show );
		
		if( show ){
			$('#new-videos-after-import').show();
		}else{
			$('#new-videos-after-import').hide();
		}
	}

	
})(jQuery);