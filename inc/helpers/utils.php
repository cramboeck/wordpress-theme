<?php
/**
 * Utility Functions
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme asset URL.
 *
 * @param string $path Asset path relative to assets folder.
 * @return string Full URL to asset.
 */
function ramboeck_asset( $path ) {
	return RAMBOECK_URI . '/assets/' . ltrim( $path, '/' );
}

/**
 * Get theme asset path.
 *
 * @param string $path Asset path relative to assets folder.
 * @return string Full path to asset.
 */
function ramboeck_asset_path( $path ) {
	return RAMBOECK_DIR . '/assets/' . ltrim( $path, '/' );
}

/**
 * Check if current page is a specific template.
 *
 * @param string $template Template name.
 * @return bool
 */
function ramboeck_is_template( $template ) {
	return is_page_template( 'templates/' . $template . '.html' );
}

/**
 * Get formatted phone number for tel: links.
 *
 * @param string $phone Phone number.
 * @return string Formatted phone number.
 */
function ramboeck_format_phone( $phone ) {
	return preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Truncate text to specified length.
 *
 * @param string $text Text to truncate.
 * @param int    $length Maximum length.
 * @param string $suffix Suffix to append.
 * @return string Truncated text.
 */
function ramboeck_truncate( $text, $length = 150, $suffix = '...' ) {
	if ( strlen( $text ) <= $length ) {
		return $text;
	}

	return substr( $text, 0, $length ) . $suffix;
}

/**
 * Generate breadcrumb trail.
 *
 * @return string Breadcrumb HTML.
 */
function ramboeck_breadcrumbs() {
	if ( is_front_page() ) {
		return '';
	}

	$separator = '<span class="breadcrumbs__separator" aria-hidden="true">/</span>';
	$home      = __( 'Home', 'ramboeck' );
	$output    = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'ramboeck' ) . '">';
	$output   .= '<a href="' . esc_url( home_url() ) . '">' . esc_html( $home ) . '</a>';

	if ( is_page() && ! is_front_page() ) {
		$output .= $separator;
		$output .= '<span class="breadcrumbs__current" aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_single() ) {
		$output .= $separator;
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$output .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
			$output .= $separator;
		}
		$output .= '<span class="breadcrumbs__current" aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() ) {
		$output .= $separator;
		$output .= '<span class="breadcrumbs__current" aria-current="page">' . esc_html( get_the_archive_title() ) . '</span>';
	} elseif ( is_search() ) {
		$output .= $separator;
		/* translators: %s: search query */
		$output .= '<span class="breadcrumbs__current" aria-current="page">' . sprintf( esc_html__( 'Search: %s', 'ramboeck' ), get_search_query() ) . '</span>';
	}

	$output .= '</nav>';

	return $output;
}
