<?php
/**
 * AI Admin Page
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add AI menu page
 */
function ramboeck_ai_admin_menu() {
	add_menu_page(
		__( 'AI Assistent', 'ramboeck' ),
		__( 'AI Assistent', 'ramboeck' ),
		'manage_options',
		'ramboeck-ai',
		'ramboeck_ai_admin_page',
		'dashicons-superhero',
		30
	);

	add_submenu_page(
		'ramboeck-ai',
		__( 'Einstellungen', 'ramboeck' ),
		__( 'Einstellungen', 'ramboeck' ),
		'manage_options',
		'ramboeck-ai-settings',
		'ramboeck_ai_settings_page'
	);
}
add_action( 'admin_menu', 'ramboeck_ai_admin_menu' );

/**
 * AI Admin page
 */
function ramboeck_ai_admin_page() {
	$claude = ramboeck_claude();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AI Assistent', 'ramboeck' ); ?></h1>

		<?php if ( ! $claude->is_configured() ) : ?>
			<div class="notice notice-warning">
				<p>
					<?php
					printf(
						/* translators: %s: Settings link */
						esc_html__( 'Claude API ist nicht konfiguriert. %s', 'ramboeck' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=ramboeck-ai-settings' ) ) . '">' . esc_html__( 'Einstellungen öffnen', 'ramboeck' ) . '</a>'
					);
					?>
				</p>
			</div>
		<?php else : ?>
			<div class="ramboeck-ai-tools">
				<div class="ramboeck-ai-card">
					<h2><?php esc_html_e( 'Meta Description Generator', 'ramboeck' ); ?></h2>
					<p><?php esc_html_e( 'Generiere SEO-optimierte Meta-Descriptions für deine Seiten.', 'ramboeck' ); ?></p>
					<a href="#" class="button button-primary"><?php esc_html_e( 'Starten', 'ramboeck' ); ?></a>
				</div>

				<div class="ramboeck-ai-card">
					<h2><?php esc_html_e( 'SEO Analyse', 'ramboeck' ); ?></h2>
					<p><?php esc_html_e( 'Analysiere Seiten und erhalte Verbesserungsvorschläge.', 'ramboeck' ); ?></p>
					<a href="#" class="button button-primary"><?php esc_html_e( 'Starten', 'ramboeck' ); ?></a>
				</div>

				<div class="ramboeck-ai-card">
					<h2><?php esc_html_e( 'FAQ Generator', 'ramboeck' ); ?></h2>
					<p><?php esc_html_e( 'Generiere FAQs basierend auf deinem Content.', 'ramboeck' ); ?></p>
					<a href="#" class="button button-primary"><?php esc_html_e( 'Starten', 'ramboeck' ); ?></a>
				</div>

				<div class="ramboeck-ai-card">
					<h2><?php esc_html_e( 'Alt-Text Generator', 'ramboeck' ); ?></h2>
					<p><?php esc_html_e( 'Generiere Alt-Texte für Bilder.', 'ramboeck' ); ?></p>
					<a href="#" class="button button-primary"><?php esc_html_e( 'Starten', 'ramboeck' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<style>
		.ramboeck-ai-tools {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 20px;
			margin-top: 20px;
		}
		.ramboeck-ai-card {
			background: #fff;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
		}
		.ramboeck-ai-card h2 {
			margin-top: 0;
			font-size: 1.2em;
		}
		.ramboeck-ai-card p {
			color: #666;
		}
	</style>
	<?php
}

/**
 * AI Settings page
 */
function ramboeck_ai_settings_page() {
	if ( isset( $_POST['ramboeck_ai_settings_nonce'] ) && wp_verify_nonce( $_POST['ramboeck_ai_settings_nonce'], 'ramboeck_ai_settings' ) ) {
		update_option( 'ramboeck_claude_api_key', sanitize_text_field( $_POST['api_key'] ?? '' ) );
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Einstellungen gespeichert.', 'ramboeck' ) . '</p></div>';
	}

	$api_key = get_option( 'ramboeck_claude_api_key', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AI Einstellungen', 'ramboeck' ); ?></h1>

		<form method="post">
			<?php wp_nonce_field( 'ramboeck_ai_settings', 'ramboeck_ai_settings_nonce' ); ?>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="api_key"><?php esc_html_e( 'Claude API Key', 'ramboeck' ); ?></label>
					</th>
					<td>
						<input type="password" id="api_key" name="api_key" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text">
						<p class="description">
							<?php
							printf(
								/* translators: %s: Anthropic console link */
								esc_html__( 'Erhalte deinen API Key von %s', 'ramboeck' ),
								'<a href="https://console.anthropic.com/" target="_blank">console.anthropic.com</a>'
							);
							?>
						</p>
						<p class="description">
							<?php esc_html_e( 'Alternativ: Definiere RAMBOECK_CLAUDE_API_KEY in wp-config.php', 'ramboeck' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
