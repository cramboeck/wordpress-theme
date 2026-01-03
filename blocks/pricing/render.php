<?php
/**
 * Pricing Block - Server-Side Render
 *
 * @package Ramboeck
 */

$plans = $attributes['plans'] ?? array();

if ( empty( $plans ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'pricing' ) );
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="pricing__container">
		<div class="pricing__grid">
			<?php foreach ( $plans as $plan ) : ?>
				<article class="pricing-card <?php echo ! empty( $plan['featured'] ) ? 'pricing-card--featured' : ''; ?>">
					<?php if ( ! empty( $plan['featured'] ) ) : ?>
						<div class="pricing-card__badge"><?php esc_html_e( 'Beliebt', 'ramboeck' ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $plan['name'] ) ) : ?>
						<h3 class="pricing-card__name"><?php echo esc_html( $plan['name'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $plan['price'] ) ) : ?>
						<div class="pricing-card__price">
							<span class="pricing-card__amount"><?php echo esc_html( $plan['price'] ); ?></span>
							<?php if ( ! empty( $plan['period'] ) ) : ?>
								<span class="pricing-card__period">/ <?php echo esc_html( $plan['period'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $plan['description'] ) ) : ?>
						<p class="pricing-card__description"><?php echo esc_html( $plan['description'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $plan['features'] ) ) : ?>
						<ul class="pricing-card__features">
							<?php foreach ( $plan['features'] as $feature ) : ?>
								<li>
									<?php echo ramboeck_icon( 'check', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo esc_html( $feature ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $plan['buttonText'] ) && ! empty( $plan['buttonUrl'] ) ) : ?>
						<a href="<?php echo esc_url( $plan['buttonUrl'] ); ?>" class="pricing-card__button button <?php echo ! empty( $plan['featured'] ) ? '' : 'button--secondary'; ?>">
							<?php echo esc_html( $plan['buttonText'] ); ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
