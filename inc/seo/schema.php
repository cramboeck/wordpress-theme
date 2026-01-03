<?php
/**
 * Schema.org Markup
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Schema.org JSON-LD.
 */
function ramboeck_schema_output() {
	$schema = array();

	// Organization/LocalBusiness
	$schema[] = ramboeck_get_organization_schema();

	// WebSite
	$schema[] = ramboeck_get_website_schema();

	// Page specific
	if ( is_singular() ) {
		$schema[] = ramboeck_get_webpage_schema();
	}

	// Output
	foreach ( $schema as $item ) {
		if ( ! empty( $item ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_head', 'ramboeck_schema_output', 10 );

/**
 * Get Organization schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_organization_schema() {
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'LocalBusiness',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url(),
		'logo'     => array(
			'@type' => 'ImageObject',
			'url'   => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
		),
	);
}

/**
 * Get WebSite schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_website_schema() {
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url(),
	);
}

/**
 * Get WebPage schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_webpage_schema() {
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'WebPage',
		'name'        => get_the_title(),
		'description' => ramboeck_get_meta_description(),
		'url'         => get_permalink(),
	);
}
