<?php
/**
 * Features Block - Server-Side Render
 *
 * @package Ramboeck
 */

$features = $attributes['features'] ?? array();
$columns  = $attributes['columns'] ?? 3;

if ( empty( $features ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'features',
		'style' => '--features-columns: ' . esc_attr( $columns ),
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="features__container">
		<div class="features__grid">
			<?php foreach ( $features as $feature ) : ?>
				<div class="feature-item">
					<div class="feature-item__icon">
						<?php echo ramboeck_icon( $feature['icon'] ?? 'check', array( 'width' => 24, 'height' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="feature-item__content">
						<?php if ( ! empty( $feature['title'] ) ) : ?>
							<h3 class="feature-item__title"><?php echo esc_html( $feature['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $feature['description'] ) ) : ?>
							<p class="feature-item__description"><?php echo esc_html( $feature['description'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
