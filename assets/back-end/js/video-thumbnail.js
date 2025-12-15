;(function ($) {
	'use strict';

	// wp-api expune wpApiSettings.root și wpApiSettings.nonce
	var restRoot = (window.wpApiSettings && window.wpApiSettings.root)
		? window.wpApiSettings.root.replace(/\/$/, '')
		: '';
	var restNonce = (window.wpApiSettings && window.wpApiSettings.nonce)
		? window.wpApiSettings.nonce
		: null;

	$(document).on('click', '#cvm-import-video-thumbnail', function (e) {
		e.preventDefault();

		var $btn         = $(this);
		var originalHtml = $btn.html();
		var data         = $btn.data();     // conține "post" și "refresh" etc.
		var postId       = data.post;

		if (!postId) {
			console.error('Missing data-post attribute.');
			return;
		}

		if (!restRoot) {
			console.error('REST API root missing (wp-api not enqueued?).');
			return;
		}

		// Dacă se apasă repetat
		if ($btn.hasClass('loading')) {
			if (window.cvm_thumb_message?.still_loading) {
				$btn.html(cvm_thumb_message.still_loading);
			}
			return;
		}

		// UI: Loading
		$btn.addClass('loading');
		if (window.cvm_thumb_message?.loading) {
			$btn.html(cvm_thumb_message.loading);
		}

		// Construim URL-ul final:
		// ex: /wp-json/vimeotheque/v1/video/123/thumbnail
		var url = restRoot + '/vimeotheque/v1/video/' + postId + '/thumbnail';

		$.ajax({
			method: 'POST',
			url: url,
			data: data, // trimite parametrii necesari: refresh, gutenberg etc.
			beforeSend: function (xhr) {
				if (restNonce) {
					xhr.setRequestHeader('X-WP-Nonce', restNonce);
				}
			}
		})
			.done(function (response) {
				// REST succes: { success: true, data: "<HTML>" }
				if (response?.success && response?.data) {
					if (typeof WPSetThumbnailHTML === 'function') {
						WPSetThumbnailHTML(response.data);
					}

					$('#cvm-thumb-response')
						.removeClass('cvm-error')
						.empty();
				} else {
					// fallback -> tratăm ca eroare
					$btn.html(originalHtml).removeClass('loading');

					var msg = response?.data || response?.message
						|| window.cvm_thumb_message?.error
						|| 'Thumbnail import failed.';

					$('#cvm-thumb-response')
						.addClass('cvm-error')
						.html(msg);
				}
			})
			.fail(function (xhr) {
				$btn.html(originalHtml).removeClass('loading');

				var msg = window.cvm_thumb_message?.error
					|| 'Thumbnail import failed.';

				// Parse WP_Error
				if (xhr.responseJSON) {
					var json = xhr.responseJSON;

					if (json.data?.details?.error?.length) {
						msg = json.data.details.error[0];
					} else if (json.message) {
						msg = json.message;
					}
				}

				$('#cvm-thumb-response')
					.addClass('cvm-error')
					.html(msg);
			});

	});

})(jQuery);
