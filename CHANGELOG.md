2.1.1 [8/June/2021]
- Solved a Block Editor error that caused the Vimeotheque Playlist Block to crash when using the playlist block with an option to change the videos order;
- Removed deprecated jQuery functions that caused jQueryMigrate messages in console;
- Improved detection of variables when saving options in WP Admin;
- Solved a bug in Vimeotheque Playlist script that prevented the videos to autoplay one after the other when option to loop the playlist was on;
- Prevented lazy-loading in Vimeotheque Playlists (not needed and counter intuitive).

2.1 [24/May/2021]
- Added new option in plugin settings "Embed options" for option "Display video" to embed video in video posts in place of the featured image;
- Added new option in plugin settings "Embed options" to lazy load videos;
- Added new option in plugin settings "Embed options" to set the play icon color for lazy loaded videos;
- Added new individual video post option in Classic editor under "Display video" to embed video in place of the featured image;
- Added new individual video post option in Classic editor to lazy load video;
- Added new individual video post option in Block editor under "Embedding options" to replace the featured image with the video embed;
- Added new individual video post option in Block editor under "Embedding options" to lazy load video;
- Solved a rare bug that caused a "TypeError" in some cases (Vimeotheque\Front_End::skipped_autoembed() must be an instance of WP_Post, instance of stdClass given);
- Solved a bug in playlist theme "Default" that wasn't switching the class "active-video" between items when loop option was on.

2.0.21 [29/Apr/2021]
- Solved a bug in Video Position Block that cause post saving error/notice when editing a video post managed by Vimeotheque;
- Changed Video Position Block options "Video start time" and "Volume" to range controls;
- Added new option in Video Position Block for video embed background transparency;
- Added new option for videos edited using the Classic Editor to set the video embed background transparency;
- Increased Vimeotheque minimum WordPress version requirement to  WordPress 5.3 ( for support of object meta type in the REST API );
- Made video background transparency a global option in Vimeotheque Settings, under tab "Embed options";
- Solved a bug in Video Player script implemented by Vimeotheque which caused the player to ignore the embed volume option.

2.0.20 [21/Apr/2021]
- Solved a bug in Playlist shortcode and Playlist block that prevented manually selected "vimeo-video" posts from being displayed into the playlist while option "Video post is public" was checked in plugin settings;
- Solved a bug in Playlist block that caused the block to crash when selecting videos imported as regular posts.

2.0.19 [19/Apr/2021]
- Solved a bug in playlist theme "Default" that prevented clicking on the read more link when showing the excerpts into the playlist.

2.0.18 [16/Apr/2021]
- Solved a bug that issued error "Call to a member function get_page() on null" when Jetpack installed.

2.0.17 [12/Apr/2021]
- Added option for muted video in Classic editor;
- Added option for muted video in Video Position block;
- Added option for background mode in Classic editor;
- Added option for background mode in Video Position block;
- Added options dependency in Classic editor which hides options that don't apply when certain options are selected (ie. background mode disables a number of options).

2.0.17-alpha.2 [12/Apr/2021]
- Added option for Classic editor to set the video start time when editing a video;
- Added option for Block editor to set the video start time when editing a video.

2.0.17-alpha.1 [6/Apr/2021]
- Order showcases by default by "modified_time";
- Order user uploads feed by default by "date".

2.0.16 [29/March/2021]
- Solved an issue with importers that were prevented from using the default sorting value;
- Solved a rare bug that caused errors when checking for duplicates and the feed returned from the Vimeo API was empty.

2.0.15 [8/March/2021]
- Created a new option in Block Editor for playlist theme "Default" to display video thumbnails using the original size ratio (thumbnails in list might have different size) or have them displayed with the same size (thumbnails in list might have black bars);
- Created a new option in Classic Editor shortcode visual interface for theme "Default" to display video thumbnails size ratio in original size or the same size for all thumbnails.

2.0.14 [2/March/2021]
- Video player adds class "loaded" on the video container once the video is loaded;
- Modified video player display to remove the black background and loader image after the video has loaded;
- Improved processing of tabs in plugin Settings.

2.0.13 [18/Feb/2021]
- Solved a bug in Video Playlist Widget that caused the widget to display videos from all categories even if a category was selected from the widget options. 

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