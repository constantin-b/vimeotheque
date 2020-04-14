<?php

namespace Vimeotheque\Admin;

use Vimeotheque\Plugin;
use Vimeotheque\Post\Register_Post;
use Vimeotheque\Vimeo_Api\Resource_Objects;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Helper_Admin {

	static public function docs_link( $path ){
		return \Vimeotheque\cvm_link( 'documentation/' . trailingslashit( $path ), 'doc_link' );
	}

	static public function aspect_ratio_select( $args = [], $echo = true ){

		$defaults = [
			'name'		=> false,
			'id'		=> false,
			'class'		=> '',
			'selected'	=> false,
			'before'	=> '',
			'after'		=> ''
		];
		$o = wp_parse_args($args, $defaults);
		$o['options'] = [
			'4x3' 	=> '4:3',
			'16x9' 	=> '16:9',
			'2.35x1' => '2,35:1'
		];

		$select = self::select( $o, false );
		if( $echo ){
			echo $select;
		}else{
			return $select;
		}
	}

	static public function select( $args = [], $echo = true ){

		$defaults = [
			'options' 	=> [],
			'name'		=> false,
			'id'		=> false,
			'class'		=> '',
			'selected'	=> false,
			'use_keys'	=> true,
			'before'	=> '',
			'after'		=> ''
		];

		$o = wp_parse_args($args, $defaults);

		if( !$o['id'] ){
			$output = sprintf( '<select name="%1$s" id="%1$s" class="%2$s">', $o['name'], $o['class']);
		}else{
			$output = sprintf( '<select name="%1$s" id="%2$s" class="%3$s">', $o['name'], $o['id'], $o['class']);
		}

		foreach( $o['options'] as $val => $option ){

			if( is_array( $option ) ){
				$title = isset( $option['title'] ) ? $option['title'] : '';
				$data = '';
				if( isset( $option['data'] ) ){
					foreach( $option['data'] as $_key => $_value ){
						$data .= sprintf( 'data-%s="%s" ', $_key, $_value );
					}
				}
				$option = $option['text'];
			}else{
				$title = '';
				$data = '';
			}

			$value = $o['use_keys'] ? $val : $option;
			$c = $o['use_keys'] ? $val == $o['selected'] : $option == $o['selected'];
			$checked = $c ? ' selected="selected"' : '';
			$output .= sprintf(
				'<option value="%1$s" title="%4$s"%2$s %5$s>%3$s</option>',
				$value,
				$checked,
				$option,
				$title,
				$data
			);
		}

		$output .= '</select>';

		if( $echo ){
			echo $o['before'].$output.$o['after'];
		}

		return $o['before'].$output.$o['after'];
	}

	static public function check( $val, $echo = true ){
		$checked = '';
		if( is_bool($val) && $val ){
			$checked = ' checked="checked"';
		}
		if( $echo ){
			echo $checked;
		}else{
			return $checked;
		}
	}

	/**
	 * @param $name
	 * @param bool $selected
	 * @param string $id
	 *
	 * @return string
	 */
	static public function select_feed_source( $name, $selected = false, $id = '' ){
		$sources = Resource_Objects::instance()->get_resources();

		$options = [];

		foreach( $sources as $key => $source ){
			if( $source->is_single_entry() ){
				continue;
			}

			$options[ $key ] = [
				'text' => $source->get_output_name(),
				'title' => sprintf( __( 'Enter %s', 'cvm_video' ), $source->get_output_name() ),
				'data' => [
					'show_user' => $source->requires_user_id(),
					'field_label' => esc_attr( $source->label_user_id() ),
					'placeholder' => esc_attr( $source->placeholder_user_id() ),
					'show_search' => $source->can_search_results()
				]
			];
		}

		return Helper_Admin::select([
			'options' => $options,
			'name' => $name,
			'id' => $id,
			'selected' => $selected,
			'use_keys' => true
		]);
	}

	static public function select_playlist_theme( $name, $selected = false, $id = '', $class = '' ){
		$themes = Plugin::instance()->get_playlist_themes()->get_themes();
		$options = [];
		foreach( $themes as $theme ){
			$options[ $theme->get_folder_name() ]=  $theme->get_theme_name();
		}

		return Helper_Admin::select([
			'options' => $options,
			'name' => $name,
			'id' => $id,
			'selected' => $selected,
			'use_keys' => true,
			'class' => $class
		]);
	}

	/**
	 * @param $name
	 * @param bool $selected
	 * @param string $id
	 * @param string $class
	 *
	 * @return string|void
	 */
	static public function select_post_type( $name, $selected = false, $id = '', $class = '' ){
		/**
		 * @var Register_Post[] $types
		 */
		$types = Plugin::instance()->get_registered_post_types()->get_post_types();

		if( count( $types ) == 1 ){
			$type = current( $types );
			printf(
				'<input type="hidden" name="%s" value="%s" id="%s" class="%s" /><label>%s</label>',
				$name,
				$type->get_post_type()->name,
				$id,
				$class,
				$type->get_post_type()->labels->singular_name
			);
			return;
		}

		$options = [];
		foreach( $types as $type ){
			$options[ $type->get_post_type()->name ] = $type->get_post_type()->labels->singular_name;
		}

		return Helper_Admin::select([
			'options' => $options,
			'name' => $name,
			'id' => $id,
			'selected' => $selected,
			'use_keys' => true,
			'class' => $class
		]);
	}

	/**
	 * @param $post_type
	 *
	 * @return Register_Post|null
	 */
	static public function get_registered_post_type( $post_type ){
		return Plugin::instance()->get_registered_post_types()->get_post_type( $post_type );
	}
}