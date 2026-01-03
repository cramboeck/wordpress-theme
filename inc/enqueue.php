<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend scripts and styles.
 */
function ramboeck_enqueue_scripts() {
	$theme_version = RAMBOECK_VERSION;

	// Main stylesheet
	wp_enqueue_style(
		'ramboeck-style',
		RAMBOECK_URI . '/assets/css/dist/main.css',
		array(),
		$theme_version
	);

	// Main script
	wp_enqueue_script(
		'ramboeck-script',
		RAMBOECK_URI . '/assets/js/dist/main.js',
		array(),
		$theme_version,
		true
	);

	// Localize script
	wp_localize_script(
		'ramboeck-script',
		'ramboeckData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'ramboeck_nonce' ),
			'siteUrl' => home_url(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ramboeck_enqueue_scripts' );

/**
 * Enqueue block editor scripts and styles.
 */
function ramboeck_enqueue_editor_scripts() {
	wp_enqueue_style(
		'ramboeck-editor-style',
		RAMBOECK_URI . '/assets/css/editor-style.css',
		array(),
		RAMBOECK_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'ramboeck_enqueue_editor_scripts' );

/**
 * Add preload for critical assets.
 */
function ramboeck_preload_assets() {
	$fonts_path = RAMBOECK_URI . '/assets/fonts/';
	?>
	<link rel="preload" href="<?php echo esc_url( $fonts_path . 'inter-regular.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( $fonts_path . 'inter-bold.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<?php
}
add_action( 'wp_head', 'ramboeck_preload_assets', 1 );

/**
 * Add defer attribute to scripts.
 */
function ramboeck_defer_scripts( $tag, $handle, $src ) {
	$defer_scripts = array( 'ramboeck-script' );

	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'ramboeck_defer_scripts', 10, 3 );
