<?php
/**
 * Claude API Integration
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Claude API Client
 */
class Ramboeck_Claude_API {

	/**
	 * API Endpoint
	 *
	 * @var string
	 */
	private $api_url = 'https://api.anthropic.com/v1/messages';

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * Model
	 *
	 * @var string
	 */
	private $model = 'claude-3-haiku-20240307';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_key = defined( 'RAMBOECK_CLAUDE_API_KEY' ) ? RAMBOECK_CLAUDE_API_KEY : get_option( 'ramboeck_claude_api_key' );
	}

	/**
	 * Check if API is configured
	 *
	 * @return bool
	 */
	public function is_configured() {
		return ! empty( $this->api_key );
	}

	/**
	 * Send message to Claude
	 *
	 * @param string $prompt User prompt.
	 * @param string $system System prompt.
	 * @param int    $max_tokens Max tokens.
	 * @return array|WP_Error Response or error.
	 */
	public function send_message( $prompt, $system = '', $max_tokens = 1024 ) {
		if ( ! $this->is_configured() ) {
			return new WP_Error( 'no_api_key', __( 'Claude API key not configured.', 'ramboeck' ) );
		}

		$body = array(
			'model'      => $this->model,
			'max_tokens' => $max_tokens,
			'messages'   => array(
				array(
					'role'    => 'user',
					'content' => $prompt,
				),
			),
		);

		if ( ! empty( $system ) ) {
			$body['system'] = $system;
		}

		$response = wp_remote_post(
			$this->api_url,
			array(
				'timeout' => 60,
				'headers' => array(
					'Content-Type'      => 'application/json',
					'x-api-key'         => $this->api_key,
					'anthropic-version' => '2023-06-01',
				),
				'body'    => wp_json_encode( $body ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code !== 200 ) {
			return new WP_Error(
				'api_error',
				$body['error']['message'] ?? __( 'Unknown API error', 'ramboeck' ),
				array( 'status' => $code )
			);
		}

		return $body;
	}

	/**
	 * Get text content from response
	 *
	 * @param array $response API response.
	 * @return string Text content.
	 */
	public function get_text( $response ) {
		if ( is_wp_error( $response ) ) {
			return '';
		}

		return $response['content'][0]['text'] ?? '';
	}

	/**
	 * Set model
	 *
	 * @param string $model Model name.
	 */
	public function set_model( $model ) {
		$this->model = $model;
	}
}

/**
 * Get Claude API instance
 *
 * @return Ramboeck_Claude_API
 */
function ramboeck_claude() {
	static $instance = null;

	if ( null === $instance ) {
		$instance = new Ramboeck_Claude_API();
	}

	return $instance;
}
