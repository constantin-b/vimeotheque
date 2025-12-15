/**
 * Video import form functionality
 * @version 1.1 – uses REST API for bulk import
 */
;(function($){
	$(document).ready(function(){
		var sort_row       = $('#cvm_load_feed_form').find('tr.cvm_order, span.cvm_order'),
			user_album     = $('#cvm_load_feed_form').find('tr.cvm_album_user, span.cvm_album_user'),
			search_results = $('#cvm_load_feed_form').find('tr.cvm_search_results, span.cvm_search_results'),
			orderFieldOptions = $('#cvm_order option');

		var orderField = function( resource ){
			$.each( orderFieldOptions, function( i, option ){
				var enabled = $(option).data('resources').split(',');
				if( $.inArray( resource, enabled ) !== -1 ){
					$(option)
						.show()
						.prop('disabled', false);
				}else{
					$(option)
						.hide()
						.prop('disabled', true)
						.prop('selected', false);
				}
			});
		};

		// search criteria form functionality
		$('#cvm_feed').on( 'change', function(){
			var val    = $(this).val(),
				data   = $(this).find('option:selected').data();

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

			$('label[for=cvm_query]').html(
				$(this).find('option:selected').attr('title') + ' :'
			);
			orderField( val );

		}).trigger('change');

		$('#cvm_load_feed_form').on( 'submit', function(e){
			var s = $('#cvm_query').val();
			if( '' === s ){
				e.preventDefault();
				$('#cvm_query, label[for=cvm_query]').addClass('cvm_error');
			}
		});

		$('#cvm_query').on( 'keyup', function(){
			var s = $(this).val();
			if( '' === s ){
				$('#cvm_query, label[for=cvm_query]').addClass('cvm_error');
			}else{
				$('#cvm_query, label[for=cvm_query]').removeClass('cvm_error');
			}
		});

		/**
		 * Feed results table functionality
		 */
		// rename table action from action (which conflicts with ajax) to action_top
		$('.ajax-submit .tablenav.top .actions select[name=action]')
			.attr({'name' : 'action_top'});

		// form submit on search results (bulk import)
		var submitted = false;

		$('.ajax-submit').on( 'submit', function(e){
			e.preventDefault();

			if( submitted ){
				$('.cvm-ajax-response').html( cvm_importMessages.wait );
				return;
			}

			var selected = $('input[name="cvm_import[]"]:checked').length;

			if ( selected === 0 ) {
				$('.cvm-ajax-response')
					.removeClass('success loading')
					.addClass('error')
					.html( cvm_importMessages.no_selection || 'Select at least one video to import.' );

				return;
			}

			submitted = true;

			$('.cvm-ajax-response')
				.removeClass('success error')
				.addClass('loading')
				.html( cvm_importMessages.loading );

			var dataArray = $(this).serializeArray();
			var payload   = {};

			$.each( dataArray, function( _, field ){
				var name  = field.name;
				var value = field.value;

				if ( name.slice(-2) === '[]' ) {
					name = name.slice(0, -2);
				}

				if ( payload[ name ] !== undefined ) {
					if ( ! Array.isArray( payload[ name ] ) ) {
						payload[ name ] = [ payload[ name ] ];
					}
					payload[ name ].push( value );
				} else {
					payload[ name ] = value;
				}
			});

			wp.apiFetch({
				path: '/vimeotheque/v1/videos/bulk-import',
				method: 'POST',
				data: payload
			}).then( function( response ){
				if ( response.success ) {
					$('.cvm-ajax-response')
						.removeClass('loading error')
						.addClass('success')
						.html( response.success );
				} else if ( response.error ) {
					$('.cvm-ajax-response')
						.removeClass('loading success')
						.addClass('error')
						.html( response.error );
				} else {
					$('.cvm-ajax-response')
						.removeClass('loading success')
						.addClass('error')
						.html( cvm_importMessages.unknown || 'Unknown error.' );
				}

				submitted = false;
			}).catch( function( err ){
				var message = err && err.message ? err.message : 'Request failed.';

				$('.cvm-ajax-response')
					.removeClass('loading success')
					.addClass('error')
					.html( message );

				submitted = false;
			});
		});


	});
})(jQuery);
