<?php
/**
 * Hero Block - Server-Side Render
 *
 * @package Ramboeck
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

$title           = $attributes['title'] ?? '';
$subtitle        = $attributes['subtitle'] ?? '';
$alignment       = $attributes['alignment'] ?? 'left';
$full_height     = $attributes['fullHeight'] ?? false;
$background_type = $attributes['backgroundType'] ?? 'color';
$bg_color        = $attributes['backgroundColor'] ?? '';
$bg_image        = $attributes['backgroundImage'] ?? array();
$overlay_opacity = $attributes['overlayOpacity'] ?? 50;
$text_color      = $attributes['textColor'] ?? '';

// Build classes
$classes = array( 'hero' );
$classes[] = 'hero--align-' . esc_attr( $alignment );

if ( $full_height ) {
	$classes[] = 'hero--full';
}

if ( ! empty( $bg_image['url'] ) ) {
	$classes[] = 'hero--has-background';
}

// Build styles
$styles = array();

if ( ! empty( $bg_color ) ) {
	$styles[] = 'background-color: ' . esc_attr( $bg_color );
}

if ( ! empty( $bg_image['url'] ) ) {
	$styles[] = 'background-image: url(' . esc_url( $bg_image['url'] ) . ')';
}

if ( ! empty( $text_color ) ) {
	$styles[] = '--hero-text-color: ' . esc_attr( $text_color );
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $classes ),
		'style' => implode( '; ', $styles ),
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $bg_image['url'] ) ) : ?>
		<div class="hero__overlay" style="opacity: <?php echo esc_attr( $overlay_opacity / 100 ); ?>"></div>
	<?php endif; ?>

	<div class="hero__container">
		<div class="hero__content">
			<?php if ( ! empty( $title ) ) : ?>
				<h1 class="hero__title"><?php echo wp_kses_post( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( ! empty( $subtitle ) ) : ?>
				<p class="hero__subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $content ) ) : ?>
				<div class="hero__actions">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
