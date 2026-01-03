<?php
/**
 * Schema.org Markup
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Schema.org JSON-LD.
 */
function ramboeck_schema_output() {
	$schema = array();

	// Organization/LocalBusiness
	$schema[] = ramboeck_get_organization_schema();

	// WebSite
	$schema[] = ramboeck_get_website_schema();

	// Page specific
	if ( is_singular() ) {
		$schema[] = ramboeck_get_webpage_schema();
	}

	// FAQ schema for pages with FAQ block
	if ( is_singular() && has_block( 'ramboeck/faq' ) ) {
		$faq_schema = ramboeck_get_faq_schema();
		if ( ! empty( $faq_schema ) ) {
			$schema[] = $faq_schema;
		}
	}

	// Output
	foreach ( $schema as $item ) {
		if ( ! empty( $item ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_head', 'ramboeck_schema_output', 10 );

/**
 * Get Organization schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_organization_schema() {
	$logo_url = '';
	if ( get_theme_mod( 'custom_logo' ) ) {
		$logo_url = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
	}

	return array(
		'@context'         => 'https://schema.org',
		'@type'            => 'LocalBusiness',
		'@id'              => home_url( '/#organization' ),
		'name'             => 'Ramböck IT',
		'alternateName'    => 'Ramböck.IT',
		'description'      => 'IT-Dienstleister für Unternehmen in Passau und Niederbayern. Managed IT Services, Cloud-Lösungen und IT-Support.',
		'url'              => home_url(),
		'logo'             => $logo_url ? array(
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		) : home_url( '/logo.png' ),
		'image'            => home_url( '/images/ramboeck-it-office.jpg' ),
		'telephone'        => '+49-8531-43800-10',
		'email'            => 'christoph@ramboeck.it',
		'address'          => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Pillham 4B',
			'addressLocality' => 'Ruhstorf a.d. Rott',
			'postalCode'      => '94099',
			'addressRegion'   => 'Bayern',
			'addressCountry'  => 'DE',
		),
		'geo'              => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => '48.4397',
			'longitude' => '13.3347',
		),
		'areaServed'       => array(
			array(
				'@type' => 'City',
				'name'  => 'Passau',
			),
			array(
				'@type' => 'City',
				'name'  => 'Vilshofen',
			),
			array(
				'@type' => 'City',
				'name'  => 'Pocking',
			),
			array(
				'@type' => 'City',
				'name'  => 'Ruhstorf a.d. Rott',
			),
			array(
				'@type' => 'State',
				'name'  => 'Niederbayern',
			),
		),
		'priceRange'       => '€€',
		'openingHoursSpecification' => array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
			'opens'     => '08:00',
			'closes'    => '18:00',
		),
		'sameAs'           => array(
			'https://www.linkedin.com/company/ramboeck-it',
			'https://www.xing.com/pages/ramboeck-it',
		),
		'founder'          => array(
			'@type'    => 'Person',
			'name'     => 'Christoph Ramböck',
			'jobTitle' => 'Geschäftsführer & IT-Consultant',
		),
		'aggregateRating'  => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => '5.0',
			'reviewCount' => '12',
		),
		'hasOfferCatalog'  => array(
			'@type'           => 'OfferCatalog',
			'name'            => 'IT-Dienstleistungen',
			'itemListElement' => array(
				array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type'       => 'Service',
						'name'        => 'IT-Support & Helpdesk',
						'description' => 'Schneller IT-Support für Unternehmen in Passau und Niederbayern.',
					),
				),
				array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type'       => 'Service',
						'name'        => 'IT-Sicherheit',
						'description' => 'IT-Security für den Mittelstand: Firewall, Backup, DSGVO-Beratung.',
					),
				),
				array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type'       => 'Service',
						'name'        => 'Microsoft 365 & Cloud',
						'description' => 'Microsoft 365 Einrichtung, Migration und Cloud-Lösungen.',
					),
				),
				array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type'       => 'Service',
						'name'        => 'Managed IT Services',
						'description' => 'ZeroStress IT - IT-Betreuung zum monatlichen Fixpreis.',
					),
				),
			),
		),
	);
}

/**
 * Get WebSite schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_website_schema() {
	return array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'url'             => home_url(),
		'inLanguage'      => 'de-DE',
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * Get WebPage schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_webpage_schema() {
	global $post;

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'WebPage',
		'@id'           => get_permalink() . '#webpage',
		'name'          => get_the_title(),
		'description'   => ramboeck_get_meta_description(),
		'url'           => get_permalink(),
		'isPartOf'      => array(
			'@id' => home_url( '/#website' ),
		),
		'inLanguage'    => 'de-DE',
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
	);

	// Add breadcrumb if not front page
	if ( ! is_front_page() ) {
		$schema['breadcrumb'] = ramboeck_get_breadcrumb_schema();
	}

	return $schema;
}

/**
 * Get Breadcrumb schema.
 *
 * @return array Schema data.
 */
function ramboeck_get_breadcrumb_schema() {
	$items = array();

	// Home
	$items[] = array(
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => 'Start',
		'item'     => home_url(),
	);

	// Current page
	$items[] = array(
		'@type'    => 'ListItem',
		'position' => 2,
		'name'     => get_the_title(),
		'item'     => get_permalink(),
	);

	return array(
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);
}

/**
 * Get FAQ schema from FAQ block content.
 *
 * @return array|null Schema data or null if no FAQ found.
 */
function ramboeck_get_faq_schema() {
	global $post;

	if ( ! $post ) {
		return null;
	}

	$blocks = parse_blocks( $post->post_content );
	$faq_items = array();

	foreach ( $blocks as $block ) {
		if ( 'ramboeck/faq' === $block['blockName'] && ! empty( $block['attrs']['items'] ) ) {
			foreach ( $block['attrs']['items'] as $item ) {
				if ( ! empty( $item['question'] ) && ! empty( $item['answer'] ) ) {
					$faq_items[] = array(
						'@type'          => 'Question',
						'name'           => wp_strip_all_tags( $item['question'] ),
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $item['answer'] ),
						),
					);
				}
			}
		}
	}

	if ( empty( $faq_items ) ) {
		return null;
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_items,
	);
}
