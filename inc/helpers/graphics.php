<?php
/**
 * Graphics Generator
 *
 * Generates unique SVG graphics, patterns and decorative elements
 *
 * @package Ramboeck
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Graphics Generator Class
 */
class Ramboeck_Graphics {

	/**
	 * Generate a unique color palette based on a seed
	 *
	 * @param string $seed Business name or unique identifier
	 * @return array Color palette
	 */
	public static function generate_palette( $seed ) {
		// Generate consistent hash from seed
		$hash = md5( $seed );

		// Extract hue from hash (0-360)
		$primary_hue = hexdec( substr( $hash, 0, 2 ) ) * 1.41; // 0-360

		// Generate harmonious colors
		return array(
			'primary'   => self::hsl_to_hex( $primary_hue, 70, 50 ),
			'secondary' => self::hsl_to_hex( ( $primary_hue + 30 ) % 360, 60, 40 ),
			'accent'    => self::hsl_to_hex( ( $primary_hue + 180 ) % 360, 80, 55 ),
			'light'     => self::hsl_to_hex( $primary_hue, 30, 95 ),
			'dark'      => self::hsl_to_hex( $primary_hue, 40, 20 ),
		);
	}

	/**
	 * Convert HSL to HEX
	 */
	private static function hsl_to_hex( $h, $s, $l ) {
		$h = $h / 360;
		$s = $s / 100;
		$l = $l / 100;

		if ( $s == 0 ) {
			$r = $g = $b = $l;
		} else {
			$q = $l < 0.5 ? $l * ( 1 + $s ) : $l + $s - $l * $s;
			$p = 2 * $l - $q;
			$r = self::hue_to_rgb( $p, $q, $h + 1/3 );
			$g = self::hue_to_rgb( $p, $q, $h );
			$b = self::hue_to_rgb( $p, $q, $h - 1/3 );
		}

		return sprintf( '#%02x%02x%02x', round( $r * 255 ), round( $g * 255 ), round( $b * 255 ) );
	}

	private static function hue_to_rgb( $p, $q, $t ) {
		if ( $t < 0 ) $t += 1;
		if ( $t > 1 ) $t -= 1;
		if ( $t < 1/6 ) return $p + ( $q - $p ) * 6 * $t;
		if ( $t < 1/2 ) return $q;
		if ( $t < 2/3 ) return $p + ( $q - $p ) * ( 2/3 - $t ) * 6;
		return $p;
	}

