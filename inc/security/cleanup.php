<?php
/**
 * WordPress Cleanup
 *
 * Remove unnecessary features and bloat.
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove unnecessary head items.
 */
function ramboeck_cleanup_head() {
	// Remove WordPress version
	remove_action( 'wp_head', 'wp_generator' );

	// Remove wlwmanifest link
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Remove RSD link
	remove_action( 'wp_head', 'rsd_link' );

	// Remove shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Remove REST API link
	remove_action( 'wp_head', 'rest_output_link_wp_head' );

	// Remove oEmbed links
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove emoji scripts
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'ramboeck_cleanup_head' );

/**
 * Remove jQuery migrate.
 *
 * @param WP_Scripts $scripts Scripts object.
 */
function ramboeck_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];

		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}
add_action( 'wp_default_scripts', 'ramboeck_remove_jquery_migrate' );

/**
 * Remove dashicons for non-logged-in users.
 */
function ramboeck_remove_dashicons() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'ramboeck_remove_dashicons' );

/**
 * Disable self pingbacks.
 *
 * @param array $links Pingback links.
 */
function ramboeck_disable_self_pingbacks( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $l ] );
		}
	}
}
add_action( 'pre_ping', 'ramboeck_disable_self_pingbacks' );

/**
 * Remove block library CSS for non-block themes.
 */
function ramboeck_remove_block_css() {
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'ramboeck_remove_block_css', 100 );
