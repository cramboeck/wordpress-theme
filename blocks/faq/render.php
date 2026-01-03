<?php
/**
 * FAQ Block - Server-Side Render
 *
 * @package Ramboeck
 */

$items = $attributes['items'] ?? array();

if ( empty( $items ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'faq' ) );

// Schema.org FAQPage
$schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(),
);

foreach ( $items as $item ) {
	if ( ! empty( $item['question'] ) && ! empty( $item['answer'] ) ) {
		$schema['mainEntity'][] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $item['answer'],
			),
		);
	}
}
?>

<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="faq__container">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="faq__item" data-faq-item>
				<button
					class="faq__question"
					type="button"
					aria-expanded="false"
					aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>"
				>
					<span><?php echo esc_html( $item['question'] ); ?></span>
					<span class="faq__icon" aria-hidden="true">
						<?php echo ramboeck_icon( 'chevron-down', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				</button>
				<div
					class="faq__answer"
					id="faq-answer-<?php echo esc_attr( $index ); ?>"
					hidden
				>
					<p><?php echo wp_kses_post( $item['answer'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
</section>
