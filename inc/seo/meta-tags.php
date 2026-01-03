<?php
/**
 * Meta Tag Generation
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output meta tags.
 */
function ramboeck_meta_tags() {
	$title       = ramboeck_get_meta_title();
	$description = ramboeck_get_meta_description();
	$image       = ramboeck_get_meta_image();
	$url         = get_permalink();
	$site_name   = get_bloginfo( 'name' );

	// Open Graph
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";

	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	// Twitter Card
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";

	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ramboeck_meta_tags', 5 );

/**
 * Get meta title.
 *
 * @return string Meta title.
 */
function ramboeck_get_meta_title() {
	if ( is_singular() ) {
		return get_the_title();
	}

	if ( is_archive() ) {
		return get_the_archive_title();
	}

	return get_bloginfo( 'name' );
}

/**
 * Get meta description.
 *
 * @return string Meta description.
 */
function ramboeck_get_meta_description() {
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && ! empty( $post->post_excerpt ) ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}
		if ( $post && ! empty( $post->post_content ) ) {
			return wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		}
	}

	return get_bloginfo( 'description' );
}

/**
 * Get meta image.
 *
 * @return string|false Image URL or false.
 */
function ramboeck_get_meta_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( null, 'large' );
	}

	// Return site logo or default image
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		return wp_get_attachment_image_url( $custom_logo_id, 'full' );
	}

	return false;
}

/**
 * Output canonical URL.
 */
function ramboeck_canonical_url() {
	if ( is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ramboeck_canonical_url', 5 );
