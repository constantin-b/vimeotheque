<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Post_Type;
use WP_Customize_Manager;
use WP_Customize_Panel;
use WP_Error;

/**
 * Class WP_Customizer
 * @package Vimeotheque
 */
class WP_Customizer{
	/**
	 * @var Post_Type
	 */
	private $main;
	/**
	 * @var WP_Customize_Manager
	 */
	private $customize_manager;
	/**
	 * @var array
	 */
	private $options_map = [];

	private $settings;

	/**
	 * WP_Customizer constructor.
	 *
	 * @param Post_Type $main
	 */
	public function __construct( Post_Type $main ) {
		$this->main = $main;

		global $wp_customize;
		$this->customize_manager = $wp_customize;

		add_action( 'customize_register', [ $this, 'register' ] );
		add_action( 'init', [ $this, 'register_settings' ], 999 );
	}

	/**
	 * @param WP_Customize_Manager $customize_manager
	 */
	public function register( WP_Customize_Manager $customize_manager ){
		// store customize manager instance
	    $this->customize_manager = $customize_manager;
	    // add panel
		$customize_manager->add_panel(
			'vimeo-video-post',
			[
				'title' => __( 'Vimeo videos', 'cvm_video' ),
				'priority' => 200,
				'theme_supports' => '',
				'active_callback' => [ $this, 'active_callback' ]
			]
		);

		$this->add_embed_section( $customize_manager, 'vimeo-video-post' );
		$this->add_post_option_section( $customize_manager, 'vimeo-video-post' );
	}

	/**
	 * @param WP_Customize_Manager $customize_manager
	 * @param $panel_id
	 */
	public function add_embed_section( WP_Customize_Manager $customize_manager, $panel_id ){
		$options = \Vimeotheque\get_player_settings();
		$section = 'vimeo_video_post_embed_section';

		$section_description = __( 'Controls how Vimeo videos are embedded into the website.', 'cvm_video' );
		if( !$options['allow_override'] ){
			$section_description .= '<p style="color:red;">' . __( 'In order to be able to control embedding globally you must first enable option "Override individual posts options" for plugin Vimeotheque PRO, page Settings.', 'cvm_video' ) . '</p>';
		}

		$customize_manager->add_section(
			$section,
			[
				'panel' => $panel_id,
				'title' => __( 'Video embed', 'cvm_video' ),
				'priority' => 10,
				'description' => $section_description
			]
		);

		// stop here if override is not allowed
		if( !$options['allow_override'] ){
			$setting = $this->add_setting(
				'allow_override',
				[
					'type' => 'option'
				],
				'embed_option'
			);

			$customize_manager->add_control(
				$setting,
				[
					'type' => 'hidden',
					'section' => $section
				]
			);

			return;
		}

		// js embed
		$setting = $this->add_setting(
			'js_embed',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Embed by JavaScript', 'cvm_video' ),
				'description' => __( 'When checked, videos will be embedded by plugin JavaScript. If unchecked, video iframe will be placed directly into the page.', 'cvm_video' )
			]
		);

