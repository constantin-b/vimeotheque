2.0.12 [9/Feb/2021]
- Added date limit for showcase and channel;
- Made image preloader in playlist themes to use the 640px wide image version for videos.

2.0.11 [19/Jan/2021]
- Solved a bug that caused the Video Playlist Block to crash when custom post type "vimeo-video" had no categories set up;
- Added "empty results" message to Video Playlist Block modal window if there are no categories set up for the plugin's custom post type;
- Improved display of options for Video Playlist Block theme "Default".

2.0.10 [14/Jan/2021]
- Solved a bug that prevented the "Add new" plugin admin page from being displayed in some cases (ie. when using WooCommerce without the Classic editor plugin).

2.0.9 [31/Dec/2020]
- Solved a bug in single video embed block that was causing the options for "Loop video" and "Autoplay video" to be always on.

2.0.8 [23/Dec/2020]
- Solved a bug in block "Video position" which caused the player color to be loaded incorrectly when loading the default color set in plugin Settings under Embed options; 
- Improved video position block for Block editor to allow additional parameters to be set;
- Added new parameter to filter "vimeotheque\player\embed-parameters" which passes any manually set embed options;
- Added new action "vimeotheque\automatic_embed_in_content" which is triggered when Vimeotheque embeds videos into the post content automatically (normally, when the Classic editor is used instead of the Block editor);
- Added new action "vimeotheque\editor\classic-editor-options-output" which is triggered when Vimeotheque displays the embedding options in post edit screen in Classic editor;
- Introduced actions and filters that allow third party plugins to add new block editor options to video position block.

2.0.7 [17/Dec/2020]
- Added filter "vimeotheque\player\embed-parameters" that allows extra parameters to be added to the video embed iframe URL;
- Updated translation file for Romanian.

2.0.6 [20/Nov/2020]
- Created new option for playlist block to display post excerpts in playlists for theme Default;
- Created new option for playlist block to allow various posts ordering options;
- Created new option for playlist widget to display post excerpts in playlists when using theme Default;
- Created new option for playlist shortcode in Classic editor to display post excerpts when using theme Default;
- Created new option for playlist shortcode to allow various posts ordering options;
- Introduced support for AMP plugin.

2.0.5 [18/Nov/2020]
- Solved occasional single video import error caused by conflicts with third party plugins;
- Introduced player embed option to prevent tracking users, including all cookies and stats;
- Show manually selected videos in playlist shortcode into the exact order that they were selected;
- Preserve videos order in playlist block same as the order they were selected;
- Hide video position block that is introduced automatically into the block editor for Vimeotheque video posts if automatic embedding is disabled by filter.

2.0.4 [3/Nov/2020]
- Stop video player script in case of player error to avoid JavaScript errors in page;
- Re-initialize video playlist script in case the player script returned an error;
- Compatibility with WP plugin "Complianz – GDPR/CCPA Cookie Consent" by "Really Simple Plugins".

2.0.3 [30/Oct/2020]
- Solved a bug in Video Position block that disregarded the option to embed videos in archive pages and always embedded them;
- Updated all Vimeotheque hooks PHPDoc comments;
- Introduced actions and filters to OAuth plugin settings instructions;
- Exposed REST_Api object for new endpoints registrations;
- Introduced Vimeo API request method.

2.0.2 [7/Oct/2020]
- Introduced add-ons management that allow installation of add-ons for various purposes;
- Added option for playlist block to set alignment;
- Optimized resizing for playlist block theme Default;
- Added option for video position block to set alignment;
- Added option for single video embed to set alignment;
- Added option to display manual bulk imports by the order set on Vimeo (applies only for showcase, channel, portfolio, user uploads and folder );
- New plugin Settings option for embed alignment;
- Allow post registration without a valid taxonomy;
- Updated block editor playlist and video blocks to hide the categories select box if no taxonomy is attached to the post type.

2.0.1 - [14/Sep/2020]
- Solved a bug that wasn't hiding the video if video was published as block element and filter to prevent the video embed was on;
- Implemented filters "vimeotheque\admin\notice\vimeo_api_notice" and "vimeotheque\admin\notice\review_notice" that can be used to hide plugin notices.

2.0 - [14/Sep/2020]

- Initial release of version 2.0