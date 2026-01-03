<?php
/**
 * Critical CSS
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline critical CSS.
 */
function ramboeck_inline_critical_css() {
	$critical_css_path = RAMBOECK_DIR . '/assets/css/critical.css';

	if ( file_exists( $critical_css_path ) ) {
		$critical_css = file_get_contents( $critical_css_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( $critical_css ) {
			echo '<style id="critical-css">' . $critical_css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}
add_action( 'wp_head', 'ramboeck_inline_critical_css', 1 );
