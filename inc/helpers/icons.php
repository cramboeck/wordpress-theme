<?php
/**
 * SVG Icon System
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get SVG icon.
 *
 * @param string $name Icon name.
 * @param array  $args Icon arguments.
 * @return string SVG markup.
 */
function ramboeck_icon( $name, $args = array() ) {
	$defaults = array(
		'class'  => '',
		'width'  => 24,
		'height' => 24,
		'title'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$icons = ramboeck_get_icons();

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	$class = 'icon icon--' . esc_attr( $name );
	if ( ! empty( $args['class'] ) ) {
		$class .= ' ' . esc_attr( $args['class'] );
	}

	$title_id   = '';
	$title_attr = '';
	if ( ! empty( $args['title'] ) ) {
		$title_id   = 'icon-title-' . wp_unique_id();
		$title_attr = ' aria-labelledby="' . $title_id . '"';
	} else {
		$title_attr = ' aria-hidden="true"';
	}

	$svg  = '<svg class="' . $class . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' . $title_attr . '>';

	if ( ! empty( $args['title'] ) ) {
		$svg .= '<title id="' . $title_id . '">' . esc_html( $args['title'] ) . '</title>';
	}

	$svg .= $icons[ $name ];
	$svg .= '</svg>';

	return $svg;
}

/**
 * Get all available icons.
 *
 * @return array Icon paths.
 */
function ramboeck_get_icons() {
	return array(
		'menu'         => '<line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>',
		'close'        => '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>',
		'chevron-down' => '<polyline points="6 9 12 15 18 9"></polyline>',
		'chevron-up'   => '<polyline points="18 15 12 9 6 15"></polyline>',
		'arrow-right'  => '<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>',
		'phone'        => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
		'mail'         => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
		'map-pin'      => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
		'check'        => '<polyline points="20 6 9 17 4 12"></polyline>',
		'star'         => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
	);
}

/**
 * Echo SVG icon.
 *
 * @param string $name Icon name.
 * @param array  $args Icon arguments.
 */
function ramboeck_the_icon( $name, $args = array() ) {
	echo ramboeck_icon( $name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