	/**
	 * Generate a wave SVG divider
	 *
	 * @param string $color Fill color
	 * @param string $position 'top' or 'bottom'
	 * @param int    $variation 1-5 for different wave styles
	 * @return string SVG markup
	 */
	public static function wave_divider( $color = '#ffffff', $position = 'bottom', $variation = 1 ) {
		$paths = array(
			1 => 'M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,128C672,107,768,85,864,90.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z',
			2 => 'M0,160L60,170.7C120,181,240,203,360,197.3C480,192,600,160,720,165.3C840,171,960,213,1080,213.3C1200,213,1320,171,1380,149.3L1440,128L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z',
			3 => 'M0,64L80,85.3C160,107,320,149,480,154.7C640,160,800,128,960,112C1120,96,1280,96,1360,96L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z',
			4 => 'M0,192L48,197.3C96,203,192,213,288,192C384,171,480,117,576,112C672,107,768,149,864,154.7C960,160,1056,128,1152,117.3C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z',
			5 => 'M0,128L40,144C80,160,160,192,240,181.3C320,171,400,117,480,96C560,75,640,85,720,112C800,139,880,181,960,197.3C1040,213,1120,203,1200,176C1280,149,1360,107,1400,85.3L1440,64L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z',
		);

		$path = $paths[ $variation ] ?? $paths[1];
		$transform = $position === 'top' ? 'rotate(180 720 160)' : '';

		return '<svg class="wave-divider wave-divider--' . esc_attr( $position ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
			<path fill="' . esc_attr( $color ) . '" transform="' . $transform . '" d="' . $path . '"></path>
		</svg>';
	}

	/**
	 * Generate blob SVG shape
	 *
	 * @param string $color Fill color
	 * @param int    $variation 1-6 for different blob shapes
	 * @return string SVG markup
	 */
	public static function blob( $color = '#3b82f6', $variation = 1, $opacity = 0.1 ) {
		$blobs = array(
			1 => 'M47.5,-57.2C59.9,-46.3,67.3,-30.1,70.4,-12.8C73.4,4.5,72.1,22.9,63.6,36.8C55.1,50.7,39.4,60.1,22.5,65.7C5.6,71.3,-12.5,73.1,-28.5,67.7C-44.5,62.3,-58.4,49.7,-66.2,34.1C-74,18.5,-75.7,-0.1,-71.4,-17.1C-67.1,-34.1,-56.8,-49.5,-43.3,-60.2C-29.8,-70.9,-14.9,-76.9,1.3,-78.5C17.5,-80.1,35.1,-68.1,47.5,-57.2Z',
			2 => 'M54.1,-62.7C68.8,-52.2,78.8,-34.3,81.3,-15.6C83.8,3.1,78.9,22.6,68.6,38.1C58.3,53.6,42.6,65.1,25.2,71.2C7.8,77.3,-11.3,78,-28.1,72.1C-44.9,66.2,-59.4,53.7,-67.8,38.1C-76.2,22.5,-78.5,3.8,-75.1,-13.6C-71.7,-31,-62.6,-47.1,-49.1,-57.8C-35.6,-68.5,-17.8,-73.8,0.8,-74.8C19.4,-75.8,39.4,-73.2,54.1,-62.7Z',
			3 => 'M45.3,-53.9C58.4,-44.4,68.5,-30.3,72.7,-14.1C76.9,2.1,75.2,20.4,67,35.4C58.8,50.4,44.1,62.1,27.7,68.2C11.3,74.3,-6.8,74.8,-23.1,69.5C-39.4,64.2,-53.9,53.1,-63.5,38.5C-73.1,23.9,-77.8,5.8,-74.9,-10.8C-72,-27.4,-61.5,-42.5,-47.8,-52C-34.1,-61.5,-17.1,-65.4,-0.5,-64.8C16.1,-64.2,32.2,-63.4,45.3,-53.9Z',
			4 => 'M41.5,-49.8C54.4,-40.8,66,-28.6,70.5,-13.8C75,1,72.4,18.4,64.5,32.7C56.6,47,43.4,58.2,28.3,64.2C13.2,70.2,-3.8,71,-20.3,66.8C-36.8,62.6,-52.8,53.4,-61.8,40C-70.8,26.6,-72.8,9,-70.2,-7.4C-67.6,-23.8,-60.4,-39,-49,-50.2C-37.6,-61.4,-22,-68.6,-5.5,-62.5C11,-56.4,28.6,-58.8,41.5,-49.8Z',
			5 => 'M44.7,-52C57.8,-43.4,68.1,-29.6,71.8,-14C75.5,1.6,72.6,19,64.8,33.7C57,48.4,44.3,60.4,29.6,66.1C14.9,71.8,-1.8,71.2,-17.8,66.1C-33.8,61,-49.1,51.4,-58.5,38C-67.9,24.6,-71.4,7.4,-69.3,-9.1C-67.2,-25.6,-59.5,-41.4,-47.4,-50.2C-35.3,-59,-18.6,-60.8,-1.8,-58.6C15,-56.4,31.6,-60.6,44.7,-52Z',
			6 => 'M55.9,-65.8C70.4,-54.9,79.3,-36.8,81.7,-18.3C84.1,0.2,80,19.1,71.1,35.1C62.2,51.1,48.5,64.2,32.5,70.8C16.5,77.4,-1.8,77.5,-19.2,72.4C-36.6,67.3,-53.1,57,-64.1,42.7C-75.1,28.4,-80.6,10.1,-78.7,-7.2C-76.8,-24.5,-67.5,-40.8,-54.4,-52C-41.3,-63.2,-24.4,-69.3,-5.9,-62.4C12.6,-55.5,41.4,-76.7,55.9,-65.8Z',
		);

		$blob = $blobs[ $variation ] ?? $blobs[1];

		return '<svg class="decorative-blob" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
			<path fill="' . esc_attr( $color ) . '" fill-opacity="' . esc_attr( $opacity ) . '" d="' . $blob . '" transform="translate(100 100)"/>
		</svg>';
	}

	/**
	 * Generate geometric pattern
	 *
	 * @param string $color Pattern color
	 * @param string $type Pattern type
	 * @return string SVG data URI for use in CSS
	 */
	public static function pattern( $color = '#3b82f6', $type = 'dots' ) {
		$patterns = array(
			'dots' => '<pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
				<circle cx="10" cy="10" r="2" fill="' . esc_attr( $color ) . '" opacity="0.15"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#dots)"/>',

			'grid' => '<pattern id="grid" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
				<path d="M 40 0 L 0 0 0 40" fill="none" stroke="' . esc_attr( $color ) . '" stroke-width="1" opacity="0.1"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#grid)"/>',

			'diagonal' => '<pattern id="diagonal" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
				<path d="M-1,1 l2,-2 M0,10 l10,-10 M9,11 l2,-2" stroke="' . esc_attr( $color ) . '" stroke-width="1" opacity="0.1"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#diagonal)"/>',

			'hexagon' => '<pattern id="hexagon" x="0" y="0" width="50" height="43.4" patternUnits="userSpaceOnUse">
				<polygon points="25,2 47,14 47,38 25,50 3,38 3,14" fill="none" stroke="' . esc_attr( $color ) . '" stroke-width="1" opacity="0.1"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#hexagon)"/>',

			'circles' => '<pattern id="circles" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
				<circle cx="30" cy="30" r="20" fill="none" stroke="' . esc_attr( $color ) . '" stroke-width="1" opacity="0.1"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#circles)"/>',

			'triangles' => '<pattern id="triangles" x="0" y="0" width="40" height="35" patternUnits="userSpaceOnUse">
				<polygon points="20,0 40,35 0,35" fill="none" stroke="' . esc_attr( $color ) . '" stroke-width="1" opacity="0.1"/>
			</pattern>
			<rect width="100%" height="100%" fill="url(#triangles)"/>',
		);

		$pattern_markup = $patterns[ $type ] ?? $patterns['dots'];

		return '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">' . $pattern_markup . '</svg>';
	}

	/**
	 * Generate gradient CSS
	 *
	 * @param array  $colors Array of colors
	 * @param string $direction Gradient direction
	 * @return string CSS gradient
	 */
	public static function gradient( $colors, $direction = '135deg' ) {
		if ( count( $colors ) < 2 ) {
			return 'transparent';
		}

		return 'linear-gradient(' . $direction . ', ' . implode( ', ', $colors ) . ')';
	}

	/**
	 * Generate decorative floating shapes CSS
	 *
	 * @param string $color Primary color
	 * @return string CSS for floating shapes
	 */
	public static function floating_shapes_css( $color = '#3b82f6' ) {
		$rgba = self::hex_to_rgba( $color, 0.1 );

		return "
		.floating-shapes {
			position: absolute;
			inset: 0;
			overflow: hidden;
			pointer-events: none;
			z-index: 0;
		}
		.floating-shapes::before,
		.floating-shapes::after {
			content: '';
			position: absolute;
			border-radius: 50%;
			background: {$rgba};
			animation: float 20s infinite ease-in-out;
		}
		.floating-shapes::before {
			width: 300px;
			height: 300px;
			top: -100px;
			right: -100px;
		}
		.floating-shapes::after {
			width: 200px;
			height: 200px;
			bottom: -50px;
			left: -50px;
			animation-delay: -10s;
		}
		@keyframes float {
			0%, 100% { transform: translate(0, 0) rotate(0deg); }
			25% { transform: translate(20px, 20px) rotate(5deg); }
			50% { transform: translate(-10px, 30px) rotate(-5deg); }
			75% { transform: translate(-20px, -10px) rotate(3deg); }
		}
		";
	}

	/**
	 * Convert HEX to RGBA
	 */
	public static function hex_to_rgba( $hex, $alpha = 1 ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( str_repeat( substr( $hex, 0, 1 ), 2 ) );
			$g = hexdec( str_repeat( substr( $hex, 1, 1 ), 2 ) );
			$b = hexdec( str_repeat( substr( $hex, 2, 1 ), 2 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		return "rgba({$r}, {$g}, {$b}, {$alpha})";
	}

	/**
	 * Generate industry-specific icons
	 *
	 * @param string $industry Industry type
	 * @return array Suggested icons
	 */
	public static function industry_icons( $industry ) {
		$industry_lower = strtolower( $industry );

		$icon_sets = array(
			'it' => array( 'monitor', 'server', 'cloud', 'shield', 'code', 'database', 'wifi', 'cpu' ),
			'tech' => array( 'monitor', 'server', 'cloud', 'shield', 'code', 'database', 'wifi', 'cpu' ),
			'software' => array( 'code', 'terminal', 'git-branch', 'layers', 'box', 'cpu', 'globe', 'cloud' ),
			'beratung' => array( 'users', 'briefcase', 'trending-up', 'target', 'award', 'bar-chart', 'clipboard', 'lightbulb' ),
			'consulting' => array( 'users', 'briefcase', 'trending-up', 'target', 'award', 'bar-chart', 'clipboard', 'lightbulb' ),
			'handwerk' => array( 'tool', 'wrench', 'hammer', 'hard-hat', 'truck', 'home', 'settings', 'check' ),
			'bau' => array( 'home', 'hard-hat', 'truck', 'tool', 'layers', 'clipboard', 'ruler', 'compass' ),
			'gesundheit' => array( 'heart', 'activity', 'plus-square', 'shield', 'users', 'clipboard', 'thermometer', 'user' ),
			'medizin' => array( 'heart', 'activity', 'plus-square', 'stethoscope', 'pill', 'clipboard', 'thermometer', 'user' ),
			'handel' => array( 'shopping-cart', 'package', 'truck', 'credit-card', 'tag', 'percent', 'box', 'gift' ),
			'shop' => array( 'shopping-cart', 'shopping-bag', 'tag', 'credit-card', 'package', 'percent', 'gift', 'star' ),
			'gastro' => array( 'coffee', 'utensils', 'wine', 'pizza', 'star', 'clock', 'map-pin', 'heart' ),
			'restaurant' => array( 'utensils', 'coffee', 'wine', 'star', 'clock', 'map-pin', 'users', 'heart' ),
			'bildung' => array( 'book', 'graduation-cap', 'edit', 'award', 'users', 'lightbulb', 'clipboard', 'check' ),
			'finanzen' => array( 'dollar-sign', 'trending-up', 'bar-chart', 'pie-chart', 'credit-card', 'shield', 'lock', 'briefcase' ),
			'immobilien' => array( 'home', 'building', 'key', 'map-pin', 'search', 'star', 'camera', 'dollar-sign' ),
			'marketing' => array( 'megaphone', 'target', 'trending-up', 'bar-chart', 'users', 'share', 'globe', 'zap' ),
			'design' => array( 'pen-tool', 'palette', 'image', 'layers', 'eye', 'star', 'sparkles', 'camera' ),
			'foto' => array( 'camera', 'image', 'aperture', 'sun', 'film', 'eye', 'star', 'heart' ),
		);

		foreach ( $icon_sets as $key => $icons ) {
			if ( strpos( $industry_lower, $key ) !== false ) {
				return $icons;
			}
		}

		// Default generic icons
		return array( 'star', 'check', 'zap', 'award', 'users', 'shield', 'heart', 'globe' );
	}

	/**
	 * Generate industry-specific color suggestion
	 *
	 * @param string $industry Industry type
	 * @return array Color palette suggestion
	 */
	public static function industry_colors( $industry ) {
		$industry_lower = strtolower( $industry );

		$color_schemes = array(
			'it'         => array( 'primary' => '#3b82f6', 'accent' => '#8b5cf6' ),
			'tech'       => array( 'primary' => '#0ea5e9', 'accent' => '#06b6d4' ),
			'software'   => array( 'primary' => '#6366f1', 'accent' => '#8b5cf6' ),
			'beratung'   => array( 'primary' => '#1e40af', 'accent' => '#f97316' ),
			'consulting' => array( 'primary' => '#1e40af', 'accent' => '#f97316' ),
			'handwerk'   => array( 'primary' => '#ea580c', 'accent' => '#16a34a' ),
			'bau'        => array( 'primary' => '#ca8a04', 'accent' => '#0369a1' ),
			'gesundheit' => array( 'primary' => '#0891b2', 'accent' => '#10b981' ),
			'medizin'    => array( 'primary' => '#0284c7', 'accent' => '#0d9488' ),
			'handel'     => array( 'primary' => '#7c3aed', 'accent' => '#ec4899' ),
			'shop'       => array( 'primary' => '#db2777', 'accent' => '#f97316' ),
			'gastro'     => array( 'primary' => '#b91c1c', 'accent' => '#ca8a04' ),
			'restaurant' => array( 'primary' => '#991b1b', 'accent' => '#15803d' ),
			'bildung'    => array( 'primary' => '#1d4ed8', 'accent' => '#eab308' ),
			'finanzen'   => array( 'primary' => '#0f172a', 'accent' => '#16a34a' ),
			'immobilien' => array( 'primary' => '#0d9488', 'accent' => '#f97316' ),
			'marketing'  => array( 'primary' => '#e11d48', 'accent' => '#8b5cf6' ),
			'design'     => array( 'primary' => '#a855f7', 'accent' => '#ec4899' ),
			'foto'       => array( 'primary' => '#18181b', 'accent' => '#f97316' ),
		);

		foreach ( $color_schemes as $key => $colors ) {
			if ( strpos( $industry_lower, $key ) !== false ) {
				return $colors;
			}
		}

		// Default
		return array( 'primary' => '#3b82f6', 'accent' => '#f97316' );
	}

	/**
	 * Generate a decorative section with pattern background
	 *
	 * @param string $color Primary color
	 * @param string $pattern_type Pattern type
	 * @return string CSS
	 */
	public static function section_decoration_css( $color, $pattern_type = 'dots' ) {
		$pattern_svg = self::pattern( $color, $pattern_type );
		$encoded = 'data:image/svg+xml,' . rawurlencode( $pattern_svg );

		return "
		.decorated-section {
			position: relative;
		}
		.decorated-section::before {
			content: '';
			position: absolute;
			inset: 0;
			background-image: url(\"{$encoded}\");
			opacity: 0.5;
			pointer-events: none;
		}
		";
	}
}

/**
 * Helper function to get graphics instance
 *
 * @return Ramboeck_Graphics
 */
function ramboeck_graphics() {
	return new Ramboeck_Graphics();
}
