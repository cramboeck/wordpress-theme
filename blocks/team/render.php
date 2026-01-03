<?php
/**
 * Team Block - Server-Side Render
 *
 * @package Ramboeck
 */

$members = $attributes['members'] ?? array();
$columns = $attributes['columns'] ?? 4;

if ( empty( $members ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'team',
		'style' => '--team-columns: ' . esc_attr( $columns ),
	)
);
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="team__container">
		<div class="team__grid">
			<?php foreach ( $members as $member ) : ?>
				<article class="team-card">
					<?php if ( ! empty( $member['image'] ) ) : ?>
						<img
							src="<?php echo esc_url( $member['image'] ); ?>"
							alt="<?php echo esc_attr( $member['name'] ?? '' ); ?>"
							class="team-card__image"
							loading="lazy"
						>
					<?php endif; ?>

					<div class="team-card__content">
						<?php if ( ! empty( $member['name'] ) ) : ?>
							<h3 class="team-card__name"><?php echo esc_html( $member['name'] ); ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $member['role'] ) ) : ?>
							<p class="team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $member['bio'] ) ) : ?>
							<p class="team-card__bio"><?php echo esc_html( $member['bio'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $member['email'] ) || ! empty( $member['linkedin'] ) ) : ?>
							<div class="team-card__social">
								<?php if ( ! empty( $member['email'] ) ) : ?>
									<a href="mailto:<?php echo esc_attr( $member['email'] ); ?>" aria-label="<?php esc_attr_e( 'E-Mail', 'ramboeck' ); ?>">
										<?php echo ramboeck_icon( 'mail', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</a>
								<?php endif; ?>
								<?php if ( ! empty( $member['linkedin'] ) ) : ?>
									<a href="<?php echo esc_url( $member['linkedin'] ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
