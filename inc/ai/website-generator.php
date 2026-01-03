<?php
/**
 * AI Website Generator
 *
 * Generates complete website content using Claude AI
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Website Generator Class
 */
class Ramboeck_Website_Generator {

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
		add_action( 'wp_ajax_ramboeck_generate_website', array( __CLASS__, 'ajax_generate_website' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Add menu page
	 */
	public static function add_menu_page() {
		add_submenu_page(
			'ramboeck-ai',
			__( 'Website Generator', 'ramboeck' ),
			__( '🚀 Website Generator', 'ramboeck' ),
			'manage_options',
			'ramboeck-website-generator',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Enqueue scripts
	 */
	public static function enqueue_scripts( $hook ) {
		// Only load on website generator page
		if ( strpos( $hook, 'ramboeck-website-generator' ) === false ) {
			return;
		}

		// CSS file is optional - styles are inline in render_page()
	}

	/**
	 * Render page
	 */
	public static function render_page() {
		$claude = ramboeck_claude();
		$is_configured = $claude->is_configured();
		?>
		<div class="wrap ramboeck-generator">
			<h1>🚀 <?php esc_html_e( 'KI Website Generator', 'ramboeck' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Beantworte ein paar Fragen und lass die KI deine komplette Website erstellen.', 'ramboeck' ); ?></p>

			<?php if ( ! $is_configured ) : ?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Bitte konfiguriere zuerst deinen Claude API Key unter AI Assistent → Einstellungen.', 'ramboeck' ); ?></p>
				</div>
				<?php return; ?>
			<?php endif; ?>

			<div class="generator-wizard">
				<!-- Step 1: Business Info -->
				<div class="wizard-step active" data-step="1">
					<div class="step-header">
						<span class="step-number">1</span>
						<h2><?php esc_html_e( 'Über dein Unternehmen', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content">
						<div class="form-group">
							<label for="business_name"><?php esc_html_e( 'Firmenname', 'ramboeck' ); ?> *</label>
							<input type="text" id="business_name" placeholder="z.B. Ramböck.IT" required>
						</div>
						<div class="form-group">
							<label for="business_type"><?php esc_html_e( 'Branche / Was macht ihr?', 'ramboeck' ); ?> *</label>
							<input type="text" id="business_type" placeholder="z.B. IT-Dienstleistungen für KMUs" required>
						</div>
						<div class="form-group">
							<label for="business_location"><?php esc_html_e( 'Standort / Region', 'ramboeck' ); ?></label>
							<input type="text" id="business_location" placeholder="z.B. Wien, Österreich">
						</div>
						<button type="button" class="button button-primary next-step"><?php esc_html_e( 'Weiter', 'ramboeck' ); ?> →</button>
					</div>
				</div>

				<!-- Step 2: Services -->
				<div class="wizard-step" data-step="2">
					<div class="step-header">
						<span class="step-number">2</span>
						<h2><?php esc_html_e( 'Deine Leistungen', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content">
						<div class="form-group">
							<label for="services"><?php esc_html_e( 'Welche Leistungen/Produkte bietest du an?', 'ramboeck' ); ?> *</label>
							<textarea id="services" rows="4" placeholder="z.B.&#10;- IT-Support & Wartung&#10;- Cloud-Lösungen (Microsoft 365)&#10;- Netzwerk-Installation&#10;- IT-Security & Backup"></textarea>
							<p class="description"><?php esc_html_e( 'Eine Leistung pro Zeile', 'ramboeck' ); ?></p>
						</div>
						<div class="form-group">
							<label for="unique_selling"><?php esc_html_e( 'Was macht dich besonders? (USP)', 'ramboeck' ); ?></label>
							<textarea id="unique_selling" rows="2" placeholder="z.B. Persönlicher Ansprechpartner, Schnelle Reaktionszeit, Faire Preise"></textarea>
						</div>
						<div class="button-group">
							<button type="button" class="button prev-step">← <?php esc_html_e( 'Zurück', 'ramboeck' ); ?></button>
							<button type="button" class="button button-primary next-step"><?php esc_html_e( 'Weiter', 'ramboeck' ); ?> →</button>
						</div>
					</div>
				</div>

				<!-- Step 3: Target Audience & Tone -->
				<div class="wizard-step" data-step="3">
					<div class="step-header">
						<span class="step-number">3</span>
						<h2><?php esc_html_e( 'Zielgruppe & Stil', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content">
						<div class="form-group">
							<label for="target_audience"><?php esc_html_e( 'Wer sind deine Kunden?', 'ramboeck' ); ?></label>
							<input type="text" id="target_audience" placeholder="z.B. Kleine und mittelständische Unternehmen">
						</div>
						<div class="form-group">
							<label for="tone"><?php esc_html_e( 'Gewünschter Tonfall', 'ramboeck' ); ?></label>
							<select id="tone">
								<option value="professional"><?php esc_html_e( 'Professionell & seriös', 'ramboeck' ); ?></option>
								<option value="friendly"><?php esc_html_e( 'Freundlich & nahbar', 'ramboeck' ); ?></option>
								<option value="modern"><?php esc_html_e( 'Modern & dynamisch', 'ramboeck' ); ?></option>
								<option value="traditional"><?php esc_html_e( 'Traditionell & vertrauenswürdig', 'ramboeck' ); ?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="contact_info"><?php esc_html_e( 'Kontaktdaten (für Impressum & Footer)', 'ramboeck' ); ?></label>
							<textarea id="contact_info" rows="4" placeholder="Firmenname&#10;Straße Nr.&#10;PLZ Ort&#10;Tel: +43 ...&#10;Email: office@..."></textarea>
						</div>
						<div class="button-group">
							<button type="button" class="button prev-step">← <?php esc_html_e( 'Zurück', 'ramboeck' ); ?></button>
							<button type="button" class="button button-primary next-step"><?php esc_html_e( 'Weiter', 'ramboeck' ); ?> →</button>
						</div>
					</div>
				</div>

				<!-- Step 4: Generate -->
				<div class="wizard-step" data-step="4">
					<div class="step-header">
						<span class="step-number">4</span>
						<h2><?php esc_html_e( 'Website generieren', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content">
						<div class="summary-box">
							<h3><?php esc_html_e( 'Zusammenfassung', 'ramboeck' ); ?></h3>
							<div id="summary-content"></div>
						</div>

						<div class="pages-to-create">
							<h3><?php esc_html_e( 'Folgende Seiten werden erstellt:', 'ramboeck' ); ?></h3>
							<ul>
								<li>✓ <?php esc_html_e( 'Startseite mit Hero, Services, Testimonials, CTA, FAQ', 'ramboeck' ); ?></li>
								<li>✓ <?php esc_html_e( 'Leistungen (detaillierte Beschreibung)', 'ramboeck' ); ?></li>
								<li>✓ <?php esc_html_e( 'Über uns', 'ramboeck' ); ?></li>
								<li>✓ <?php esc_html_e( 'Kontakt', 'ramboeck' ); ?></li>
								<li>✓ <?php esc_html_e( 'Impressum', 'ramboeck' ); ?></li>
								<li>✓ <?php esc_html_e( 'Datenschutzerklärung', 'ramboeck' ); ?></li>
							</ul>
						</div>

						<div class="button-group">
							<button type="button" class="button prev-step">← <?php esc_html_e( 'Zurück', 'ramboeck' ); ?></button>
							<button type="button" class="button button-hero button-primary" id="generate-btn">
								🚀 <?php esc_html_e( 'Website jetzt generieren', 'ramboeck' ); ?>
							</button>
						</div>
					</div>
				</div>

				<!-- Progress -->
				<div class="wizard-step" data-step="5" style="display:none;">
					<div class="step-header">
						<span class="step-number">⏳</span>
						<h2><?php esc_html_e( 'Website wird erstellt...', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content">
						<div class="progress-container">
							<div class="progress-bar"><div class="progress-fill"></div></div>
							<div class="progress-status"><?php esc_html_e( 'Starte...', 'ramboeck' ); ?></div>
						</div>
						<div class="progress-log"></div>
					</div>
				</div>

				<!-- Success -->
				<div class="wizard-step" data-step="6" style="display:none;">
					<div class="step-header">
						<span class="step-number">✅</span>
						<h2><?php esc_html_e( 'Fertig!', 'ramboeck' ); ?></h2>
					</div>
					<div class="step-content success-content">
						<p class="success-message"><?php esc_html_e( 'Deine Website wurde erfolgreich erstellt!', 'ramboeck' ); ?></p>
						<div class="success-actions">
							<a href="<?php echo home_url(); ?>" target="_blank" class="button button-hero button-primary">
								🌐 <?php esc_html_e( 'Website ansehen', 'ramboeck' ); ?>
							</a>
							<a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" class="button button-hero">
								📝 <?php esc_html_e( 'Seiten bearbeiten', 'ramboeck' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<style>
			.ramboeck-generator { max-width: 800px; }
			.generator-wizard { margin-top: 30px; }

			.wizard-step { display: none; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
			.wizard-step.active { display: block; }

			.step-header { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; }
			.step-number { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #3b82f6; color: #fff; border-radius: 50%; font-size: 18px; font-weight: bold; }
			.step-header h2 { margin: 0; }

			.form-group { margin-bottom: 20px; }
			.form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
			.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; }
			.form-group input:focus, .form-group textarea:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
			.form-group .description { margin-top: 5px; color: #666; font-size: 13px; }

			.button-group { display: flex; gap: 10px; margin-top: 25px; }
			.button-hero { padding: 12px 30px !important; font-size: 16px !important; }

			.summary-box { background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
			.summary-box h3 { margin-top: 0; }

			.pages-to-create { margin-bottom: 20px; }
			.pages-to-create ul { margin: 10px 0; }
			.pages-to-create li { padding: 5px 0; }

			.progress-container { text-align: center; padding: 40px 0; }
			.progress-bar { width: 100%; height: 20px; background: #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 15px; }
			.progress-fill { width: 0%; height: 100%; background: linear-gradient(90deg, #3b82f6, #8b5cf6); transition: width 0.5s ease; }
			.progress-status { font-size: 16px; color: #666; }
			.progress-log { margin-top: 20px; padding: 15px; background: #1e293b; color: #94a3b8; border-radius: 6px; font-family: monospace; font-size: 13px; max-height: 200px; overflow-y: auto; text-align: left; }
			.progress-log .log-item { padding: 3px 0; }
			.progress-log .log-success { color: #4ade80; }
			.progress-log .log-error { color: #f87171; }

			.success-content { text-align: center; padding: 40px 0; }
			.success-message { font-size: 20px; color: #059669; margin-bottom: 30px; }
			.success-actions { display: flex; gap: 15px; justify-content: center; }
		</style>

		<script>
		jQuery(document).ready(function($) {
			var currentStep = 1;
			var formData = {};

			// Next step
			$('.next-step').on('click', function() {
				if (validateStep(currentStep)) {
					saveStepData();
					currentStep++;
					showStep(currentStep);
					if (currentStep === 4) updateSummary();
				}
			});

			// Previous step
			$('.prev-step').on('click', function() {
				currentStep--;
				showStep(currentStep);
			});

			function showStep(step) {
				$('.wizard-step').removeClass('active');
				$('.wizard-step[data-step="' + step + '"]').addClass('active').show();
			}

			function validateStep(step) {
				if (step === 1) {
					if (!$('#business_name').val() || !$('#business_type').val()) {
						alert('<?php esc_html_e( 'Bitte fülle alle Pflichtfelder aus.', 'ramboeck' ); ?>');
						return false;
					}
				}
				if (step === 2) {
					if (!$('#services').val()) {
						alert('<?php esc_html_e( 'Bitte gib mindestens eine Leistung an.', 'ramboeck' ); ?>');
						return false;
					}
				}
				return true;
			}

			function saveStepData() {
				formData = {
					business_name: $('#business_name').val(),
					business_type: $('#business_type').val(),
					business_location: $('#business_location').val(),
					services: $('#services').val(),
					unique_selling: $('#unique_selling').val(),
					target_audience: $('#target_audience').val(),
					tone: $('#tone').val(),
					contact_info: $('#contact_info').val()
				};
			}

			function updateSummary() {
				var html = '<p><strong><?php esc_html_e( 'Firma:', 'ramboeck' ); ?></strong> ' + formData.business_name + '</p>';
				html += '<p><strong><?php esc_html_e( 'Branche:', 'ramboeck' ); ?></strong> ' + formData.business_type + '</p>';
				if (formData.business_location) {
					html += '<p><strong><?php esc_html_e( 'Standort:', 'ramboeck' ); ?></strong> ' + formData.business_location + '</p>';
				}
				html += '<p><strong><?php esc_html_e( 'Leistungen:', 'ramboeck' ); ?></strong><br>' + formData.services.replace(/\n/g, '<br>') + '</p>';
				$('#summary-content').html(html);
			}

			// Generate website
			$('#generate-btn').on('click', function() {
				saveStepData();
				showStep(5);
				generateWebsite();
			});

			function generateWebsite() {
				var $progress = $('.progress-fill');
				var $status = $('.progress-status');
				var $log = $('.progress-log');

				function log(msg, type) {
					var className = type ? 'log-' + type : '';
					$log.append('<div class="log-item ' + className + '">' + msg + '</div>');
					$log.scrollTop($log[0].scrollHeight);
				}

				log('🚀 <?php esc_html_e( 'Starte Website-Generierung...', 'ramboeck' ); ?>');
				$progress.css('width', '10%');
				$status.text('<?php esc_html_e( 'Verbinde mit Claude AI...', 'ramboeck' ); ?>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'ramboeck_generate_website',
						nonce: '<?php echo wp_create_nonce( 'ramboeck_generate_website' ); ?>',
						data: formData
					},
					success: function(response) {
						if (response.success) {
							$progress.css('width', '100%');

							response.data.log.forEach(function(item) {
								log(item.message, item.type);
							});

							setTimeout(function() {
								showStep(6);
							}, 1000);
						} else {
							log('❌ ' + response.data.message, 'error');
							$status.text('<?php esc_html_e( 'Fehler aufgetreten', 'ramboeck' ); ?>');
						}
					},
					error: function() {
						log('❌ <?php esc_html_e( 'Verbindungsfehler', 'ramboeck' ); ?>', 'error');
					},
					xhr: function() {
						var xhr = new window.XMLHttpRequest();
						var lastLength = 0;

						xhr.addEventListener('progress', function(evt) {
							var newData = evt.target.responseText.substr(lastLength);
							lastLength = evt.target.responseText.length;

							// Parse streaming updates
							var lines = newData.split('\n');
							lines.forEach(function(line) {
								if (line.indexOf('PROGRESS:') === 0) {
									var pct = parseInt(line.replace('PROGRESS:', ''));
									$progress.css('width', pct + '%');
								} else if (line.indexOf('STATUS:') === 0) {
									$status.text(line.replace('STATUS:', ''));
								} else if (line.indexOf('LOG:') === 0) {
									log(line.replace('LOG:', ''));
								}
							});
						});

						return xhr;
					}
				});
			}
		});
		</script>
		<?php
	}

	/**
	 * AJAX handler for website generation
	 */
	public static function ajax_generate_website() {
		// Enable error reporting for debugging
		error_log( 'Ramboeck Website Generator - AJAX handler called' );

		check_ajax_referer( 'ramboeck_generate_website', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Keine Berechtigung' ) );
			return;
		}

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( array( 'message' => 'Keine Daten empfangen' ) );
			return;
		}

		$data = $_POST['data'];
		$log = array();

		// Sanitize input
		$business = array(
			'name'           => sanitize_text_field( $data['business_name'] ?? '' ),
			'type'           => sanitize_text_field( $data['business_type'] ?? '' ),
			'location'       => sanitize_text_field( $data['business_location'] ?? '' ),
			'services'       => sanitize_textarea_field( $data['services'] ?? '' ),
			'unique_selling' => sanitize_textarea_field( $data['unique_selling'] ?? '' ),
			'target_audience'=> sanitize_text_field( $data['target_audience'] ?? '' ),
			'tone'           => sanitize_text_field( $data['tone'] ?? 'professional' ),
			'contact_info'   => sanitize_textarea_field( $data['contact_info'] ?? '' ),
		);

		// Validate required fields
		if ( empty( $business['name'] ) || empty( $business['type'] ) ) {
			wp_send_json_error( array( 'message' => 'Firmenname und Branche sind erforderlich' ) );
			return;
		}

		$log[] = array( 'message' => '✓ Daten empfangen: ' . $business['name'], 'type' => 'success' );

		// Generate content with Claude
		$claude = ramboeck_claude();

		if ( ! $claude->is_configured() ) {
			error_log( 'Ramboeck Website Generator - API Key not configured' );
			wp_send_json_error( array( 'message' => 'Claude API nicht konfiguriert. Bitte unter AI Assistent → Einstellungen den API Key hinterlegen.' ) );
			return;
		}

		$log[] = array( 'message' => '✓ Claude API verbunden', 'type' => 'success' );

		// Generate homepage content
		$log[] = array( 'message' => '⏳ Generiere Homepage...', 'type' => '' );
		$homepage_content = self::generate_homepage_content( $claude, $business );
		$log[] = array( 'message' => '✓ Homepage generiert', 'type' => 'success' );

		// Generate services page
		$log[] = array( 'message' => '⏳ Generiere Leistungsseite...', 'type' => '' );
		$services_content = self::generate_services_content( $claude, $business );
		$log[] = array( 'message' => '✓ Leistungsseite generiert', 'type' => 'success' );

		// Generate about page
		$log[] = array( 'message' => '⏳ Generiere Über-uns-Seite...', 'type' => '' );
		$about_content = self::generate_about_content( $claude, $business );
		$log[] = array( 'message' => '✓ Über-uns-Seite generiert', 'type' => 'success' );

		// Create pages
		$log[] = array( 'message' => '⏳ Erstelle WordPress-Seiten...', 'type' => '' );

		// Homepage
		$homepage_id = self::create_or_update_page( 'Startseite', $homepage_content );
		update_option( 'page_on_front', $homepage_id );
		update_option( 'show_on_front', 'page' );

		// Services
		self::create_or_update_page( 'Leistungen', $services_content );

		// About
		self::create_or_update_page( 'Über uns', $about_content );

		// Contact (simple)
		self::create_or_update_page( 'Kontakt', self::get_contact_page_content( $business ) );

		// Legal pages
		self::create_or_update_page( 'Impressum', self::get_impressum_content( $business ) );
		self::create_or_update_page( 'Datenschutz', self::get_datenschutz_content( $business ) );

		$log[] = array( 'message' => '✓ Alle Seiten erstellt', 'type' => 'success' );

		// Generate unique graphics
		$log[] = array( 'message' => '⏳ Generiere einzigartige Grafiken...', 'type' => '' );

		$graphics = new Ramboeck_Graphics();
		$industry_colors = $graphics::industry_colors( $business['type'] );
		$unique_palette = $graphics::generate_palette( $business['name'] );

		$log[] = array( 'message' => '✓ Farbpalette erstellt', 'type' => 'success' );

		// Update theme settings with generated colors and info
		$theme_options = get_option( 'ramboeck_theme_options', array() );
		$theme_options['company_name'] = $business['name'];
		$theme_options['tagline'] = $business['type'];
		$theme_options['color_primary'] = $industry_colors['primary'];
		$theme_options['color_accent'] = $industry_colors['accent'];
		$theme_options['custom_color'] = $unique_palette['primary'];
		$theme_options['color_preset'] = 'custom';

		// Save decorative settings
		$theme_options['pattern_type'] = self::get_pattern_for_industry( $business['type'] );
		$theme_options['wave_style'] = rand( 1, 5 );
		update_option( 'ramboeck_theme_options', $theme_options );

		$log[] = array( 'message' => '✓ Theme-Einstellungen aktualisiert', 'type' => 'success' );
		$log[] = array( 'message' => '🎉 Website erfolgreich erstellt!', 'type' => 'success' );

		wp_send_json_success( array( 'log' => $log ) );
	}

	/**
	 * Generate homepage content with Claude
	 */
	private static function generate_homepage_content( $claude, $business ) {
		$tone_desc = array(
			'professional' => 'professionell und seriös',
			'friendly'     => 'freundlich und nahbar',
			'modern'       => 'modern und dynamisch',
			'traditional'  => 'traditionell und vertrauenswürdig',
		);

		$prompt = "Du bist ein Experte für Website-Texte. Erstelle überzeugende deutsche Texte für eine Homepage.

UNTERNEHMEN:
- Name: {$business['name']}
- Branche: {$business['type']}
- Standort: {$business['location']}
- Leistungen: {$business['services']}
- USP: {$business['unique_selling']}
- Zielgruppe: {$business['target_audience']}
- Tonfall: {$tone_desc[$business['tone']]}

ERSTELLE FOLGENDE TEXTE (JSON-Format):
{
  \"hero_title\": \"Kurzer, packender Haupttitel (max 8 Worte)\",
  \"hero_subtitle\": \"Beschreibender Untertitel (1-2 Sätze)\",
  \"services\": [
    {\"title\": \"Leistung 1\", \"description\": \"Kurze Beschreibung (max 20 Worte)\"},
    {\"title\": \"Leistung 2\", \"description\": \"...\"},
    {\"title\": \"Leistung 3\", \"description\": \"...\"}
  ],
  \"features\": [
    {\"title\": \"Vorteil 1\", \"description\": \"Kurz (max 10 Worte)\"},
    {\"title\": \"Vorteil 2\", \"description\": \"...\"},
    {\"title\": \"Vorteil 3\", \"description\": \"...\"},
    {\"title\": \"Vorteil 4\", \"description\": \"...\"}
  ],
  \"testimonials\": [
    {\"quote\": \"Kundenzitat\", \"author\": \"Name\", \"company\": \"Firma\"},
    {\"quote\": \"...\", \"author\": \"...\", \"company\": \"...\"},
    {\"quote\": \"...\", \"author\": \"...\", \"company\": \"...\"}
  ],
  \"cta_title\": \"Call-to-Action Überschrift\",
  \"cta_text\": \"Call-to-Action Beschreibung\",
  \"faq\": [
    {\"question\": \"Frage 1\", \"answer\": \"Antwort\"},
    {\"question\": \"Frage 2\", \"answer\": \"...\"},
    {\"question\": \"Frage 3\", \"answer\": \"...\"}
  ]
}

Gib NUR das JSON zurück, ohne Erklärungen.";

		// Increase max_tokens for complex JSON response
		$response = $claude->send_message( $prompt, '', 4096 );

		if ( is_wp_error( $response ) ) {
			error_log( 'Ramboeck Website Generator - Claude API Error: ' . $response->get_error_message() );
			return self::get_fallback_homepage( $business );
		}

		$content_text = $response['content'][0]['text'] ?? '';

		if ( empty( $content_text ) ) {
			error_log( 'Ramboeck Website Generator - Empty response from Claude API' );
			return self::get_fallback_homepage( $business );
		}

		// Extract JSON from response
		preg_match( '/\{[\s\S]*\}/', $content_text, $matches );
		if ( empty( $matches[0] ) ) {
			return self::get_fallback_homepage( $business );
		}

		$content = json_decode( $matches[0], true );
		if ( ! $content ) {
			return self::get_fallback_homepage( $business );
		}

		// Build WordPress blocks
		return self::build_homepage_blocks( $content, $business );
	}

	/**
	 * Build homepage blocks from content
	 */
	private static function build_homepage_blocks( $content, $business ) {
		// Get industry-specific icons
		$industry_icons = Ramboeck_Graphics::industry_icons( $business['type'] );

		$services_json = array();
		foreach ( $content['services'] as $i => $service ) {
			$services_json[] = array(
				'icon'        => $industry_icons[ $i % count( $industry_icons ) ],
				'title'       => $service['title'],
				'description' => $service['description'],
				'link'        => '/leistungen',
			);
		}

		$features_json = array();
		$feat_icons = array( 'check', 'zap', 'shield', 'star' ); // Universal positive icons
		foreach ( $content['features'] as $i => $feature ) {
			$features_json[] = array(
				'icon'        => $feat_icons[ $i % count( $feat_icons ) ],
				'title'       => $feature['title'],
				'description' => $feature['description'],
			);
		}

		$testimonials_json = array();
		foreach ( $content['testimonials'] as $testimonial ) {
			$testimonials_json[] = array(
				'quote'   => $testimonial['quote'],
				'author'  => $testimonial['author'],
				'company' => $testimonial['company'],
				'rating'  => 5,
			);
		}

		$faq_json = array();
		foreach ( $content['faq'] as $item ) {
			$faq_json[] = array(
				'question' => $item['question'],
				'answer'   => $item['answer'],
			);
		}

		$blocks = '';

		// Hero with floating decoration
		$blocks .= '<!-- wp:ramboeck/hero {"title":"' . esc_attr( $content['hero_title'] ) . '","subtitle":"' . esc_attr( $content['hero_subtitle'] ) . '","alignment":"center","backgroundColor":"#f8fafc","className":"has-floating-decoration"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="/kontakt">Kostenlose Beratung</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/leistungen">Unsere Leistungen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/hero -->';

		// Services with card hover effect
		$blocks .= '<!-- wp:ramboeck/services {"columns":3,"services":' . wp_json_encode( $services_json ) . ',"className":"card-hover-wrapper"} /-->';

		// Features with icon boxes
		$blocks .= '<!-- wp:ramboeck/features {"columns":4,"features":' . wp_json_encode( $features_json ) . ',"className":"icon-colored"} /-->';

		// Testimonials with pattern background
		$blocks .= '<!-- wp:ramboeck/testimonials {"testimonials":' . wp_json_encode( $testimonials_json ) . ',"className":"has-pattern-bg"} /-->';

		// CTA with gradient (uses primary color dynamically)
		$blocks .= '<!-- wp:ramboeck/cta {"title":"' . esc_attr( $content['cta_title'] ) . '","text":"' . esc_attr( $content['cta_text'] ) . '","useGradient":true,"className":"has-floating-decoration"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Jetzt Kontakt aufnehmen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->';

		// FAQ
		$blocks .= '<!-- wp:ramboeck/faq {"items":' . wp_json_encode( $faq_json ) . '} /-->';

		return $blocks;
	}

	/**
	 * Generate services content
	 */
	private static function generate_services_content( $claude, $business ) {
		$prompt = "Erstelle deutschen Fließtext für eine Leistungsseite.

UNTERNEHMEN: {$business['name']}
BRANCHE: {$business['type']}
LEISTUNGEN:
{$business['services']}

Erstelle für JEDE Leistung:
- Überschrift (H2)
- 2-3 Absätze Beschreibung
- 3 Vorteile/Features

Format: Reiner HTML-Text mit h2, p, ul/li Tags. Professionell und überzeugend.";

		$response = $claude->send_message( $prompt, '', 4096 );

		if ( is_wp_error( $response ) ) {
			error_log( 'Ramboeck - Services generation error: ' . $response->get_error_message() );
			return self::get_fallback_services( $business );
		}

		$html = $response['content'][0]['text'] ?? '';

		if ( empty( $html ) ) {
			return self::get_fallback_services( $business );
		}

		// Wrap in WordPress blocks
		return '<!-- wp:ramboeck/hero {"title":"Unsere Leistungen","subtitle":"' . esc_attr( $business['type'] ) . '","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
' . $html . '
</div>
<!-- /wp:group -->

<!-- wp:ramboeck/cta {"title":"Interesse geweckt?","text":"Kontaktieren Sie uns für ein unverbindliches Angebot.","backgroundColor":"#3b82f6"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Angebot anfordern</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->';
	}

	/**
	 * Generate about content
	 */
	private static function generate_about_content( $claude, $business ) {
		$prompt = "Erstelle deutschen Text für eine Über-uns-Seite.

UNTERNEHMEN: {$business['name']}
BRANCHE: {$business['type']}
STANDORT: {$business['location']}
USP: {$business['unique_selling']}

Erstelle:
1. Einleitung (wer wir sind)
2. Unsere Geschichte/Mission
3. Unsere Werte (3-4 Werte)
4. Warum wir (Abschluss)

Format: HTML mit h2, h3, p Tags. Authentisch und vertrauenswürdig.";

		$response = $claude->send_message( $prompt, '', 4096 );

		if ( is_wp_error( $response ) ) {
			error_log( 'Ramboeck - About generation error: ' . $response->get_error_message() );
			return self::get_fallback_about( $business );
		}

		$html = $response['content'][0]['text'] ?? '';

		if ( empty( $html ) ) {
			return self::get_fallback_about( $business );
		}

		return '<!-- wp:ramboeck/hero {"title":"Über uns","subtitle":"Lernen Sie ' . esc_attr( $business['name'] ) . ' kennen","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
' . $html . '
</div>
<!-- /wp:group -->

<!-- wp:ramboeck/cta {"title":"Lassen Sie uns sprechen","text":"Wir freuen uns darauf, Sie kennenzulernen.","backgroundColor":"#3b82f6"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Kontakt aufnehmen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->';
	}

	/**
	 * Create or update page
	 */
	private static function create_or_update_page( $title, $content ) {
		$existing = get_page_by_title( $title, OBJECT, 'page' );

		if ( $existing ) {
			wp_update_post( array(
				'ID'           => $existing->ID,
				'post_content' => $content,
			) );
			return $existing->ID;
		}

		return wp_insert_post( array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
	}

	/**
	 * Get contact page content
	 */
	private static function get_contact_page_content( $business ) {
		$contact_lines = explode( "\n", $business['contact_info'] );
		$contact_html = implode( '<br>', array_map( 'esc_html', $contact_lines ) );

		return '<!-- wp:ramboeck/hero {"title":"Kontakt","subtitle":"Wir freuen uns auf Ihre Nachricht","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:heading {"level":2} -->
<h2>Schreiben Sie uns</h2>
<!-- /wp:heading -->
<!-- wp:ramboeck/contact {"recipientEmail":"' . esc_attr( $business['email'] ?? 'office@example.com' ) . '"} /-->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:heading {"level":2} -->
<h2>Kontaktdaten</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . $contact_html . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->';
	}

	/**
	 * Get impressum content
	 */
	private static function get_impressum_content( $business ) {
		return '<!-- wp:heading {"level":1} -->
<h1>Impressum</h1>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2>Angaben gemäß § 5 ECG</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>' . esc_html( $business['name'] ) . '</strong><br>' . nl2br( esc_html( $business['contact_info'] ) ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#64748b"}}} -->
<p class="has-text-color" style="color:#64748b"><em>Bitte ergänzen Sie diese Angaben mit Ihren vollständigen rechtlichen Informationen (UID, Firmenbuchnummer, etc.).</em></p>
<!-- /wp:paragraph -->';
	}

	/**
	 * Get datenschutz content
	 */
	private static function get_datenschutz_content( $business ) {
		return '<!-- wp:heading {"level":1} -->
<h1>Datenschutzerklärung</h1>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2>1. Verantwortliche Stelle</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $business['name'] ) . '<br>' . nl2br( esc_html( $business['contact_info'] ) ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>2. Erhebung und Speicherung personenbezogener Daten</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Beim Besuch unserer Website werden automatisch Informationen allgemeiner Natur erfasst. Diese Informationen (Server-Logfiles) beinhalten etwa die Art des Webbrowsers, das verwendete Betriebssystem, den Domainnamen Ihres Internet-Service-Providers und ähnliches.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#64748b"}}} -->
<p class="has-text-color" style="color:#64748b"><em>Diese Datenschutzerklärung ist eine Vorlage. Bitte passen Sie sie an Ihre spezifischen Anforderungen an oder nutzen Sie einen DSGVO-konformen Datenschutz-Generator.</em></p>
<!-- /wp:paragraph -->';
	}

	/**
	 * Fallback content if Claude fails
	 */
	private static function get_fallback_homepage( $business ) {
		$services = explode( "\n", $business['services'] );
		$services_json = array();
		$icons = array( 'monitor', 'shield', 'cloud', 'server', 'phone', 'code' );

		foreach ( array_slice( $services, 0, 6 ) as $i => $service ) {
			$service = trim( $service, "- \t" );
			if ( $service ) {
				$services_json[] = array(
					'icon'        => $icons[ $i % count( $icons ) ],
					'title'       => $service,
					'description' => 'Professionelle Lösung für Ihr Unternehmen.',
					'link'        => '/leistungen',
				);
			}
		}

		return '<!-- wp:ramboeck/hero {"title":"' . esc_attr( $business['name'] ) . '","subtitle":"' . esc_attr( $business['type'] ) . '","alignment":"center","backgroundColor":"#f8fafc"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="/kontakt">Kontakt aufnehmen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/hero -->

<!-- wp:ramboeck/services {"columns":3,"services":' . wp_json_encode( $services_json ) . '} /-->';
	}

	private static function get_fallback_services( $business ) {
		return '<!-- wp:ramboeck/hero {"title":"Unsere Leistungen","subtitle":"' . esc_attr( $business['type'] ) . '","alignment":"center"} /-->

<!-- wp:paragraph -->
<p>Hier finden Sie eine Übersicht unserer Leistungen. Kontaktieren Sie uns für mehr Informationen.</p>
<!-- /wp:paragraph -->';
	}

	private static function get_fallback_about( $business ) {
		return '<!-- wp:ramboeck/hero {"title":"Über ' . esc_attr( $business['name'] ) . '","alignment":"center"} /-->

<!-- wp:paragraph -->
<p>Erfahren Sie mehr über unser Unternehmen und unsere Werte.</p>
<!-- /wp:paragraph -->';
	}

	/**
	 * Get appropriate pattern for industry
	 */
	private static function get_pattern_for_industry( $industry ) {
		$industry_lower = strtolower( $industry );

		$patterns = array(
			'it'         => 'grid',
			'tech'       => 'hexagon',
			'software'   => 'grid',
			'beratung'   => 'diagonal',
			'consulting' => 'diagonal',
			'handwerk'   => 'triangles',
			'bau'        => 'triangles',
			'gesundheit' => 'circles',
			'medizin'    => 'circles',
			'handel'     => 'dots',
			'shop'       => 'dots',
			'gastro'     => 'circles',
			'restaurant' => 'circles',
			'bildung'    => 'grid',
			'finanzen'   => 'diagonal',
			'immobilien' => 'hexagon',
			'marketing'  => 'dots',
			'design'     => 'hexagon',
			'foto'       => 'dots',
		);

		foreach ( $patterns as $key => $pattern ) {
			if ( strpos( $industry_lower, $key ) !== false ) {
				return $pattern;
			}
		}

		return 'dots';
	}
}

// Initialize
Ramboeck_Website_Generator::init();
