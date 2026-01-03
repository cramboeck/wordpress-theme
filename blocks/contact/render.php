<?php
/**
 * Contact Block - Server-Side Render
 *
 * @package Ramboeck
 */

$recipient_email = $attributes['recipientEmail'] ?? get_option( 'admin_email' );
$success_message = $attributes['successMessage'] ?? __( 'Vielen Dank für Ihre Nachricht!', 'ramboeck' );
$privacy_page_id = $attributes['privacyPageId'] ?? get_option( 'wp_page_for_privacy_policy' );
$privacy_url     = $privacy_page_id ? get_permalink( $privacy_page_id ) : '/datenschutz';
$form_id         = 'contact-form-' . wp_unique_id();

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'contact-form' ) );
?>

<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<form id="<?php echo esc_attr( $form_id ); ?>" class="contact-form__form" method="post" data-success-message="<?php echo esc_attr( $success_message ); ?>">
		<?php wp_nonce_field( 'ramboeck_contact', 'contact_nonce' ); ?>
		<input type="hidden" name="action" value="ramboeck_contact_submit">
		<input type="hidden" name="recipient" value="<?php echo esc_attr( base64_encode( $recipient_email ) ); ?>">

		<!-- Honeypot -->
		<div class="contact-form__honeypot" aria-hidden="true">
			<input type="text" name="website" tabindex="-1" autocomplete="off">
		</div>

		<div class="contact-form__grid">
			<div class="contact-form__field">
				<label for="<?php echo esc_attr( $form_id ); ?>-name"><?php esc_html_e( 'Name', 'ramboeck' ); ?> <span class="required">*</span></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-name" name="contact_name" required>
			</div>

			<div class="contact-form__field">
				<label for="<?php echo esc_attr( $form_id ); ?>-email"><?php esc_html_e( 'E-Mail', 'ramboeck' ); ?> <span class="required">*</span></label>
				<input type="email" id="<?php echo esc_attr( $form_id ); ?>-email" name="contact_email" required>
			</div>

			<div class="contact-form__field">
				<label for="<?php echo esc_attr( $form_id ); ?>-phone"><?php esc_html_e( 'Telefon', 'ramboeck' ); ?></label>
				<input type="tel" id="<?php echo esc_attr( $form_id ); ?>-phone" name="contact_phone">
			</div>

			<div class="contact-form__field">
				<label for="<?php echo esc_attr( $form_id ); ?>-subject"><?php esc_html_e( 'Betreff', 'ramboeck' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-subject" name="contact_subject">
			</div>
		</div>

		<div class="contact-form__field contact-form__field--full">
			<label for="<?php echo esc_attr( $form_id ); ?>-message"><?php esc_html_e( 'Nachricht', 'ramboeck' ); ?> <span class="required">*</span></label>
			<textarea id="<?php echo esc_attr( $form_id ); ?>-message" name="contact_message" rows="5" required></textarea>
		</div>

		<div class="contact-form__field contact-form__field--full">
			<label class="contact-form__checkbox">
				<input type="checkbox" name="contact_privacy" required>
				<span>
					<?php
					printf(
						/* translators: %s: Privacy policy link */
						esc_html__( 'Ich habe die %s gelesen und stimme der Verarbeitung meiner Daten zu.', 'ramboeck' ),
						'<a href="' . esc_url( $privacy_url ) . '" target="_blank">' . esc_html__( 'Datenschutzerklärung', 'ramboeck' ) . '</a>'
					);
					?>
					<span class="required">*</span>
				</span>
			</label>
		</div>

		<div class="contact-form__submit">
			<button type="submit" class="button">
				<span class="button__text"><?php esc_html_e( 'Nachricht senden', 'ramboeck' ); ?></span>
				<span class="button__loading" hidden><?php esc_html_e( 'Wird gesendet...', 'ramboeck' ); ?></span>
			</button>
		</div>

		<div class="contact-form__message" role="alert" hidden></div>
	</form>
</div>
