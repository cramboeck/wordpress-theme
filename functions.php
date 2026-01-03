<?php
/**
 * Ramböck Theme Functions
 *
 * @package Ramboeck
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Constants
 */
define( 'RAMBOECK_VERSION', '1.0.0' );
define( 'RAMBOECK_DIR', get_template_directory() );
define( 'RAMBOECK_URI', get_template_directory_uri() );

/**
 * Include Files
 *
 * Load all theme functionality from the inc directory.
 */
$ramboeck_includes = array(
	// Core
	'/inc/setup.php',           // Theme Setup, Supports
	'/inc/enqueue.php',         // Scripts & Styles

	// Blocks & Patterns
	'/inc/blocks.php',          // Block Registration
	'/inc/patterns.php',        // Pattern Registration

	// Helpers
	'/inc/helpers/utils.php',   // Utility Functions
	'/inc/helpers/icons.php',   // SVG Icon System
	'/inc/helpers/images.php',  // Image Helpers
	'/inc/helpers/graphics.php',// Decorative Graphics

	// SEO
	'/inc/seo/meta-tags.php',   // Meta Tag Generation
	'/inc/seo/schema.php',      // Schema.org Markup

	// Security
	'/inc/security/headers.php',    // Security Headers
	'/inc/security/hardening.php',  // WP Hardening
	'/inc/security/cleanup.php',    // Remove Bloat

	// Performance
	'/inc/performance/critical-css.php',  // Inline Critical CSS
	'/inc/performance/optimization.php',  // Various Optimizations

	// AI Integration (Claude API)
	'/inc/ai/claude-api.php',        // Claude API Client
	'/inc/ai/content-generator.php', // AI Content Generation
	'/inc/ai/seo-analyzer.php',      // AI SEO Analysis
	'/inc/ai/admin-page.php',        // AI Admin Interface
	'/inc/ai/website-generator.php', // AI Website Generator

	// Admin
	'/inc/admin/theme-settings.php', // Theme Settings Panel

	// Demo Setup
	'/inc/demo-setup.php',           // Demo Content Generator
);

foreach ( $ramboeck_includes as $file ) {
	$filepath = RAMBOECK_DIR . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	}
}
