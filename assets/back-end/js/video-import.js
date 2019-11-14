/**
 * Video import form functionality
 * @version 1.0
 */
;(function($){
	$(document).ready(function(){
		var sort_row = $('#cvm_load_feed_form').find('tr.cvm_order, span.cvm_order'),
			user_album = $('#cvm_load_feed_form').find('tr.cvm_album_user, span.cvm_album_user'),
			search_results = $('#cvm_load_feed_form').find('tr.cvm_search_results, span.cvm_search_results');
		
		// search criteria form functionality
		$('#cvm_feed').change(function(){
			
			var val = $(this).val(),
				ordVal = $('#cvm_order').val();
			
			$('label[for=cvm_query]').html($(this).find('option:selected').attr('title')+' :');
			
			switch( val ){
				case 'search':
					$(sort_row).show();
					$(user_album).hide();
					$(search_results).hide();
					$('#cvm_order option[value=relevant]').removeAttr('disabled');
				break;
				// with query
				case 'album':
					$(sort_row).hide();
					$(search_results).show();
					$(user_album).show();
				break;
				// with query
				case 'channel':
					$(sort_row).hide();
					$(search_results).show();
					$(user_album).hide();
				break;
				// with query
				case 'user':
					$(sort_row).show();
					$(search_results).show();
					$(user_album).hide();
					$('#cvm_order option[value=relevant]').attr({'disabled' : 'disabled'});
				break;
				// with query
				case 'group':
					$(sort_row).show();
					$(search_results).show();
					$(user_album).hide();
					$('#cvm_order option[value=relevant]').attr({'disabled' : 'disabled'});
				break;

				case 'category':
					$(sort_row).hide();
					$(search_results).hide();
					$(user_album).hide();
				break;

				case 'ondemand_videos':
					$(sort_row).hide();
					$(search_results).hide();
					$(user_album).hide();
				break;			
			}			
		}).trigger('change');
		
		$('#cvm_load_feed_form').submit(function(e){
			var s = $('#cvm_query').val();
			if( '' == s ){
				e.preventDefault();
				$('#cvm_query, label[for=cvm_query]').addClass('cvm_error');
			}
		});
		$('#cvm_query').keyup(function(){
			var s = $(this).val();
			if( '' == s ){
				$('#cvm_query, label[for=cvm_query]').addClass('cvm_error');
			}else{
				$('#cvm_query, label[for=cvm_query]').removeClass('cvm_error');
			}	
		})
		
		/**
		 * Feed results table functionality
		 */		
		// rename table action from action (which conflicts with ajax) to action_top
		$('.ajax-submit .tablenav.top .actions select[name=action]').attr({'name' : 'action_top'});		
		// form submit on search results
		var submitted = false;
		$('.ajax-submit').submit(function(e){
			e.preventDefault();
			if( submitted ){
				$('.cvm-ajax-response')
					.html(cvm_importMessages.wait);
				return;
			}
			
			var dataString 	= $(this).serialize();
			submitted = true;
			
			$('.cvm-ajax-response')
				.removeClass('success error')
				.addClass('loading')
				.html(cvm_importMessages.loading);
			
			$.ajax({
				type 	: 'post',
				url 	: ajaxurl,
				data	: dataString,
				dataType: 'json',
				success	: function(response){
					if( response.success ){
						$('.cvm-ajax-response')
							.removeClass('loading error')
							.addClass('success')
							.html( response.success );
					}
					if( response.error ){
						$('.cvm-ajax-response')
							.removeClass('loading success')
							.addClass('error')
							.html( response.error );
					}
										
					submitted = false;
				}
			});			
		});	
	})
})(jQuery);