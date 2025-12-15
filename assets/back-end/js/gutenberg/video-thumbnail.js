;(function ($) {
	$(document).ready(function () {
		var el  = wp.element.createElement,
			__  = wp.i18n.__,
			elDescription = __(
				'Imports Vimeo image as post featured image. If image already exists it will be duplicated.',
				'codeflavors-vimeo-video-post-lite'
			);

		// Baza REST (wp-json) + nonce – trebuie să fie disponibile în editor
		var restRoot = (window.wpApiSettings && window.wpApiSettings.root)
			? window.wpApiSettings.root.replace(/\/$/, '') // scoatem slash-ul final
			: '';

		var restNonce = (window.wpApiSettings && window.wpApiSettings.nonce)
			? window.wpApiSettings.nonce
			: null;

		/**
		 * Wrapper pentru componenta Featured Image în Gutenberg
		 */
		function wrapPostFeaturedImage(OriginalComponent) {
			return function (props) {
				return el(
					wp.element.Fragment,
					{},
					null,
					el(OriginalComponent, props),
					el('hr'),
					el(
						'a',
						{
							href: '#',
							id: 'cvm-import-video-thumbnail',
							onClick: function (event) {
								importFeaturedImage(event, props);
							}
						},
						__('Import Vimeo image', 'codeflavors-vimeo-video-post-lite')
					),
					el(
						'p',
						{
							className: 'description',
							id: 'cvm-thumb-response'
						},
						elDescription
					)
				);
			};
		}

		/**
		 * Click handler pentru butonul „Import Vimeo image”
		 */
		function importFeaturedImage(e, props) {
			e.preventDefault();

			var $btn = $(e.target);
			var originalHtml = $btn.html();

			if (!restRoot) {
				console.error('REST API root missing (wp-api not enqueued?).');
				return;
			}

			if ($btn.hasClass('loading')) {
				$btn.html(__('... still loading', 'codeflavors-vimeo-video-post-lite'));
				return;
			}

			// Construim payload:
			// - refresh: true dacă există deja featured image
			// - gutenberg: flag pentru server
			var data = {
				refresh: !!props.featuredImageId,
				gutenberg: 1
			};

			$btn.addClass('loading').html(__('... loading', 'codeflavors-vimeo-video-post-lite'));

			// URL REST: /wp-json/vimeotheque/v1/video/{post_id}/thumbnail
			var url =
				restRoot +
				'/vimeotheque/v1/video/' +
				props.currentPostId +
				'/thumbnail';

			$.ajax({
				method: 'POST',
				url: url,
				data: data,
				beforeSend: function (xhr) {
					if (restNonce) {
						xhr.setRequestHeader('X-WP-Nonce', restNonce);
					}
				}
			})
				.done(function (response) {
					// Ne așteptăm la { success: true, data: { attachment_id, post_id } }
					if (response && response.success && response.data && response.data.attachment_id) {
						// Setăm featured_media în editor
						wp.data
							.dispatch('core/editor')
							.editPost({ featured_media: response.data.attachment_id });

						$btn
							.removeClass('loading')
							.html(__('Import Vimeo image', 'codeflavors-vimeo-video-post-lite'));

						$('#cvm-thumb-response')
							.removeClass('cvm-error')
							.addClass('cvm-success')
							.html(__('Image imported successfully.', 'codeflavors-vimeo-video-post-lite'));
					} else {
						// Răspuns neașteptat, tratăm ca eroare generică
						$btn.html(originalHtml).removeClass('loading');

						var msg =
							(response && response.data) ||
							(response && response.message) ||
							__('Thumbnail import failed.', 'codeflavors-vimeo-video-post-lite');

						$('#cvm-thumb-response')
							.removeClass('cvm-success')
							.addClass('cvm-error')
							.html(msg);
					}

					setTimeout(function () {
						$('#cvm-thumb-response')
							.removeClass('cvm-error cvm-success')
							.html(elDescription);
					}, 3000);
				})
				.fail(function (xhr) {
					$btn.html(originalHtml).removeClass('loading');

					var msg = __('Thumbnail import failed.', 'codeflavors-vimeo-video-post-lite');

					if (xhr.responseJSON) {
						var json = xhr.responseJSON;

						if (json.data && json.data.details && json.data.details.error && json.data.details.error.length) {
							msg = json.data.details.error[0];
						} else if (json.message) {
							msg = json.message;
						}
					}

					$('#cvm-thumb-response')
						.removeClass('cvm-success')
						.addClass('cvm-error')
						.html(msg);

					setTimeout(function () {
						$('#cvm-thumb-response')
							.removeClass('cvm-error cvm-success')
							.html(elDescription);
					}, 3000);
				});
		}

		wp.hooks.addFilter(
			'editor.PostFeaturedImage',
			'vimeo-video-post/post-featured-image',
			wrapPostFeaturedImage
		);
	});
})(jQuery);
