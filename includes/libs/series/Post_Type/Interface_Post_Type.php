<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Post_Type;

interface Interface_Post_Type {

	public function get_post_name();
	public function get_category_taxonomy();
	public function get_tag_taxonomy();
}
