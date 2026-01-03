<?php
/**
 * Services Block - Server-Side Render
 *
 * @package Ramboeck
 */

$columns  = $attributes['columns'] ?? 3;
$services = $attributes['services'] ?? array();

if ( empty( $services ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'services',
		'style' => '--services-columns: ' . esc_attr( $columns ),
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="services__grid">
		<?php foreach ( $services as $service ) : ?>
			<article class="service-card">
				<?php if ( ! empty( $service['icon'] ) ) : ?>
					<div class="service-card__icon">
						<?php echo ramboeck_icon( $service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $service['title'] ) ) : ?>
					<h3 class="service-card__title"><?php echo esc_html( $service['title'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $service['description'] ) ) : ?>
					<p class="service-card__description"><?php echo esc_html( $service['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $service['link'] ) ) : ?>
					<a href="<?php echo esc_url( $service['link'] ); ?>" class="service-card__link">
						<?php esc_html_e( 'Mehr erfahren', 'ramboeck' ); ?>
						<?php echo ramboeck_icon( 'arrow-right', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>
