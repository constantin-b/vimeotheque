=== Vimeotheque - WordPress Vimeo videos ===
Contributors: codeflavors, constantin.boiangiu
Tags: embed videos, wordpress vimeo embed, vimeo embed, vimeo plugin, video post
Requires at least: 5.2
Tested up to: 5.5.1
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create WordPress posts from Vimeo videos complete with video embed, title, description and featured image. 

== Description ==

**Vimeotheque 2** is a WordPress video importer plugin specially developed for Vimeo that allows quick importing of Vimeo videos as WordPress posts with attached video player from channels, albums, user, groups or categories. Posts are created having all details from Vimeo (title, description, video etc).

A demonstration on how easy it is to use this Vimeo WordPress plugin:

https://vimeo.com/70033244

The plugin allows video importing of single videos (needs only video ID to fill title and description and attach the video) or bulk video imports from Vimeo feeds (channels, albums, etc). Bulk importing can be done manually or automatically and importing of details is made according to your settings (ie. description can be imported as post excerpt and/or content).
Please note that before being able to perform bulk imports, you need to [register the plugin](https://developer.vimeo.com/apps/new "register Vimeo app") as an app on Vimeo (requires a valid Vimeo account). 

After importing, all videos can be embedded into posts using a visual interface that creates and places the shortcode into your post/page content. Available shortcodes are for single videos as well as video playlists.

**Features**

* Responsive design;
* Vimeo HTML5 video player support;
* Multiple video embeds on the same page;
* Compatible with the WordPress Block Editor
* Latest videos widget;
* Single video shortcode;
* Import as custom post;
* Full video import (title, description, thumbnail, video);
* Single video import;
* Manual bulk import;
* Playlist themes;
* Automatic bulk import (**PRO version only**);
* Import private videos (**PRO version only**);
* WordPress Theme support (**PRO version only**);
* Import videos as regular post type (**PRO version only**);
* Priority support (**PRO version only**).

**Important links:**

* [Vimeotheque PRO live demo](http://vvp-demo.codeflavors.com "Vimeotheque PRO live demo")
* [Vimeotheque homepage](https://vimeotheque.com/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Vimeotheque - Vimeo WordPress plugin")
* [Documentation](https://vimeotheque.com/documents/getting-started/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Vimeotheque - Vimeo WordPress plugin documentation");

== Installation ==

Like any other plugin, it can be installed manually or directly from WordPress installation Plugins page. 

Once activated a new menu entry will be created called **Videos** (look for the Vimeo logo).

In order to be able to perform any imports, you will first need to register the plugin as an app on Vimeo website. Registration can be made [here](https://developer.vimeo.com/apps/new "register Vimeo app"). Please note that you must have a Vimeo account before you can register the app.
After successfull registration, go to plugin page **Settings** and under **Vimeo authentication** enter your consumer and secret key provided by Vimeo. Now you can make bulk imports.

For a detailed tutorial on how to set up Vimeo access registration, please see [this tutorial](https://vimeotheque.com/documentation/getting-started/vimeo-oauth-new-interface/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "How to set up Vimeo OAuth").

== Frequently Asked Questions ==

= Why can't I import Vimeo videos as WordPress posts? =

Before being able to perform any imports you must first create a Vimeo App for the plugin and enter the credentials into the plugin Settings page.
For a detailed tutorial on how to set up Vimeo access registration, please see [this tutorial](https://vimeotheque.com/documentation/getting-started/vimeo-oauth-new-interface/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "How to set up Vimeo OAuth").

= How can I import hidden Vimeo videos as WordPress posts? =

If you would like to create video posts from hidden Vimeo videos you will need [Vimeotheque PRO](https://vimeotheque.com/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Vimeotheque - Vimeo WordPress plugin").

= How do I import video image as post featured image? =

For each video post created by the plugin you have the option in post edit screen to import the video image as post featured image. The option is available in "Featured Image" metabox, just click the button "Import Video image" and the plugin will take care of the rest.

== Screenshots ==

1. Manual bulk import - step 1
2. Manual bulk import - step 2 (choose videos to import)
3. Front-end playlist shortcode display

== Changelog ==
= 2.0.3 =
- Solved a bug in Video Position block that disregarded the option to embed videos in archive pages and always embedded them;
- Updated Vimeotheque hooks PHPDoc comments in plugin files;
- Introduced actions and filters to OAuth plugin settings instructions;
- Exposed REST_Api object for new endpoints registrations;
- Introduced Vimeo API request method.

= 2.0.2 =
- Introduced add-ons management that allow installation of add-ons for various purposes;
- Added option for playlist block to set alignment;
- Optimized resizing for playlist block theme Default;
- Added option for video position block to set alignment;
- Added option for single video embed to set alignment;
- Added option to display manual bulk imports by the order set on Vimeo (applies only for showcase, channel, portfolio, user uploads and folder );
- New plugin Settings option for embed alignment.

= 2.0.1 =
* Added changelog file;
* Implemented filters to hide various plugin notices;
* Solved bug that wasn't hiding the video when using the appropriate filter to prevent auto embedding in post content using the block editor.

= 2.0 =
* Plugin requires at least WordPress 5.2 and PHP 5.6;
* Vimeotheque 2 functions as base for Vimeotheque PRO 2;
* Introduced 3 blocks for the block editor: video position (for video posts), single video embed and playlist embed;
* Allows import of more than 1 tag;
* Allows setup of tags when making manual bulk imports;
* Manual bulk import allows search within the results and ordering;
* Allows import of featured image automatically, when the video is imported;
* Redesigned manual bulk import into a friendlier grid interface app;
* Redesigned single video import into a stepped process app;
* Introduced status page that can output environment varialbles useful for debugging;
* Made custom post type video slugs editable from plugin settings; 
* Vimeo player script uses the new Vimeo player API.

= 1.3.5 =
* Introduced user ID parameter to queries for Vimeo showcases.

= 1.3.4 =
* Solved WP 5.4 redirect bug from plugin Settings page;
* Removed contextual help deprecated filter.

= 1.3.3 =
* Solved a bug that prevented video imports when one of the videos in feed had an empty description.

= 1.3.2 =
* Removed SSL verification for requests to Vimeo API to prevent cURL SSL errors on installations with older libraries when making requests to the API.

= 1.3.1 =
* Renamed plugin to "Vimeotheque Lite";
* Redirected all links to new, plugin dedicated URL: https://vimeotheque.com

= 1.3 =
* Added (initial) compatibility with Gutenberg editor.

= 1.2.8 =
* Added search filter when importing videos from user uploads, channels, albums or groups.

= 1.2.7 =
* Added privacy policy information which is displayed into WordPress's Privacy Policy Guide.

= 1.2.6.1 =
* Solved a translation bug that was generating errors in plugin Settings page;
* Modified video post checking to look for the queried object instead of using the global $post variable;
* Converted all necessary Vimeo URLs to https.

= 1.2.6 =
* Added option to import video publish date on Vimeo;
* Added option to import and set 1 video tag retrieved from Vimeo;
* Added option to embed videos by iframe instead of using the JavaScript embedding;
* Added option to allow individual video posts to override the embed aspect ratio set in plugin Settings with the actual ratio of the video;
* Vimeo OAuth API keys are not visible anymore after setting and validating the keys in plugin Settings page;
* Added possibility to import video image as post featured image in video post edit screen;
* Confined review request to be displayed only on plugin pages to avoid WP admin notices clutter;
* Introduced "About" page on plugin activation that displays messages about the current update and other useful information;
* Introduced embed aspect ratio 2.35:1 in addition to 16:9 and 4:3.

= 1.2.5.4 = 
* Added styling to front-end video embed to display a loading icon before video has finished loading in page;
* Minimized front-end video embed script and created a developer file of the script;
* Modified post type "vimeo-video" to also support trackbacks, custom fields, comments, revisions and post formats; 
* Modified video import to set post format to video for all imported Vimeo videos;
* Implemented filter "cvm_import_post_format" that can be used to change post format for all videos that are going to be imported after filter implementation;
* Implemented filters [cvm_video_post_title](https://vimeotheque.com/documentation/vimeo-video-post-filters/cvm_video_post_title/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme), [cvm_video_post_content](https://vimeotheque.com/documentation/vimeo-video-post-filters/cvm_video_post_content/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme), [cvm_video_post_excerpt](https://vimeotheque.com/documentation/vimeo-video-post-filters/cvm_video_post_excerpt/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme) and [cvm_video_post_status](https://vimeotheque.com/documentation/vimeo-video-post-filters/cvm_video_post_status/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme).

= 1.2.5.3 =
* Implemented REST API functionality;
* Solved a rare bug when selecting video posts for plugin shortcode that was displaying all registered posts in website instead of only the video posts if a third party script was implementing "pre_get_posts" filter;
* Added new PHP class to handle all WP REST API functionality.

= 1.2.5.2 =
* Solved a JavaScript bug that was preventing volume to be set to 0 (mute);
* Added friendly, user dismissible notice asking for plugin review on WordPress.org (thank you in advance for your review). 

= 1.2.5.1 =
* Solved a JavaScript bug that was preventing the volume setting from being set on videos embedded by the plugin;
* Solved a bug that was setting embed color scheme to red (#FF0000) by default even when not filled.

= 1.2.5 =
* Solved a bug that was displaying the video on password protected posts even if the correct password was not provided;
* Updated several documentation links;
* Added JSON "fields" parameter to requests to Vimeo API in order to increase the number of requests per hour.

= 1.2.4 =
* Updated player embed script to only use the iframe player embed (removed deprecated Flash player entirely).
* Wrapped widget classes in conditional statements to avoid PHP errors when certain page builders are used.

= 1.2.3 = 
* Solves a rare, ocasional mixed content error when using https and images from Vimeo aren't delivered over https.

= 1.2.2 =
* Solved a bug related to playlist shortcode that was preventing videos from being embedded in certain cases.

= 1.2.1 =
* Solved a shortcode bug that was preventing videos from being embedded when using the single video shortcode in pages or posts.

= 1.2 =

* Video embed details available as data-... attributes on video div element;
* Added tags to video post type;
* Added filter 'cvm_automatic_video_embed' that can be used to prevent embeds to be made by the plugin automatically (return false from callback function);
* Added translation files;
* Added various templating and utility functions;
* Now compatible with the [tutorial](https://vimeotheque.com/documentation/advanced-tutorials/using-vimeo-video-post-plugin-in-wp-theme/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Using Vimeotheque plugin in WordPress theme") on how to create template files for the custom post type.

= 1.1.3 =

* Added custom post type "vimeo-video" archive (modified has_archive parameter to reflect public settings from Plugin settings)

= 1.1.2 =

* Vimeo video player SSL compatible

= 1.1.1 =

* Plugin compatible with WordPress 4.3 (scheduled for release on August 18th, 2015);
* Added Vimeo video albums import (not functional in version 1.1).

= 1.1 =
* Compatibility with Vimeo OAuth2;
* Restructured plugin Settings page into tabs for easier options management.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.2.6 =
After upgrading to this version, single video imports won't work anymore unless you enter your Vimeo App client identifier and client secret. More about how to register an App on Vimeo here: [How to setup Vimeo OAuth](https://vimeotheque.com/documentation/getting-started/vimeo-oauth-new-interface/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Vimeo OAuth setup")

== Troubleshooting ==

Plugin was tested with latest WordPress version and all default WordPress themes in all major browsers and also mobile devices. 
If anything is wrong on your installation, please [contact us](https://codeflavors.com/contact/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=vimeo-lite-plugin-readme "Contact us") and tell us the theme you're using, WordPress version and browser/device used for testing.