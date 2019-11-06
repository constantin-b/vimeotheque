<?php
namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Vimeo_Api_Oauth
 */
class Vimeo_Oauth extends Vimeo{

	const UNAUTH_REQUEST 	= 'oauth/authorize/client';
	const AUTH_REDIRECT 	= 'oauth/authorize/';
	const ACCESS_TOKEN		= 'oauth/access_token';

	/**
	 * Stores Vimeo token
	 * @var string
	 */
	protected $token;
	/**
	 * oAuth client ID
	 * @var string
	 */
	private $client_id;
	/**
	 * oAuth client secret
	 * @var string
	 */
	private $client_secret;
	/**
	 * oAuth redirect URL
	 * @var string
	 */
	private $redirect_url;

	/**
	 * Constructor, sets up client id, client secret and token
	 *
	 * @param string $client_id - oAuth client ID
	 * @param string $client_secret - oAuth client secret
	 * @param string $token - authorization token
	 * @param $redirect_url
	 */
	public function __construct( $client_id, $client_secret, $token = null, $redirect_url ){
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->token = $token;
		$this->redirect_url = $redirect_url;
	}

	/**
	 * Returns token for unauthorized requests.
	 *
	 * @return array|string|\WP_Error|null
	 */
	public function get_unauth_token(){
		// if there is a token, return it
		if( !empty( $this->token ) ){
			return $this->token;
		}

		// construct the endpoint
		$endpoint = parent::API_ENDPOINT . self::UNAUTH_REQUEST;

		// make request
		$request = wp_remote_post( $endpoint, [
			'body' => [
				'grant_type' => 'client_credentials'
			],
			'method' => 'POST',
			'sslverify' => false,
			'headers' => [
				'authorization' => 'basic ' . base64_encode( $this->client_id . ':' . $this->client_secret )
			]
		] );

		// if request failed for some reason, return the error
		if( is_wp_error( $request ) ){
			return $request;
		}

		// get request data
		$data = json_decode( wp_remote_retrieve_body( $request ), true );

		// if Vimeo API returned the access token, set the token and return its value
		if( isset( $data['access_token'] ) && !empty( $data['access_token'] ) ){
			$this->token = $data['access_token'];
			return $data['access_token'];
		}

		// if Vimeo returned error, return the error
		if( 200 != wp_remote_retrieve_response_code( $request ) ){
			return parent::api_error( $data );
		}
	}

	/**
	 * Returns the authorization redirect URL
	 *
	 * @param string $state - a unique string to verify the response from Vimeo
	 * @param array $scope - grant scope(s), anything besides private and public. See https://developer.vimeo.com/api/authentication#supported-scopes
	 * @return string - authorization URL
	 */
	public function get_auth_redirect( $state = null, $scope = [] ){
		if( empty( $this->client_id ) || empty( $this->client_secret ) ){
			return parent::error(
				'cvm_missing_oauth_credentials',
				__(
					'Vimeo authorization credentials could not be found. Please enter them in plugin Settings page.' ,
					'cvm_video'
				)
			);
		}

		$redirect = parent::API_ENDPOINT . self::AUTH_REDIRECT;
		if( !$state ){
			$state = time();
		}

		$args = [
			'response_type' => 'code',
			'client_id' 	=> $this->client_id,
			// https://developer.vimeo.com/api/authentication#supported-scopes
			'scope' 		=> 'public private' . ( $scope && is_array( $scope ) ? ' ' . implode( ' ', $scope ) : '' ),
			'state' 		=> $state,
			'redirect_uri' 	=> $this->redirect_url
		];
		return $redirect . '?' . http_build_query( $args );
	}

	/**
	 * Get authenticated request access token.
	 * @param string $code - code returned by Vimeo after user granted access
	 * @return array|\WP_Error
	 */
	public function get_auth_token( $code ){
		if( empty( $code ) ){
			return parent::error( 'cvm_no_access_code', __( 'Error, no access code provided.' , 'cvm_video') );
		}

		$endpoint = parent::API_ENDPOINT . self::ACCESS_TOKEN;
		$request = wp_remote_post( $endpoint, [
			'body' => [
				'grant_type' 	=> 'authorization_code',
				'code' 		 	=> $code,
				'redirect_uri' 	=> $this->redirect_url
			],
			'method' => 'POST',
			'sslverify' => false,
			'headers' => [
				'authorization' => 'basic ' . base64_encode( $this->client_id . ':' . $this->client_secret )
			]
		] );

		if( is_wp_error( $request ) ){
			return $request;
		}

		$data = json_decode( wp_remote_retrieve_body( $request ), true );

		// if Vimeo API returned the access token, set the token and return its value
		if( isset( $data['access_token'] ) && !empty( $data['access_token'] ) ){
			/**
			 * Pass some data to third party code
			 * @param $data - user uri and scopes that the grant was given for
			 */
			do_action( 'cvm_granted_auth_access_token_scope', [
				'scope' => explode( ' ', $data['scope'] ),
				'user_uri' => $data['user']['uri'],
				'name' => $data['user']['name']
			] );

			// set the token
			$this->token = $data['access_token'];
			return $data['access_token'];
		}

		// if Vimeo returned error, return the error
		if( isset( $data['error'] ) ){
			return parent::api_error( $data );
		}
	}

	/**
	 * Returns the value of the redirect URL set up by child class
	 * @return string
	 */
	public function get_redirect_url(){
		return $this->redirect_url;
	}
}