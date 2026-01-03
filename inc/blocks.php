<?php
/**
 * Block Registration
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom blocks.
 */
function ramboeck_register_blocks() {
	$blocks_dir = RAMBOECK_DIR . '/blocks';

	if ( ! file_exists( $blocks_dir ) ) {
		return;
	}

	$blocks = array(
		'hero',
		'services',
		'testimonials',
		'cta',
		'faq',
		'pricing',
		'team',
		'features',
		'contact',
	);

	foreach ( $blocks as $block ) {
		$block_path = $blocks_dir . '/' . $block;

		if ( file_exists( $block_path . '/block.json' ) ) {
			register_block_type( $block_path );
		}
	}
}
add_action( 'init', 'ramboeck_register_blocks' );

/**
 * Register block category.
 */
function ramboeck_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'ramboeck',
				'title' => __( 'Ramböck Blocks', 'ramboeck' ),
				'icon'  => 'layout',
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'ramboeck_block_categories', 10, 1 );

/**
 * Add custom block styles.
 */
function ramboeck_register_block_styles() {
	// Button styles
	register_block_style(
		'core/button',
		array(
			'name'  => 'accent',
			'label' => __( 'Accent', 'ramboeck' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'secondary',
			'label' => __( 'Secondary', 'ramboeck' ),
		)
	);

	// Group styles
	register_block_style(
		'core/group',
		array(
			'name'  => 'card',
			'label' => __( 'Card', 'ramboeck' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'section',
			'label' => __( 'Section', 'ramboeck' ),
		)
	);
}
add_action( 'init', 'ramboeck_register_block_styles' );
