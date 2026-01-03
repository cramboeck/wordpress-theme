<?php
/**
 * Demo Content Setup
 *
 * Creates all pages with pre-filled content for Ramböck.IT
 * Run via: /wp-admin/admin.php?page=ramboeck-demo-setup
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add admin menu for demo setup.
 */
function ramboeck_demo_setup_menu() {
	add_submenu_page(
		'themes.php',
		'Demo Setup',
		'🚀 Demo Setup',
		'manage_options',
		'ramboeck-demo-setup',
		'ramboeck_demo_setup_page'
	);
}
add_action( 'admin_menu', 'ramboeck_demo_setup_menu' );

/**
 * Demo setup admin page.
 */
function ramboeck_demo_setup_page() {
	// Handle form submission
	if ( isset( $_POST['ramboeck_create_demo'] ) && check_admin_referer( 'ramboeck_demo_setup' ) ) {
		$result = ramboeck_create_demo_content();
		echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
	}

	?>
	<div class="wrap">
		<h1>🚀 Ramböck.IT Demo Setup</h1>
		<p>Erstellt automatisch alle Seiten mit vorgefülltem Content für Ramböck.IT.</p>

		<div style="background: #fff; padding: 20px; border: 1px solid #ccc; margin: 20px 0; max-width: 600px;">
			<h2>Folgende Seiten werden erstellt:</h2>
			<ul style="list-style: disc; margin-left: 20px;">
				<li><strong>Startseite</strong> - Hero, Services, Testimonials, CTA, FAQ</li>
				<li><strong>Leistungen</strong> - Alle IT-Services im Detail</li>
				<li><strong>Über uns</strong> - Team und Firmengeschichte</li>
				<li><strong>Kontakt</strong> - Kontaktformular und Infos</li>
				<li><strong>Impressum</strong> - Rechtliche Infos (Platzhalter)</li>
				<li><strong>Datenschutz</strong> - DSGVO-Seite (Platzhalter)</li>
			</ul>

			<form method="post" style="margin-top: 20px;">
				<?php wp_nonce_field( 'ramboeck_demo_setup' ); ?>
				<button type="submit" name="ramboeck_create_demo" class="button button-primary button-hero">
					✨ Demo-Content erstellen
				</button>
			</form>
		</div>

		<p><em>Hinweis: Bestehende Seiten mit gleichem Titel werden nicht überschrieben.</em></p>
	</div>
	<?php
}

/**
 * Create all demo content.
 */
function ramboeck_create_demo_content() {
	$pages_created = 0;

	// Homepage
	$homepage_id = ramboeck_create_page(
		'Startseite',
		ramboeck_get_homepage_content(),
		'front-page'
	);
	if ( $homepage_id ) {
		update_option( 'page_on_front', $homepage_id );
		update_option( 'show_on_front', 'page' );
		$pages_created++;
	}

	// Services page
	if ( ramboeck_create_page( 'Leistungen', ramboeck_get_services_content(), 'page-services' ) ) {
		$pages_created++;
	}

	// About page
	if ( ramboeck_create_page( 'Über uns', ramboeck_get_about_content(), 'page-about' ) ) {
		$pages_created++;
	}

	// Contact page
	if ( ramboeck_create_page( 'Kontakt', ramboeck_get_contact_content(), 'page-contact' ) ) {
		$pages_created++;
	}

	// Legal pages
	if ( ramboeck_create_page( 'Impressum', ramboeck_get_impressum_content(), 'page-legal' ) ) {
		$pages_created++;
	}

	if ( ramboeck_create_page( 'Datenschutz', ramboeck_get_datenschutz_content(), 'page-legal' ) ) {
		$pages_created++;
	}

	// Create menu
	ramboeck_create_navigation_menu();

	return sprintf( '✅ %d Seiten erfolgreich erstellt! Die Startseite wurde als Homepage festgelegt.', $pages_created );
}

/**
 * Create a page if it doesn't exist.
 */
