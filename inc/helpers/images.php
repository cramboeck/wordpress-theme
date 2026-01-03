<?php
/**
 * Image Helpers
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get responsive image markup.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param array  $attr Additional attributes.
 * @return string Image HTML.
 */
function ramboeck_get_image( $attachment_id, $size = 'large', $attr = array() ) {
	$defaults = array(
		'loading' => 'lazy',
		'decoding' => 'async',
	);

	$attr = wp_parse_args( $attr, $defaults );

	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}

/**
 * Get placeholder image.
 *
 * @param int    $width  Width.
 * @param int    $height Height.
 * @param string $text   Placeholder text.
 * @return string Placeholder image URL.
 */
function ramboeck_placeholder( $width = 800, $height = 600, $text = '' ) {
	$text = $text ?: $width . 'x' . $height;
	return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='{$width}' height='{$height}' viewBox='0 0 {$width} {$height}'%3E%3Crect fill='%23f1f5f9' width='{$width}' height='{$height}'/%3E%3Ctext fill='%2394a3b8' font-family='sans-serif' font-size='24' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3E{$text}%3C/text%3E%3C/svg%3E";
}

/**
 * Add custom image sizes.
 */
function ramboeck_add_image_sizes() {
	add_image_size( 'ramboeck-hero', 1920, 1080, true );
	add_image_size( 'ramboeck-card', 600, 400, true );
	add_image_size( 'ramboeck-thumbnail', 300, 200, true );
	add_image_size( 'ramboeck-avatar', 100, 100, true );
}
add_action( 'after_setup_theme', 'ramboeck_add_image_sizes' );

/**
 * Add custom image sizes to media selector.
 *
 * @param array $sizes Available sizes.
 * @return array Modified sizes.
 */
function ramboeck_custom_image_sizes( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'ramboeck-hero'      => __( 'Hero', 'ramboeck' ),
			'ramboeck-card'      => __( 'Card', 'ramboeck' ),
			'ramboeck-thumbnail' => __( 'Thumbnail', 'ramboeck' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'ramboeck_custom_image_sizes' );
