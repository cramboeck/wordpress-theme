<?php
/**
 * Theme Settings Admin Page
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Settings Class
 */
class Ramboeck_Theme_Settings {

	/**
	 * Option name
	 */
	const OPTION_NAME = 'ramboeck_theme_options';

	/**
	 * Color presets
	 */
	public static $presets = array(
		'modern-blue' => array(
			'name'      => 'Modern Blue',
			'primary'   => '#3b82f6',
			'secondary' => '#1e40af',
			'accent'    => '#f97316',
		),
		'forest-green' => array(
			'name'      => 'Forest Green',
			'primary'   => '#059669',
			'secondary' => '#047857',
			'accent'    => '#f59e0b',
		),
		'sunset-orange' => array(
			'name'      => 'Sunset Orange',
			'primary'   => '#ea580c',
			'secondary' => '#c2410c',
			'accent'    => '#0891b2',
		),
		'royal-purple' => array(
			'name'      => 'Royal Purple',
			'primary'   => '#7c3aed',
			'secondary' => '#5b21b6',
			'accent'    => '#f472b6',
		),
		'slate-gray' => array(
			'name'      => 'Slate Professional',
			'primary'   => '#475569',
			'secondary' => '#334155',
			'accent'    => '#0ea5e9',
		),
		'rose-red' => array(
			'name'      => 'Rose Red',
			'primary'   => '#e11d48',
			'secondary' => '#be123c',
			'accent'    => '#8b5cf6',
		),
	);

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
		add_action( 'wp_head', array( __CLASS__, 'output_custom_css' ), 100 );
	}

	/**
	 * Add menu page
	 */
	public static function add_menu_page() {
		add_theme_page(
			__( 'Theme-Einstellungen', 'ramboeck' ),
			__( '🎨 Theme-Einstellungen', 'ramboeck' ),
			'manage_options',
			'ramboeck-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public static function register_settings() {
		register_setting( 'ramboeck_settings', self::OPTION_NAME, array(
			'sanitize_callback' => array( __CLASS__, 'sanitize_options' ),
		) );
	}

	/**
	 * Enqueue admin assets
	 */
	public static function enqueue_admin_assets( $hook ) {
		if ( 'appearance_page_ramboeck-settings' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();

		wp_add_inline_style( 'wp-color-picker', self::get_admin_styles() );
		wp_add_inline_script( 'wp-color-picker', self::get_admin_scripts(), 'after' );
	}

	/**
	 * Get options
	 */
	public static function get_options() {
		$defaults = array(
			// Preset
			'preset'           => 'modern-blue',
			'use_custom'       => false,

			// Colors
			'color_primary'    => '#3b82f6',
			'color_secondary'  => '#1e40af',
			'color_accent'     => '#f97316',
			'color_text'       => '#0f172a',
			'color_text_muted' => '#64748b',
			'color_background' => '#ffffff',
			'color_background_alt' => '#f8fafc',

			// Branding
			'logo_id'          => 0,
			'favicon_id'       => 0,
			'site_title'       => get_bloginfo( 'name' ),
			'tagline'          => get_bloginfo( 'description' ),

			// Contact
			'company_name'     => '',
			'address'          => '',
			'phone'            => '',
			'email'            => '',
			'opening_hours'    => '',

			// Social
			'social_facebook'  => '',
			'social_instagram' => '',
			'social_linkedin'  => '',
			'social_twitter'   => '',
			'social_youtube'   => '',
			'social_xing'      => '',

			// Layout
			'header_style'     => 'default',
			'footer_style'     => 'default',
			'button_style'     => 'rounded',
		);

		$options = get_option( self::OPTION_NAME, array() );
		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Sanitize options
	 */
	public static function sanitize_options( $input ) {
		$sanitized = array();

		// Preset
		$sanitized['preset'] = sanitize_text_field( $input['preset'] ?? 'modern-blue' );
		$sanitized['use_custom'] = ! empty( $input['use_custom'] );

		// Colors
		$color_fields = array( 'color_primary', 'color_secondary', 'color_accent', 'color_text', 'color_text_muted', 'color_background', 'color_background_alt' );
		foreach ( $color_fields as $field ) {
			$sanitized[ $field ] = sanitize_hex_color( $input[ $field ] ?? '' );
		}

		// Branding
		$sanitized['logo_id'] = absint( $input['logo_id'] ?? 0 );
		$sanitized['favicon_id'] = absint( $input['favicon_id'] ?? 0 );
		$sanitized['site_title'] = sanitize_text_field( $input['site_title'] ?? '' );
		$sanitized['tagline'] = sanitize_text_field( $input['tagline'] ?? '' );

		// Contact
		$sanitized['company_name'] = sanitize_text_field( $input['company_name'] ?? '' );
		$sanitized['address'] = sanitize_textarea_field( $input['address'] ?? '' );
		$sanitized['phone'] = sanitize_text_field( $input['phone'] ?? '' );
		$sanitized['email'] = sanitize_email( $input['email'] ?? '' );
		$sanitized['opening_hours'] = sanitize_textarea_field( $input['opening_hours'] ?? '' );

		// Social
		$social_fields = array( 'social_facebook', 'social_instagram', 'social_linkedin', 'social_twitter', 'social_youtube', 'social_xing' );
		foreach ( $social_fields as $field ) {
			$sanitized[ $field ] = esc_url_raw( $input[ $field ] ?? '' );
		}

		// Layout
		$sanitized['header_style'] = sanitize_text_field( $input['header_style'] ?? 'default' );
		$sanitized['footer_style'] = sanitize_text_field( $input['footer_style'] ?? 'default' );
		$sanitized['button_style'] = sanitize_text_field( $input['button_style'] ?? 'rounded' );

		return $sanitized;
	}

	/**
	 * Render settings page
	 */
	public static function render_settings_page() {
		$options = self::get_options();
		?>
		<div class="wrap ramboeck-settings">
			<h1>🎨 <?php esc_html_e( 'Theme-Einstellungen', 'ramboeck' ); ?></h1>

			<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Einstellungen gespeichert!', 'ramboeck' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'ramboeck_settings' ); ?>

				<div class="ramboeck-settings-grid">
					<!-- Color Presets -->
					<div class="ramboeck-settings-card">
						<h2>🎨 <?php esc_html_e( 'Farbschema', 'ramboeck' ); ?></h2>

						<div class="preset-grid">
							<?php foreach ( self::$presets as $key => $preset ) : ?>
								<label class="preset-item <?php echo $options['preset'] === $key ? 'active' : ''; ?>">
									<input type="radio" name="<?php echo self::OPTION_NAME; ?>[preset]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $options['preset'], $key ); ?>>
									<div class="preset-preview">
										<span class="preset-color" style="background: <?php echo esc_attr( $preset['primary'] ); ?>"></span>
										<span class="preset-color" style="background: <?php echo esc_attr( $preset['secondary'] ); ?>"></span>
										<span class="preset-color" style="background: <?php echo esc_attr( $preset['accent'] ); ?>"></span>
									</div>
									<span class="preset-name"><?php echo esc_html( $preset['name'] ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>

						<div class="custom-colors-toggle">
							<label>
								<input type="checkbox" name="<?php echo self::OPTION_NAME; ?>[use_custom]" value="1" <?php checked( $options['use_custom'] ); ?>>
								<?php esc_html_e( 'Eigene Farben verwenden', 'ramboeck' ); ?>
							</label>
						</div>

						<div class="custom-colors" style="<?php echo $options['use_custom'] ? '' : 'display:none;'; ?>">
							<table class="form-table">
								<tr>
									<th><?php esc_html_e( 'Primärfarbe', 'ramboeck' ); ?></th>
									<td><input type="text" class="color-picker" name="<?php echo self::OPTION_NAME; ?>[color_primary]" value="<?php echo esc_attr( $options['color_primary'] ); ?>"></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Sekundärfarbe', 'ramboeck' ); ?></th>
									<td><input type="text" class="color-picker" name="<?php echo self::OPTION_NAME; ?>[color_secondary]" value="<?php echo esc_attr( $options['color_secondary'] ); ?>"></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Akzentfarbe', 'ramboeck' ); ?></th>
									<td><input type="text" class="color-picker" name="<?php echo self::OPTION_NAME; ?>[color_accent]" value="<?php echo esc_attr( $options['color_accent'] ); ?>"></td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Branding -->
					<div class="ramboeck-settings-card">
						<h2>🏢 <?php esc_html_e( 'Branding', 'ramboeck' ); ?></h2>
						<table class="form-table">
							<tr>
								<th><?php esc_html_e( 'Logo', 'ramboeck' ); ?></th>
								<td>
									<div class="image-upload-field">
										<?php $logo_url = $options['logo_id'] ? wp_get_attachment_image_url( $options['logo_id'], 'medium' ) : ''; ?>
										<img src="<?php echo esc_url( $logo_url ); ?>" class="preview-image" style="<?php echo $logo_url ? '' : 'display:none;'; ?> max-width: 200px;">
										<input type="hidden" name="<?php echo self::OPTION_NAME; ?>[logo_id]" value="<?php echo esc_attr( $options['logo_id'] ); ?>" class="image-id">
										<button type="button" class="button upload-image"><?php esc_html_e( 'Logo auswählen', 'ramboeck' ); ?></button>
										<button type="button" class="button remove-image" style="<?php echo $logo_url ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Entfernen', 'ramboeck' ); ?></button>
									</div>
								</td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Firmenname', 'ramboeck' ); ?></th>
								<td><input type="text" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[company_name]" value="<?php echo esc_attr( $options['company_name'] ); ?>" placeholder="Ramböck.IT"></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Slogan', 'ramboeck' ); ?></th>
								<td><input type="text" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[tagline]" value="<?php echo esc_attr( $options['tagline'] ); ?>" placeholder="IT-Lösungen die funktionieren"></td>
							</tr>
						</table>
					</div>

					<!-- Contact -->
					<div class="ramboeck-settings-card">
						<h2>📞 <?php esc_html_e( 'Kontaktdaten', 'ramboeck' ); ?></h2>
						<table class="form-table">
							<tr>
								<th><?php esc_html_e( 'Adresse', 'ramboeck' ); ?></th>
								<td><textarea name="<?php echo self::OPTION_NAME; ?>[address]" rows="3" class="regular-text"><?php echo esc_textarea( $options['address'] ); ?></textarea></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Telefon', 'ramboeck' ); ?></th>
								<td><input type="text" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[phone]" value="<?php echo esc_attr( $options['phone'] ); ?>" placeholder="+43 1 234 567 8"></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'E-Mail', 'ramboeck' ); ?></th>
								<td><input type="email" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[email]" value="<?php echo esc_attr( $options['email'] ); ?>" placeholder="office@ramboeck.it"></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Öffnungszeiten', 'ramboeck' ); ?></th>
								<td><textarea name="<?php echo self::OPTION_NAME; ?>[opening_hours]" rows="2" class="regular-text"><?php echo esc_textarea( $options['opening_hours'] ); ?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Social Media -->
					<div class="ramboeck-settings-card">
						<h2>📱 <?php esc_html_e( 'Social Media', 'ramboeck' ); ?></h2>
						<table class="form-table">
							<tr>
								<th>Facebook</th>
								<td><input type="url" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[social_facebook]" value="<?php echo esc_url( $options['social_facebook'] ); ?>" placeholder="https://facebook.com/..."></td>
							</tr>
							<tr>
								<th>Instagram</th>
								<td><input type="url" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[social_instagram]" value="<?php echo esc_url( $options['social_instagram'] ); ?>" placeholder="https://instagram.com/..."></td>
							</tr>
							<tr>
								<th>LinkedIn</th>
								<td><input type="url" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[social_linkedin]" value="<?php echo esc_url( $options['social_linkedin'] ); ?>" placeholder="https://linkedin.com/..."></td>
							</tr>
							<tr>
								<th>XING</th>
								<td><input type="url" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[social_xing]" value="<?php echo esc_url( $options['social_xing'] ); ?>" placeholder="https://xing.com/..."></td>
							</tr>
						</table>
					</div>

					<!-- Layout -->
					<div class="ramboeck-settings-card">
						<h2>📐 <?php esc_html_e( 'Layout', 'ramboeck' ); ?></h2>
						<table class="form-table">
							<tr>
								<th><?php esc_html_e( 'Button-Stil', 'ramboeck' ); ?></th>
								<td>
									<select name="<?php echo self::OPTION_NAME; ?>[button_style]">
										<option value="rounded" <?php selected( $options['button_style'], 'rounded' ); ?>><?php esc_html_e( 'Abgerundet', 'ramboeck' ); ?></option>
										<option value="square" <?php selected( $options['button_style'], 'square' ); ?>><?php esc_html_e( 'Eckig', 'ramboeck' ); ?></option>
										<option value="pill" <?php selected( $options['button_style'], 'pill' ); ?>><?php esc_html_e( 'Pill', 'ramboeck' ); ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<?php submit_button( __( 'Einstellungen speichern', 'ramboeck' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Output custom CSS
	 */
	public static function output_custom_css() {
		$options = self::get_options();

		// Get colors from preset or custom
		if ( $options['use_custom'] ) {
			$primary   = $options['color_primary'];
			$secondary = $options['color_secondary'];
			$accent    = $options['color_accent'];
		} else {
			$preset    = self::$presets[ $options['preset'] ] ?? self::$presets['modern-blue'];
			$primary   = $preset['primary'];
			$secondary = $preset['secondary'];
			$accent    = $preset['accent'];
		}

		// Button radius
		$button_radius = '0.5rem';
		if ( $options['button_style'] === 'square' ) {
			$button_radius = '0';
		} elseif ( $options['button_style'] === 'pill' ) {
			$button_radius = '9999px';
		}

		?>
		<style id="ramboeck-custom-colors">
			:root {
				--color-primary: <?php echo esc_attr( $primary ); ?>;
				--color-primary-dark: <?php echo esc_attr( self::adjust_brightness( $primary, -20 ) ); ?>;
				--color-secondary: <?php echo esc_attr( $secondary ); ?>;
				--color-accent: <?php echo esc_attr( $accent ); ?>;
				--radius-button: <?php echo esc_attr( $button_radius ); ?>;
			}

			/* WordPress Block Editor colors */
			.has-primary-background-color { background-color: <?php echo esc_attr( $primary ); ?> !important; }
			.has-primary-color { color: <?php echo esc_attr( $primary ); ?> !important; }
			.has-secondary-background-color { background-color: <?php echo esc_attr( $secondary ); ?> !important; }
			.has-secondary-color { color: <?php echo esc_attr( $secondary ); ?> !important; }
			.has-accent-background-color { background-color: <?php echo esc_attr( $accent ); ?> !important; }
			.has-accent-color { color: <?php echo esc_attr( $accent ); ?> !important; }

			.wp-block-button__link {
				border-radius: <?php echo esc_attr( $button_radius ); ?>;
			}
		</style>
		<?php
	}

	/**
	 * Adjust color brightness
	 */
	public static function adjust_brightness( $hex, $percent ) {
		$hex = ltrim( $hex, '#' );

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$r = max( 0, min( 255, $r + ( $r * $percent / 100 ) ) );
		$g = max( 0, min( 255, $g + ( $g * $percent / 100 ) ) );
		$b = max( 0, min( 255, $b + ( $b * $percent / 100 ) ) );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}

	/**
	 * Admin styles
	 */
	public static function get_admin_styles() {
		return '
			.ramboeck-settings { max-width: 1400px; }
			.ramboeck-settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin: 20px 0; }
			.ramboeck-settings-card { background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 8px; }
			.ramboeck-settings-card h2 { margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #eee; }

			.preset-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
			.preset-item { display: flex; flex-direction: column; align-items: center; padding: 15px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
			.preset-item:hover { border-color: #0073aa; }
			.preset-item.active { border-color: #0073aa; background: #f0f6fc; }
			.preset-item input { display: none; }
			.preset-preview { display: flex; gap: 4px; margin-bottom: 8px; }
			.preset-color { width: 24px; height: 24px; border-radius: 50%; }
			.preset-name { font-size: 12px; font-weight: 500; }

			.custom-colors-toggle { margin: 15px 0; padding: 10px; background: #f9f9f9; border-radius: 4px; }
			.custom-colors { margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; }

			.image-upload-field { display: flex; flex-direction: column; gap: 10px; }
			.image-upload-field .preview-image { max-width: 200px; border-radius: 4px; }
			.image-upload-field .button { width: fit-content; }
		';
	}

	/**
	 * Admin scripts
	 */
	public static function get_admin_scripts() {
		return '
			jQuery(document).ready(function($) {
				// Color picker
				$(".color-picker").wpColorPicker();

				// Preset selection
				$(".preset-item").on("click", function() {
					$(".preset-item").removeClass("active");
					$(this).addClass("active");
				});

				// Toggle custom colors
				$("input[name*=\"use_custom\"]").on("change", function() {
					$(".custom-colors").toggle(this.checked);
				});

				// Media upload
				$(".upload-image").on("click", function(e) {
					e.preventDefault();
					var $container = $(this).closest(".image-upload-field");
					var frame = wp.media({ multiple: false });

					frame.on("select", function() {
						var attachment = frame.state().get("selection").first().toJSON();
						$container.find(".image-id").val(attachment.id);
						$container.find(".preview-image").attr("src", attachment.url).show();
						$container.find(".remove-image").show();
					});

					frame.open();
				});

				$(".remove-image").on("click", function(e) {
					e.preventDefault();
					var $container = $(this).closest(".image-upload-field");
					$container.find(".image-id").val("");
					$container.find(".preview-image").hide();
					$(this).hide();
				});
			});
		';
	}
}

// Initialize
Ramboeck_Theme_Settings::init();

/**
 * Helper function to get theme option
 */
function ramboeck_get_option( $key, $default = '' ) {
	$options = Ramboeck_Theme_Settings::get_options();
	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Helper function to get active colors
 */
function ramboeck_get_colors() {
	$options = Ramboeck_Theme_Settings::get_options();

	if ( $options['use_custom'] ) {
		return array(
			'primary'   => $options['color_primary'],
			'secondary' => $options['color_secondary'],
			'accent'    => $options['color_accent'],
		);
	}

	$preset = Ramboeck_Theme_Settings::$presets[ $options['preset'] ] ?? Ramboeck_Theme_Settings::$presets['modern-blue'];
	return array(
		'primary'   => $preset['primary'],
		'secondary' => $preset['secondary'],
		'accent'    => $preset['accent'],
	);
}
