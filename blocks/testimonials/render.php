<?php
/**
 * Testimonials Block - Server-Side Render
 *
 * @package Ramboeck
 */

$testimonials = $attributes['testimonials'] ?? array();

if ( empty( $testimonials ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'testimonials' ) );
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="testimonials__container">
		<div class="testimonials__grid">
			<?php foreach ( $testimonials as $testimonial ) : ?>
				<article class="testimonial-card">
					<div class="testimonial-card__stars">
						<?php for ( $i = 0; $i < 5; $i++ ) : ?>
							<?php echo ramboeck_icon( 'star', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endfor; ?>
					</div>

					<?php if ( ! empty( $testimonial['quote'] ) ) : ?>
						<blockquote class="testimonial-card__quote">
							<?php echo esc_html( $testimonial['quote'] ); ?>
						</blockquote>
					<?php endif; ?>

					<div class="testimonial-card__author">
						<?php if ( ! empty( $testimonial['avatar'] ) ) : ?>
							<img
								src="<?php echo esc_url( $testimonial['avatar'] ); ?>"
								alt="<?php echo esc_attr( $testimonial['name'] ?? '' ); ?>"
								class="testimonial-card__avatar"
								loading="lazy"
							>
						<?php endif; ?>
						<div class="testimonial-card__info">
							<?php if ( ! empty( $testimonial['name'] ) ) : ?>
								<span class="testimonial-card__name"><?php echo esc_html( $testimonial['name'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $testimonial['role'] ) ) : ?>
								<span class="testimonial-card__role"><?php echo esc_html( $testimonial['role'] ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
