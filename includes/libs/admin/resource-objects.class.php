<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Vimeo_Api\Album_Resource;
use Vimeotheque\Vimeo_Api\Category_Resource;
use Vimeotheque\Vimeo_Api\Channel_Resource;
use Vimeotheque\Vimeo_Api\Group_Resource;
use Vimeotheque\Vimeo_Api\Ondemand_Resource;
use Vimeotheque\Vimeo_Api\Portfolio_Resource;
use Vimeotheque\Vimeo_Api\Resource_Abstract;
use Vimeotheque\Vimeo_Api\Search_Resource;
use Vimeotheque\Vimeo_Api\User_Resource;
use Vimeotheque\Vimeo_Api\Video_Resource;

/**
 * Class Resources_Objects
 * @package Vimeotheque\Admin
 */
class Resource_Objects{
	/**
	 * @var Resource_Abstract[]
	 */
	private $resources = [];

	/**
	 * Resources_Objects constructor.
	 */
	public function __construct() {

		$this->resources['album'] = new Album_Resource( false );
		$this->resources['category'] = new Category_Resource( false );
		$this->resources['channel'] = new Channel_Resource( false );
		$this->resources['group'] = new Group_Resource( false );
		$this->resources['ondemand'] = new Ondemand_Resource( false );
		$this->resources['portfolio'] = new Portfolio_Resource( false );
		$this->resources['search'] = new Search_Resource( false );
		$this->resources['user'] = new User_Resource( false );
		$this->resources['video'] = new Video_Resource( false );

	}

	/**
	 * @return Resource_Abstract[]
	 */
	public function get_resources(){
		return $this->resources;
	}

}