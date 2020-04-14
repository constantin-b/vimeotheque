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
				ordVal = $('#cvm_order').val(),
				data = $(this).find('option:selected').data();
			
            if( data.show_user ){
                $(user_album).show();
                $('label[for=cvm_album_user]').html( data.field_label + ': ' );
                $('#cvm_album_user').attr( 'placeholder', data.placeholder );
            }else{
            	$(user_album).hide();
            }    

            if( data.show_search ){
                $(search_results).show();
            }else{
                $(search_results).hide();
            }


			$('label[for=cvm_query]').html($(this).find('option:selected').attr('title')+' :');

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