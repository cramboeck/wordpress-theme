<?php
/**
 * AI SEO Analyzer
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEO Analyzer using Claude API
 */
class Ramboeck_SEO_Analyzer {

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
	 * Analyze page SEO
	 *
	 * @param int $post_id Post ID.
	 * @return array|WP_Error Analysis results or error.
	 */
	public function analyze_page( $post_id ) {
		$post = get_post( $post_id );

		if ( ! $post ) {
			return new WP_Error( 'no_post', __( 'Post not found', 'ramboeck' ) );
		}

		$data = array(
			'title'       => $post->post_title,
			'content'     => wp_strip_all_tags( $post->post_content ),
			'url'         => get_permalink( $post_id ),
			'word_count'  => str_word_count( wp_strip_all_tags( $post->post_content ) ),
			'has_images'  => (bool) preg_match( '/<img[^>]+>/i', $post->post_content ),
			'has_h2'      => (bool) preg_match( '/<h2[^>]*>/i', $post->post_content ),
			'has_h3'      => (bool) preg_match( '/<h3[^>]*>/i', $post->post_content ),
		);

		$system = 'Du bist ein SEO-Experte. Analysiere die Seite und gib Verbesserungsvorschläge. ' .
				  'Antworte im JSON-Format: {"score": 0-100, "issues": ["..."], "suggestions": ["..."]}';

		$prompt = "Analysiere diese Seite:\n\n" . wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

		$response = $this->claude->send_message( $prompt, $system, 1024 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$text = $this->claude->get_text( $response );

		// Extract JSON
		preg_match( '/\{.*\}/s', $text, $matches );

		if ( empty( $matches[0] ) ) {
			return new WP_Error( 'parse_error', __( 'Could not parse analysis', 'ramboeck' ) );
		}

		$analysis = json_decode( $matches[0], true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new WP_Error( 'json_error', __( 'Invalid JSON', 'ramboeck' ) );
		}

		return $analysis;
	}

	/**
	 * Suggest keywords
	 *
	 * @param string $content Content to analyze.
	 * @param string $topic Topic/niche.
	 * @return array|WP_Error Keywords or error.
	 */
	public function suggest_keywords( $content, $topic = '' ) {
		$system = 'Du bist ein SEO-Experte. Schlage relevante Keywords vor. ' .
				  'Antworte im JSON-Format: {"primary": "...", "secondary": ["..."], "longtail": ["..."]}';

		$prompt = "Thema: {$topic}\n\nInhalt:\n" . wp_strip_all_tags( $content );

		$response = $this->claude->send_message( $prompt, $system, 512 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$text = $this->claude->get_text( $response );

		preg_match( '/\{.*\}/s', $text, $matches );

		if ( empty( $matches[0] ) ) {
			return new WP_Error( 'parse_error', __( 'Could not parse keywords', 'ramboeck' ) );
		}

		return json_decode( $matches[0], true );
	}

	/**
	 * Generate title suggestions
	 *
	 * @param string $content Content.
	 * @param string $current_title Current title.
	 * @return array|WP_Error Suggestions or error.
	 */
	public function suggest_titles( $content, $current_title = '' ) {
		$system = 'Du bist ein SEO-Experte und Texter. Erstelle 5 alternative Titel-Vorschläge. ' .
				  'Jeder Titel sollte max. 60 Zeichen haben und SEO-optimiert sein. ' .
				  'Antworte im JSON-Format: ["Titel 1", "Titel 2", ...]';

		$prompt = "Aktueller Titel: {$current_title}\n\nInhalt:\n" . wp_strip_all_tags( substr( $content, 0, 1000 ) );

		$response = $this->claude->send_message( $prompt, $system, 512 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$text = $this->claude->get_text( $response );

		preg_match( '/\[.*\]/s', $text, $matches );

		if ( empty( $matches[0] ) ) {
			return new WP_Error( 'parse_error', __( 'Could not parse titles', 'ramboeck' ) );
		}

		return json_decode( $matches[0], true );
	}
}

/**
 * Get SEO Analyzer instance
 *
 * @return Ramboeck_SEO_Analyzer
 */
function ramboeck_seo_analyzer() {
	static $instance = null;

	if ( null === $instance ) {
		$instance = new Ramboeck_SEO_Analyzer();
	}

	return $instance;
}
