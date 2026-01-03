<?php
/**
 * AI Page Optimizer
 *
 * Provides AI-powered suggestions to improve page content.
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AI Page Optimizer Class
 */
class Ramboeck_Page_Optimizer {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_ajax_ramboeck_analyze_page', array( $this, 'ajax_analyze_page' ) );
		add_action( 'wp_ajax_ramboeck_improve_content', array( $this, 'ajax_improve_content' ) );
		add_action( 'wp_ajax_ramboeck_generate_section', array( $this, 'ajax_generate_section' ) );
	}

	/**
	 * Enqueue editor assets
	 */
	public function enqueue_editor_assets() {
		// Only load in post editor, not site editor
		$screen = get_current_screen();
		if ( $screen && 'site-editor' === $screen->id ) {
			return;
		}

		$asset_file = get_template_directory() . '/assets/js/dist/page-optimizer.asset.php';
		$asset      = file_exists( $asset_file ) ? require $asset_file : array(
			'dependencies' => array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n', 'wp-blocks' ),
			'version'      => RAMBOECK_VERSION,
		);

		// Ensure required dependencies are available
		$dependencies = $asset['dependencies'];

		// Filter out 'react' if 'wp-element' is present (wp-element includes React)
		if ( in_array( 'wp-element', $dependencies, true ) ) {
			$dependencies = array_filter( $dependencies, function( $dep ) {
				return 'react' !== $dep;
			} );
		}

		// Add wp-blocks for parsing generated content
		if ( ! in_array( 'wp-blocks', $dependencies, true ) ) {
			$dependencies[] = 'wp-blocks';
		}

		wp_enqueue_script(
			'ramboeck-page-optimizer',
			get_template_directory_uri() . '/assets/js/dist/page-optimizer.js',
			array_values( $dependencies ),
			$asset['version'],
			true
		);

		wp_localize_script(
			'ramboeck-page-optimizer',
			'ramboeckOptimizer',
			array(
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'ramboeck_optimizer' ),
				'isConfigured' => ramboeck_claude()->is_configured(),
				'settingsUrl'  => admin_url( 'admin.php?page=ramboeck-ai-settings' ),
				'strings'      => array(
					'title'           => __( 'KI-Optimierer', 'ramboeck' ),
					'analyze'         => __( 'Seite analysieren', 'ramboeck' ),
					'analyzing'       => __( 'Analysiere...', 'ramboeck' ),
					'improve'         => __( 'Verbessern', 'ramboeck' ),
					'improving'       => __( 'Verbessere...', 'ramboeck' ),
					'generateSection' => __( 'Section generieren', 'ramboeck' ),
					'generating'      => __( 'Generiere...', 'ramboeck' ),
					'apply'           => __( 'Anwenden', 'ramboeck' ),
					'discard'         => __( 'Verwerfen', 'ramboeck' ),
					'notConfigured'   => __( 'Claude API nicht konfiguriert.', 'ramboeck' ),
					'configureHint'   => __( 'Bitte API-Key in den AI-Einstellungen hinterlegen.', 'ramboeck' ),
					'error'           => __( 'Fehler bei der Anfrage.', 'ramboeck' ),
					'noContent'       => __( 'Kein Inhalt zum Analysieren.', 'ramboeck' ),
					'seoScore'        => __( 'SEO Score', 'ramboeck' ),
					'suggestions'     => __( 'Verbesserungsvorschläge', 'ramboeck' ),
					'missingSections' => __( 'Fehlende Sections', 'ramboeck' ),
					'selectSection'   => __( 'Section auswählen', 'ramboeck' ),
				),
			)
		);

		wp_enqueue_style(
			'ramboeck-page-optimizer',
			get_template_directory_uri() . '/assets/css/admin/page-optimizer.css',
			array(),
			RAMBOECK_VERSION
		);
	}

	/**
	 * AJAX: Analyze page content
	 */
	public function ajax_analyze_page() {
		check_ajax_referer( 'ramboeck_optimizer', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Keine Berechtigung.', 'ramboeck' ) ) );
		}

		$content    = isset( $_POST['content'] ) ? wp_kses_post( wp_unslash( $_POST['content'] ) ) : '';
		$page_title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$page_type  = isset( $_POST['pageType'] ) ? sanitize_text_field( wp_unslash( $_POST['pageType'] ) ) : 'page';

		if ( empty( $content ) ) {
			wp_send_json_error( array( 'message' => __( 'Kein Inhalt vorhanden.', 'ramboeck' ) ) );
		}

		$claude = ramboeck_claude();

		if ( ! $claude->is_configured() ) {
			wp_send_json_error( array( 'message' => __( 'Claude API nicht konfiguriert.', 'ramboeck' ) ) );
		}

		// Strip HTML for analysis
		$plain_content = wp_strip_all_tags( $content );

		$system_prompt = 'Du bist ein SEO- und Content-Experte für deutsche Business-Websites.
Analysiere den folgenden Website-Content und gib strukturiertes Feedback.
Antworte NUR mit einem gültigen JSON-Objekt ohne zusätzlichen Text.';

		$prompt = "Analysiere diese Seite:

Titel: {$page_title}
Seitentyp: {$page_type}

Inhalt:
{$plain_content}

Gib mir ein JSON-Objekt mit dieser Struktur:
{
  \"seoScore\": <Zahl 0-100>,
  \"summary\": \"<Kurze Zusammenfassung der Analyse>\",
  \"strengths\": [\"<Stärke 1>\", \"<Stärke 2>\"],
  \"improvements\": [
    {\"priority\": \"high|medium|low\", \"suggestion\": \"<Verbesserungsvorschlag>\", \"type\": \"seo|content|structure|cta\"}
  ],
  \"missingSections\": [
    {\"name\": \"<Section-Name>\", \"description\": \"<Warum diese Section wichtig ist>\", \"type\": \"hero|services|testimonials|faq|cta|features|about|contact\"}
  ],
  \"keywords\": {
    \"found\": [\"<gefundene Keywords>\"],
    \"suggested\": [\"<empfohlene Keywords>\"]
  }
}";

		$response = $claude->send_message( $prompt, $system_prompt, 2048 );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		$text = $claude->get_text( $response );

		// Try to extract JSON from response
		$json_match = preg_match( '/\{[\s\S]*\}/', $text, $matches );
		if ( $json_match ) {
			$analysis = json_decode( $matches[0], true );
			if ( $analysis ) {
				wp_send_json_success( $analysis );
			}
		}

		// Fallback if JSON parsing fails
		wp_send_json_success( array(
			'seoScore'        => 50,
			'summary'         => $text,
			'strengths'       => array(),
			'improvements'    => array(),
			'missingSections' => array(),
			'keywords'        => array( 'found' => array(), 'suggested' => array() ),
		) );
	}

	/**
	 * AJAX: Improve specific content
	 */
	public function ajax_improve_content() {
		check_ajax_referer( 'ramboeck_optimizer', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Keine Berechtigung.', 'ramboeck' ) ) );
		}

		$content          = isset( $_POST['content'] ) ? wp_kses_post( wp_unslash( $_POST['content'] ) ) : '';
		$improvement_type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'general';
		$context          = isset( $_POST['context'] ) ? sanitize_text_field( wp_unslash( $_POST['context'] ) ) : '';

		if ( empty( $content ) ) {
			wp_send_json_error( array( 'message' => __( 'Kein Inhalt vorhanden.', 'ramboeck' ) ) );
		}

		$claude = ramboeck_claude();

		if ( ! $claude->is_configured() ) {
			wp_send_json_error( array( 'message' => __( 'Claude API nicht konfiguriert.', 'ramboeck' ) ) );
		}

		$type_prompts = array(
			'seo'       => 'Optimiere den Text für SEO. Füge relevante Keywords natürlich ein, verbessere Meta-relevante Elemente.',
			'readability' => 'Verbessere die Lesbarkeit. Kürzere Sätze, aktivere Sprache, bessere Struktur.',
			'cta'       => 'Verstärke die Call-to-Actions. Mache sie überzeugender und handlungsorientierter.',
			'headlines' => 'Verbessere die Überschriften. Sie sollen ansprechender und SEO-optimiert sein.',
			'general'   => 'Verbessere den gesamten Text. Bessere Struktur, überzeugendere Sprache, professioneller Ton.',
		);

		$type_instruction = $type_prompts[ $improvement_type ] ?? $type_prompts['general'];

		$system_prompt = 'Du bist ein professioneller Texter für deutsche Business-Websites.
Verbessere den gegebenen Content und gib NUR den verbesserten Text zurück.
Behalte die grundlegende Struktur bei, aber verbessere Qualität und Wirkung.
Antworte auf Deutsch.';

		$prompt = "{$type_instruction}

Kontext: {$context}

Zu verbessernder Text:
{$content}

Gib NUR den verbesserten Text zurück, ohne Erklärungen oder Kommentare.";

		$response = $claude->send_message( $prompt, $system_prompt, 2048 );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		$improved_content = $claude->get_text( $response );

		wp_send_json_success( array(
			'original' => $content,
			'improved' => $improved_content,
		) );
	}

	/**
	 * AJAX: Generate a new section
	 */
	public function ajax_generate_section() {
		check_ajax_referer( 'ramboeck_optimizer', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Keine Berechtigung.', 'ramboeck' ) ) );
		}

		$section_type = isset( $_POST['sectionType'] ) ? sanitize_text_field( wp_unslash( $_POST['sectionType'] ) ) : '';
		$page_context = isset( $_POST['context'] ) ? sanitize_text_field( wp_unslash( $_POST['context'] ) ) : '';
		$business_info = isset( $_POST['businessInfo'] ) ? sanitize_text_field( wp_unslash( $_POST['businessInfo'] ) ) : 'Ramböck IT - IT-Dienstleister in Passau';

		if ( empty( $section_type ) ) {
			wp_send_json_error( array( 'message' => __( 'Kein Section-Typ angegeben.', 'ramboeck' ) ) );
		}

		$claude = ramboeck_claude();

		if ( ! $claude->is_configured() ) {
			wp_send_json_error( array( 'message' => __( 'Claude API nicht konfiguriert.', 'ramboeck' ) ) );
		}

		$section_templates = $this->get_section_templates();

		if ( ! isset( $section_templates[ $section_type ] ) ) {
			wp_send_json_error( array( 'message' => __( 'Unbekannter Section-Typ.', 'ramboeck' ) ) );
		}

		$template = $section_templates[ $section_type ];

		$system_prompt = 'Du bist ein WordPress-Entwickler und Content-Experte.
Generiere Gutenberg-Block-Markup für die angeforderte Section.
Antworte NUR mit dem Block-Markup (<!-- wp:... -->), ohne zusätzliche Erklärungen.
Verwende realistische, professionelle Inhalte auf Deutsch.';

		$prompt = "Erstelle eine {$template['name']} Section für diese Website:

Business: {$business_info}
Seitenkontext: {$page_context}

Verwende dieses Block-Format:
{$template['example']}

Passe die Inhalte an das Business an. Generiere professionelle, überzeugende Texte auf Deutsch.";

		$response = $claude->send_message( $prompt, $system_prompt, 4096 );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		$block_content = $claude->get_text( $response );

		// Extract block markup
		if ( preg_match( '/<!-- wp:[\s\S]*<!-- \/wp:\w+ -->/', $block_content, $matches ) ) {
			$block_content = $matches[0];
		}

		wp_send_json_success( array(
			'sectionType' => $section_type,
			'blocks'      => $block_content,
		) );
	}

	/**
	 * Get section templates
	 *
	 * @return array Section templates.
	 */
	private function get_section_templates() {
		return array(
			'hero'         => array(
				'name'    => 'Hero',
				'example' => '<!-- wp:ramboeck/hero {"title":"Überschrift","subtitle":"Untertitel"} --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">CTA Button</a></div><!-- /wp:button --></div><!-- /wp:buttons --><!-- /wp:ramboeck/hero -->',
			),
			'services'     => array(
				'name'    => 'Services/Leistungen',
				'example' => '<!-- wp:ramboeck/services {"columns":3,"title":"Unsere Leistungen","services":[{"icon":"monitor","title":"Service 1","description":"Beschreibung"},{"icon":"shield","title":"Service 2","description":"Beschreibung"},{"icon":"cloud","title":"Service 3","description":"Beschreibung"}]} /-->',
			),
			'features'     => array(
				'name'    => 'Features/Vorteile',
				'example' => '<!-- wp:ramboeck/features {"columns":3,"features":[{"icon":"check","title":"Feature 1","description":"Beschreibung"},{"icon":"check","title":"Feature 2","description":"Beschreibung"},{"icon":"check","title":"Feature 3","description":"Beschreibung"}]} /-->',
			),
			'testimonials' => array(
				'name'    => 'Testimonials',
				'example' => '<!-- wp:ramboeck/testimonials {"testimonials":[{"quote":"Kundenzitat","author":"Name","company":"Firma","rating":5}]} /-->',
			),
			'faq'          => array(
				'name'    => 'FAQ',
				'example' => '<!-- wp:ramboeck/faq {"title":"Häufige Fragen","items":[{"question":"Frage 1?","answer":"Antwort 1"},{"question":"Frage 2?","answer":"Antwort 2"}]} /-->',
			),
			'cta'          => array(
				'name'    => 'Call-to-Action',
				'example' => '<!-- wp:ramboeck/cta {"title":"CTA Überschrift","text":"Beschreibungstext","backgroundColor":"#3b82f6"} --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} --><div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Button Text</a></div><!-- /wp:button --></div><!-- /wp:buttons --><!-- /wp:ramboeck/cta -->',
			),
			'stats'        => array(
				'name'    => 'Statistiken/Zahlen',
				'example' => '<!-- wp:group {"align":"full","backgroundColor":"background-alt"} --><div class="wp-block-group alignfull has-background-alt-background-color has-background"><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph {"align":"center","className":"stat-number","style":{"typography":{"fontSize":"3.5rem","fontWeight":"700"}},"textColor":"primary"} --><p class="has-text-align-center stat-number has-primary-color has-text-color" data-counter="100">100+</p><!-- /wp:paragraph --><!-- wp:paragraph {"align":"center","textColor":"text-muted"} --><p class="has-text-align-center has-text-muted-color has-text-color">Kunden</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
			),
			'contact'      => array(
				'name'    => 'Kontakt',
				'example' => '<!-- wp:ramboeck/contact {"recipientEmail":"info@example.com"} /-->',
			),
		);
	}
}

// Initialize
new Ramboeck_Page_Optimizer();
