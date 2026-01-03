<?php
/**
 * CTA Block - Server-Side Render
 *
 * @package Ramboeck
 */

$title    = $attributes['title'] ?? '';
$text     = $attributes['text'] ?? '';
$bg_color = $attributes['backgroundColor'] ?? '';

$styles = array();
if ( ! empty( $bg_color ) ) {
	$styles[] = 'background-color: ' . esc_attr( $bg_color );
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'cta',
		'style' => implode( '; ', $styles ),
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="cta__container">
		<?php if ( ! empty( $title ) ) : ?>
			<h2 class="cta__title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( ! empty( $text ) ) : ?>
			<p class="cta__text"><?php echo wp_kses_post( $text ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $content ) ) : ?>
			<div class="cta__actions">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</section>
