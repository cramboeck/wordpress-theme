<?php
/**
 * AI Content Generator
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Content Generator using Claude API
 */
class Ramboeck_Content_Generator {

	/**
	 * Claude API
	 *
	 * @var Ramboeck_Claude_API
	 */
	private $claude;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->claude = ramboeck_claude();
	}

	/**
	 * Generate meta description
	 *
	 * @param string $content Post content.
	 * @param string $title Post title.
	 * @return string|WP_Error Meta description or error.
	 */
	public function generate_meta_description( $content, $title = '' ) {
		$system = 'Du bist ein SEO-Experte. Erstelle eine Meta-Description (max. 155 Zeichen) auf Deutsch. ' .
				  'Die Description soll informativ, ansprechend und zum Klicken animieren. ' .
				  'Antworte NUR mit der Meta-Description, ohne Anführungszeichen oder Erklärungen.';

		$prompt = "Titel: {$title}\n\nInhalt:\n" . wp_strip_all_tags( $content );

		$response = $this->claude->send_message( $prompt, $system, 100 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return trim( $this->claude->get_text( $response ) );
	}

	/**
	 * Generate alt text for image
	 *
	 * @param string $image_url Image URL.
	 * @param string $context Context about the image.
	 * @return string|WP_Error Alt text or error.
	 */
	public function generate_alt_text( $image_url, $context = '' ) {
		$system = 'Du bist ein Accessibility-Experte. Erstelle einen Alt-Text für ein Bild (max. 125 Zeichen) auf Deutsch. ' .
				  'Der Alt-Text soll das Bild für Screenreader-Nutzer beschreiben. ' .
				  'Antworte NUR mit dem Alt-Text, ohne Anführungszeichen.';

		$prompt = "Kontext: {$context}\nBild-URL: {$image_url}";

		$response = $this->claude->send_message( $prompt, $system, 80 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return trim( $this->claude->get_text( $response ) );
	}

	/**
	 * Improve text readability
	 *
	 * @param string $text Text to improve.
	 * @return string|WP_Error Improved text or error.
	 */
	public function improve_readability( $text ) {
		$system = 'Du bist ein erfahrener Texter. Verbessere den folgenden Text hinsichtlich Lesbarkeit und Klarheit. ' .
				  'Behalte den Inhalt bei, aber mache Sätze kürzer und prägnanter. ' .
				  'Antworte NUR mit dem verbesserten Text.';

		$response = $this->claude->send_message( $text, $system, 2048 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return trim( $this->claude->get_text( $response ) );
	}

	/**
	 * Generate FAQ from content
	 *
	 * @param string $content Content to analyze.
	 * @param int    $count Number of FAQs.
	 * @return array|WP_Error FAQs or error.
	 */
	public function generate_faq( $content, $count = 5 ) {
		$system = 'Du bist ein Content-Stratege. Erstelle FAQs basierend auf dem Inhalt. ' .
				  'Antworte im JSON-Format: [{"question": "...", "answer": "..."}]';

		$prompt = "Erstelle {$count} FAQs auf Deutsch basierend auf:\n\n" . wp_strip_all_tags( $content );

		$response = $this->claude->send_message( $prompt, $system, 2048 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$text = $this->claude->get_text( $response );

		// Extract JSON from response
		preg_match( '/\[.*\]/s', $text, $matches );

		if ( empty( $matches[0] ) ) {
			return new WP_Error( 'parse_error', __( 'Could not parse FAQ response', 'ramboeck' ) );
		}

		$faqs = json_decode( $matches[0], true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new WP_Error( 'json_error', __( 'Invalid JSON in response', 'ramboeck' ) );
		}

		return $faqs;
	}
}

/**
 * Get Content Generator instance
 *
 * @return Ramboeck_Content_Generator
 */
function ramboeck_content_generator() {
	static $instance = null;

	if ( null === $instance ) {
		$instance = new Ramboeck_Content_Generator();
	}

	return $instance;
}
