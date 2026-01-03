<?php
/**
 * Security Headers
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add security headers.
 */
function ramboeck_security_headers() {
	if ( is_admin() ) {
		return;
	}

	// X-Content-Type-Options
	header( 'X-Content-Type-Options: nosniff' );

	// X-Frame-Options
	header( 'X-Frame-Options: SAMEORIGIN' );

	// X-XSS-Protection
	header( 'X-XSS-Protection: 1; mode=block' );

	// Referrer-Policy
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );

	// Permissions-Policy
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
}
add_action( 'send_headers', 'ramboeck_security_headers' );