		// override player size with video own size
		$setting = $this->add_setting(
			'aspect_override',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Allow videos to override player size', 'cvm_video' ),
				'description' => __( 'When checked (recommended), player will have the exact aspect ratio as retrieved from Vimeo. Player size option will be ignored. Applies only to videos imported starting with plugin version 1.3.', 'cvm_video' )
			]
		);

		// video position
		$setting = $this->add_setting(
			'video_position',
			[
				'type' => 'option',
				'sanitize_callback' => [
					$this,
					'sanitize_video_position_option'
				]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'select',
				'section' => $section,
				'label' => __( 'Select embed position', 'cvm_video' ),
				'description' => __( 'Select the position where video will be embedded', 'cvm_video' ),
				'choices' => [
					'above-content' => __( 'Above post content', 'cvm_video' ),
					'below-content' => __( 'Below post content', 'cvm_video' )
				]
			]
		);

		// volume
		// video position
		$setting = $this->add_setting(
			'volume',
			[
				'type' => 'option'
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'number',
				'section' => $section,
				'label' => __( 'Volume', 'cvm_video' ),
				'description' => __( 'Set video playback volume (between 1 and 100).', 'cvm_video' ),
				'input_attrs' => [
					'min' => 1,
					'max' => 100,
					'step' => 1
				]
			]
		);

		// autoplay
		$setting = $this->add_setting(
			'autoplay',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Autoplay videos (not available in Customizer)', 'cvm_video' )
			]
		);

		// loop
		$setting = $this->add_setting(
			'loop',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Loop videos', 'cvm_video' )
			]
		);

		// title
		$setting = $this->add_setting(
			'title',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Show video title', 'cvm_video' )
			]
		);

		// byline
		$setting = $this->add_setting(
			'byline',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Show video author', 'cvm_video' )
			]
		);

		// portrait
		$setting = $this->add_setting(
			'portrait',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Show author portrait', 'cvm_video' )
			]
		);

		// color
		$setting = $this->add_setting(
			'color',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_color' ]
			],
			'embed_option'
		);
		$customize_manager->add_control(
			$setting,
			[
				'type' => 'color',
				'section' => $section,
				'label' => __( 'Player color', 'cvm_video' )
			]
		);

	}

	/**
	 * @param WP_Customize_Manager $customize_manager
	 * @param $panel_id
	 */
	public function add_post_option_section( WP_Customize_Manager $customize_manager, $panel_id ){
	    $options = \Vimeotheque\get_settings();
        $section = 'vimeo_video_post_post_section';

		$customize_manager->add_section(
			$section,
			[
				'panel' => $panel_id,
				'title' => __( 'Video post', 'cvm_video' ),
				'priority' => 10
			]
		);

		// embed video in archive pages
	    $setting = $this->add_setting(
	        'archives',
            [
                'type' => 'option',
                'sanitize_callback' => [ $this, 'validate_checkbox' ]
            ]
        );

	    $customize_manager->add_control(
		    $setting,
            [
                'type' => 'checkbox',
                'section' => $section,
                'label' => __( 'Embed videos in archive pages', 'cvm_video' ),
                'description' => __( 'When checked, videos will be embedded on both single post page and on all pages displaying a video loop.', 'cvm_video' )
            ]
        );
	    // include on homepage
		$setting = $this->add_setting(
			'homepage',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			]
		);

		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Add video post type to blog home page', 'cvm_video' ),
                'description' => __( 'When checked, post type "vimeo-video" posts will be added to blog home page.', 'cvm_video' )
			]
		);
		// prevent autoembed
		$setting = $this->add_setting(
			'prevent_autoembed',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			]
		);

		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Prevent video auto embed in post content', 'cvm_video' ),
                'description' => __( 'When checked, any video URLs that are into the post content will be prevented from auto embedding the video (which is a default WordPress feature).', 'cvm_video' )
			]
		);
		//make clickable
		$setting = $this->add_setting(
			'make_clickable',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			]
		);

		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Make URLs clickable', 'cvm_video' ),
				'description' => __( 'When checked, any URLs that are into the post content will be converted into clickable links.', 'cvm_video' )
			]
		);
		//add microdata
		$setting = $this->add_setting(
			'use_microdata',
			[
				'type' => 'option',
				'sanitize_callback' => [ $this, 'validate_checkbox' ]
			]
		);

		$customize_manager->add_control(
			$setting,
			[
				'type' => 'checkbox',
				'section' => $section,
				'label' => __( 'Include microdata ', 'cvm_video' ),
				'description' => __( 'When checked, the plugin will generate microdata for videos that can be used by search engines.', 'cvm_video' )
			]
		);
    }

	/**
	 * @param $checked
	 *
	 * @return bool
	 */
    public function validate_checkbox( $checked ){
	    return ( ( isset( $checked ) && true == $checked ) ? true : false );
    }

	/**
	 * @param $color
	 *
	 * @return mixed
	 */
    public function validate_color( $color ){
		$color = str_replace( '#', '', $color );
		return $color;
    }

	/**
     * Adds a setting to customizer
	 * @param $option_name
	 * @param array $args
	 *
	 * @return string
	 */
    private function add_setting( $option_name, $args = [], $option_type = 'plugin_option' ){
	    $this->options_map[ $option_name ] = $this->plugin_setting_name( $option_name, $option_type );
	    $this->customize_manager->add_setting(
	        $this->plugin_setting_name( $option_name, $option_type ),
            $args
        );
	    return $this->plugin_setting_name( $option_name, $option_type );
    }

	/**
     * Generates setting ID based on WP option name
	 * @param $option
	 *
	 * @return string
	 */
    private function plugin_setting_name( $option, $type = 'plugin_option' ){
    	$option_name = $type == 'plugin_option' ? \Vimeotheque\cvm_get_settings_option_name() : \Vimeotheque\cvm_get_player_settings_option_name();
	    return sprintf( '%s[%s]', $option_name, $option );
    }

	/**
	 * Returns customizer changeset data
	 * @return array|WP_Error $options
	 */
    public function get_changeset_data( $defaults ){
        if( !is_a( $this->customize_manager, 'WP_Customize_manager' ) ){
            return new WP_Error( 'cvm_customize_manager_not_set', 'Customize manager is not set yet.' );
        }else {
	        foreach ( $defaults as $k => $v ){
	            if( isset( $this->options_map[ $k ] ) && isset( $this->settings[ $this->options_map[ $k ] ] ) ){
		            $value = 'color' == $k ?
		                    str_replace( '#', '', $this->settings[ $this->options_map[ $k ] ] ) :
			                $this->settings[ $this->options_map[ $k ] ];

	            	$defaults[ $k ] = $value;
                }
            }

            return $defaults;
        }
    }

	/**
	 * @param $panel
	 *
	 * @return bool
	 */
	public function active_callback( WP_Customize_Panel $panel ){
		return true;
	}

	/**
	 * @param $position
	 *
	 * @return mixed
	 */
	public function sanitize_video_position_option( $position ){
		return $position;
	}

	/**
	 * If previewing in Customizer, this will set all custom settings
     * currently set in customizer
	 */
	public function register_settings(){
	    if( !is_customize_preview() ){
	        return;
        }

	    $options = $this->customize_manager->unsanitized_post_values();
	    if( $options ) {
		    $this->settings = $options;
	    }
    }
}