function ramboeck_create_page( $title, $content, $template = '' ) {
	// Check if page exists
	$existing = get_page_by_title( $title, OBJECT, 'page' );
	if ( $existing ) {
		return false;
	}

	$page_data = array(
		'post_title'   => $title,
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	$page_id = wp_insert_post( $page_data );

	if ( $page_id && $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return $page_id;
}

/**
 * Homepage content.
 */
function ramboeck_get_homepage_content() {
	return '<!-- wp:ramboeck/hero {"title":"IT-Lösungen die funktionieren","subtitle":"Ihr Partner für professionelle IT-Dienstleistungen in Österreich. Wir kümmern uns um Ihre Technik – Sie konzentrieren sich auf Ihr Geschäft.","alignment":"center","backgroundColor":"#f8fafc"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="/kontakt">Kostenlose Beratung</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/leistungen">Unsere Leistungen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/hero -->

<!-- wp:ramboeck/services {"columns":3,"services":[{"icon":"monitor","title":"IT-Support","description":"Schnelle Hilfe bei IT-Problemen. Remote-Support und Vor-Ort-Service.","link":"/leistungen#support"},{"icon":"shield","title":"IT-Security","description":"Firewall, Antivirus, Backup-Lösungen und Sicherheitsaudits.","link":"/leistungen#security"},{"icon":"cloud","title":"Cloud-Lösungen","description":"Microsoft 365, Cloud-Speicher und hybride Infrastrukturen.","link":"/leistungen#cloud"},{"icon":"server","title":"Netzwerk","description":"Planung und Wartung Ihrer IT-Infrastruktur.","link":"/leistungen#netzwerk"},{"icon":"phone","title":"Telefonie","description":"Moderne VoIP-Telefonanlagen für Ihr Unternehmen.","link":"/leistungen#telefonie"},{"icon":"code","title":"Webentwicklung","description":"Professionelle Websites und Webanwendungen.","link":"/leistungen#web"}]} /-->

<!-- wp:ramboeck/features {"columns":4,"features":[{"icon":"check","title":"Persönlicher Ansprechpartner","description":"Ein fester Kontakt für alle IT-Anliegen"},{"icon":"clock","title":"Schnelle Reaktionszeit","description":"Support innerhalb von 4 Stunden"},{"icon":"map-pin","title":"Regional vor Ort","description":"Schneller Vor-Ort-Service"},{"icon":"thumbs-up","title":"Faire Preise","description":"Transparente Abrechnung"}]} /-->

<!-- wp:ramboeck/testimonials {"testimonials":[{"quote":"Seit wir mit Ramböck.IT zusammenarbeiten, haben wir keine IT-Sorgen mehr. Schnell, kompetent und immer erreichbar!","author":"Maria Huber","company":"Huber GmbH","rating":5},{"quote":"Endlich ein IT-Partner, der unsere Sprache spricht. Die Cloud-Migration war ein voller Erfolg.","author":"Thomas Maier","company":"Maier & Partner","rating":5},{"quote":"Professioneller Service zu fairen Preisen. Absolute Empfehlung!","author":"Sandra Berger","company":"Berger Consulting","rating":5}]} /-->

<!-- wp:ramboeck/cta {"title":"Bereit für bessere IT?","text":"Vereinbaren Sie jetzt ein kostenloses Erstgespräch.","backgroundColor":"#3b82f6"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Jetzt Termin vereinbaren</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->

<!-- wp:ramboeck/faq {"items":[{"question":"Wie schnell können Sie bei Problemen helfen?","answer":"Bei kritischen Problemen sind wir innerhalb von 4 Stunden vor Ort oder per Remote-Support erreichbar."},{"question":"Bieten Sie auch Support für kleine Unternehmen?","answer":"Ja, wir betreuen Unternehmen jeder Größe – vom Einzelunternehmer bis zum Mittelstand."},{"question":"Was kostet der IT-Support?","answer":"Wir bieten flexible Modelle: Einzelabrechnungen oder günstige Wartungsverträge. Kontaktieren Sie uns für ein Angebot."}]} /-->';
}

/**
 * Services page content.
 */
function ramboeck_get_services_content() {
	return '<!-- wp:ramboeck/hero {"title":"Unsere Leistungen","subtitle":"Maßgeschneiderte IT-Lösungen für Ihren Erfolg","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center" id="support">IT-Support & Wartung</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Schnelle und kompetente Hilfe bei allen IT-Problemen. Ob Remote-Support oder Vor-Ort-Service – wir sind für Sie da.</p>
<!-- /wp:paragraph -->

<!-- wp:ramboeck/features {"columns":3,"features":[{"icon":"monitor","title":"Remote-Support","description":"Schnelle Hilfe per Fernwartung"},{"icon":"truck","title":"Vor-Ort-Service","description":"Techniker bei Ihnen vor Ort"},{"icon":"clock","title":"24/7 Notfall","description":"Für kritische Systeme"}]} /-->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center" id="security">IT-Security</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Schützen Sie Ihre Daten und Systeme vor Bedrohungen. Wir implementieren mehrstufige Sicherheitslösungen.</p>
<!-- /wp:paragraph -->

<!-- wp:ramboeck/features {"columns":3,"features":[{"icon":"shield","title":"Firewall & Antivirus","description":"Schutz vor Malware und Angriffen"},{"icon":"database","title":"Backup-Lösungen","description":"Automatische Datensicherung"},{"icon":"search","title":"Security-Audits","description":"Schwachstellen identifizieren"}]} /-->

<!-- wp:ramboeck/cta {"title":"Interesse an unseren Leistungen?","text":"Wir erstellen Ihnen gerne ein individuelles Angebot.","backgroundColor":"#3b82f6"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Angebot anfordern</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->';
}

/**
 * About page content.
 */
function ramboeck_get_about_content() {
	return '<!-- wp:ramboeck/hero {"title":"Über uns","subtitle":"Ihr verlässlicher IT-Partner seit über 10 Jahren","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Unsere Geschichte</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ramböck.IT wurde mit einer klaren Vision gegründet: IT-Dienstleistungen anzubieten, die wirklich funktionieren. Keine komplizierten Erklärungen, keine versteckten Kosten – einfach zuverlässige Technik, die Ihr Unternehmen voranbringt.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Heute betreuen wir zahlreiche Unternehmen in der Region und sind stolz darauf, dass viele unserer ersten Kunden noch immer zu uns kommen. Das zeigt uns: Wir machen etwas richtig.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Unsere Werte</h2>
<!-- /wp:heading -->

<!-- wp:ramboeck/features {"columns":3,"features":[{"icon":"users","title":"Persönlich","description":"Bei uns sind Sie keine Ticketnummer, sondern ein geschätzter Partner."},{"icon":"zap","title":"Schnell","description":"Wir wissen, dass IT-Probleme Zeit und Geld kosten. Deshalb reagieren wir schnell."},{"icon":"heart","title":"Ehrlich","description":"Wir empfehlen nur, was Sie wirklich brauchen. Keine unnötigen Upgrades."}]} /-->

</div>
<!-- /wp:group -->

<!-- wp:ramboeck/cta {"title":"Lernen Sie uns kennen","text":"Vereinbaren Sie ein unverbindliches Gespräch.","backgroundColor":"#3b82f6"} -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="/kontakt">Kontakt aufnehmen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:ramboeck/cta -->';
}

/**
 * Contact page content.
 */
function ramboeck_get_contact_content() {
	return '<!-- wp:ramboeck/hero {"title":"Kontakt","subtitle":"Wir freuen uns auf Ihre Nachricht","alignment":"center","backgroundColor":"#f8fafc"} /-->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">

<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Schreiben Sie uns</h2>
<!-- /wp:heading -->

<!-- wp:ramboeck/contact {"recipientEmail":"office@ramboeck.it","successMessage":"Vielen Dank für Ihre Nachricht! Wir melden uns schnellstmöglich bei Ihnen."} /-->

</div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Kontaktdaten</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Ramböck.IT</strong><br>Musterstraße 123<br>1234 Musterstadt<br>Österreich</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📞 <a href="tel:+4312345678">+43 1 234 567 8</a><br>📧 <a href="mailto:office@ramboeck.it">office@ramboeck.it</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Öffnungszeiten</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Mo - Fr: 08:00 - 17:00 Uhr<br>Notfall-Support: 24/7</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->';
}

/**
 * Impressum content.
 */
function ramboeck_get_impressum_content() {
	return '<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Impressum</h1>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Angaben gemäß § 5 ECG</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Ramböck.IT</strong><br>Christoph Ramböck<br>Musterstraße 123<br>1234 Musterstadt<br>Österreich</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Kontakt</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Telefon: +43 1 234 567 8<br>E-Mail: office@ramboeck.it</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Umsatzsteuer-ID</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Umsatzsteuer-Identifikationsnummer gemäß § 27a Umsatzsteuergesetz:<br>ATU12345678</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#64748b"}}} -->
<p class="has-text-color" style="color:#64748b"><em>Bitte ersetzen Sie diese Platzhalter durch Ihre echten Daten.</em></p>
<!-- /wp:paragraph -->';
}

/**
 * Datenschutz content.
 */
function ramboeck_get_datenschutz_content() {
	return '<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Datenschutzerklärung</h1>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">1. Datenschutz auf einen Blick</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Allgemeine Hinweise</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">2. Verantwortliche Stelle</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Verantwortlich für die Datenverarbeitung auf dieser Website ist:<br><br>Ramböck.IT<br>Christoph Ramböck<br>Musterstraße 123<br>1234 Musterstadt<br>Österreich<br><br>E-Mail: office@ramboeck.it</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">3. Hosting</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Diese Website wird bei einem externen Dienstleister gehostet. Die personenbezogenen Daten, die auf dieser Website erfasst werden, werden auf den Servern des Hosters gespeichert.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#64748b"}}} -->
<p class="has-text-color" style="color:#64748b"><em>Dies ist eine Vorlage. Bitte passen Sie die Datenschutzerklärung an Ihre spezifischen Anforderungen an oder nutzen Sie einen Datenschutz-Generator.</em></p>
<!-- /wp:paragraph -->';
}

/**
 * Create navigation menu.
 */
function ramboeck_create_navigation_menu() {
	$menu_name = 'Hauptmenü';
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if ( $menu_exists ) {
		return;
	}

	$menu_id = wp_create_nav_menu( $menu_name );

	// Add menu items
	$pages = array(
		'Startseite' => '/',
		'Leistungen' => '/leistungen/',
		'Über uns'   => '/ueber-uns/',
		'Kontakt'    => '/kontakt/',
	);

	$position = 0;
	foreach ( $pages as $title => $url ) {
		$page = get_page_by_title( $title, OBJECT, 'page' );
		if ( $page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $title,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position++,
			) );
		}
	}

	// Set as primary menu
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
