<?php
/**
 * Performance Optimization
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add resource hints.
 */
function ramboeck_resource_hints() {
	// DNS prefetch
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";

	// Preconnect
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'ramboeck_resource_hints', 1 );

/**
 * Optimize images by adding loading="lazy" and decoding="async".
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function ramboeck_lazy_load_images( $content ) {
	if ( is_admin() || is_feed() ) {
		return $content;
	}

	// Skip if already has loading attribute
	$content = preg_replace_callback(
		'/<img([^>]+)>/i',
		function ( $matches ) {
			$img = $matches[0];

			// Skip if already has loading attribute
			if ( strpos( $img, 'loading=' ) !== false ) {
				return $img;
			}

			// Add loading="lazy" and decoding="async"
			return str_replace( '<img', '<img loading="lazy" decoding="async"', $img );
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'ramboeck_lazy_load_images' );

/**
 * Disable embeds.
 */
function ramboeck_disable_embeds() {
	// Remove oEmbed discovery
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	// Remove embed rewrite rules
	add_filter( 'rewrite_rules_array', function ( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( strpos( $rewrite, 'embed=true' ) !== false ) {
				unset( $rules[ $rule ] );
			}
		}
		return $rules;
	} );
}
add_action( 'init', 'ramboeck_disable_embeds', 9999 );

/**
 * Limit post revisions.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}
