<?php
/**
 * Plugin Name: WP-Scraper
 * Plugin URI: #
 * Description: WordPress plugin for scrapping data and store into database.
 * Version: 1.0.0
 * Author: milan1750
 * Author URI: https://github.com/milan1750
 * Text Domain: wpscraper
 * Domain Path: /languages/
 * Requires at least: 5.4
 * Requires PHP: 5.6.20
 *
 * @package WPScraper
 */

// Exit if access directly.
defined( 'ABSPATH' ) || exit;

// WPScraper version.
if ( ! defined( 'WPSCRAPER_VERSION' ) ) {
	define( 'WPSCRAPER_VERSION', '1.0.0' );
}

// WPScraper root file.
if ( ! defined( 'WPSCRAPER_PLUGIN_FILE' ) ) {
	define( 'WPSCRAPER_PLUGIN_FILE', __FILE__ );
}

/**
 * Autoload packages.
 *
 * We want to fail gracefully if `composer install` has not been executed yet, so we are checking for the autoloader.
 * If the autoloader is not present, let's log the failure and display a nice admin notice.
 */
$autoloader = __DIR__ . '/vendor/autoload.php';
if ( is_readable( $autoloader ) ) {
	include $autoloader;
} else {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			sprintf(
			/* translators: 1: composer command. 2: plugin directory */
				esc_html__( 'Your installation of the WPScraper plugin is incomplete. Please run %1$s within the %2$s directory.', 'wpscraper' ),
				'`composer install`',
				'`' . esc_html( str_replace( ABSPATH, '', __DIR__ ) ) . '`'
			)
		);
	}

	/**
	 * Outputs an admin notice if composer install has not been ran.
	 *
	 * @since 1.0.0
	 */
	add_action(
		'admin_notices',
		function () {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				sprintf(
					/* translators: 1: composer command. 2: plugin directory */
					esc_html__( 'Your installation of the WPScraper plugin is incomplete. Please run %1$s within the %2$s directory.', 'wpscraper' ),
					'<code>composer install</code>',
					'<code>' . esc_html( str_replace( ABSPATH, '', __DIR__ ) ) . '</code>'
				)
			);
		}
	);
	return;
}

// Include the main WPScraoer class.
if ( ! class_exists( 'WPScraper' ) ) {
	include_once dirname( __FILE__ ) . '/includes/WPScraper.php';
}

/**
 * Main instance of WPScraoer.
 *
 * Returns the main instance of WPScraoer to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WPScraper
 */
function wpscraper() {
	return WPScraper::instance();
}

wpscraper();
