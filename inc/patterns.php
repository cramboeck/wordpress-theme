<?php
/**
 * Pattern Registration
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block pattern categories.
 */
function ramboeck_register_pattern_categories() {
	register_block_pattern_category(
		'ramboeck-heroes',
		array(
			'label' => __( 'Heroes', 'ramboeck' ),
		)
	);

	register_block_pattern_category(
		'ramboeck-sections',
		array(
			'label' => __( 'Sections', 'ramboeck' ),
		)
	);

	register_block_pattern_category(
		'ramboeck-cta',
		array(
			'label' => __( 'Call to Action', 'ramboeck' ),
		)
	);

	register_block_pattern_category(
		'ramboeck-testimonials',
		array(
			'label' => __( 'Testimonials', 'ramboeck' ),
		)
	);
}
add_action( 'init', 'ramboeck_register_pattern_categories' );
