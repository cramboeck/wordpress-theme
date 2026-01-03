<?php
/**
 * WordPress Hardening
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable file editor.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Hide WordPress version from RSS feeds.
 */
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable REST API user enumeration.
 *
 * @param array $endpoints REST API endpoints.
 * @return array Modified endpoints.
 */
function ramboeck_disable_rest_user_enumeration( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'ramboeck_disable_rest_user_enumeration' );

/**
 * Block author enumeration via URL.
 */
function ramboeck_block_author_enumeration() {
	if ( ! is_admin() && isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'ramboeck_block_author_enumeration' );

/**
 * Remove login error messages.
 *
 * @return string Generic error message.
 */
function ramboeck_login_error_message() {
	return __( 'Invalid credentials.', 'ramboeck' );
}
add_filter( 'login_errors', 'ramboeck_login_error_message' );
