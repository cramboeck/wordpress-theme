<?php
/**
 * Theme Setup
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function ramboeck_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Remove core block patterns.
	remove_theme_support( 'core-block-patterns' );

	// Register nav menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'ramboeck' ),
			'footer'  => __( 'Footer Menu', 'ramboeck' ),
			'legal'   => __( 'Legal Menu', 'ramboeck' ),
		)
	);

	// Set content width.
	if ( ! isset( $content_width ) ) {
		$content_width = 800;
	}
}
add_action( 'after_setup_theme', 'ramboeck_setup' );

/**
 * Register widget areas.
 */
function ramboeck_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'ramboeck' ),
			'id'            => 'footer-1',
			'description'   => __( 'Footer column 1', 'ramboeck' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'ramboeck' ),
			'id'            => 'footer-2',
			'description'   => __( 'Footer column 2', 'ramboeck' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'ramboeck' ),
			'id'            => 'footer-3',
			'description'   => __( 'Footer column 3', 'ramboeck' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'ramboeck_widgets_init' );
