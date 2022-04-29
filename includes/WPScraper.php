<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
class WPScraper {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected static $instance;

	/**
	 * WPScraper Constructor.
	 */
	public function __construct() {
		// Initilize.
		$this->init();
		add_action( 'admin_enqueue_scripts', [ $this, 'wpscraper_admin_script' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'wpscraper_frontend_script' ] );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function init() {
		include_once plugin_dir_path( WPSCRAPER_PLUGIN_FILE ) . 'includes/library/PhpQuery.php';
		new WPScraper\View\Setting();
		new WPScraper\View\Listing();
		new WPScraper\Crawler();
	}

	/**
	 * Wpscraper scripts.
	 *
	 * @return void
	 */
	public function wpscraper_admin_script() {
		wp_enqueue_script( 'wpscraper_jquery', 'https://code.jquery.com/jquery-3.6.0.js', WPSCRAPER_VERSION );
		wp_enqueue_style( 'scrapping-backend-style-wpsp', plugins_url( 'asset/admin/css/style-admin.css', WPSCRAPER_PLUGIN_FILE ), WPSCRAPER_VERSION );
		wp_register_script( 'wpscraper_custom_js', plugins_url( 'asset/admin/js/custom-admin.js', WPSCRAPER_PLUGIN_FILE ), WPSCRAPER_VERSION, [ 'jquery' ], '1.0' );

		wp_localize_script(
			'wpscraper_custom_js',
			'wpscraperSettingsParams',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'wpscraper_run_crawler' ),
			]
		);
		wp_enqueue_script( 'wpscraper_custom_js' );
	}

	/**
	 * Wpscraper scripts.
	 *
	 * @return void
	 */
	public function wpscraper_frontend_script() {
		wp_enqueue_style( 'wpscraper_style', plugins_url( 'asset/css/style.css', WPSCRAPER_PLUGIN_FILE ), [], WPSCRAPER_VERSION );
		wp_register_script( 'wpscraper_js', plugins_url( 'asset/js/custom.js', WPSCRAPER_PLUGIN_FILE ), [ 'jquery' ], WPSCRAPER_VERSION );
		wp_localize_script( 'wpscraper_js', 'export_all_ajax_object', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

		wp_enqueue_style( 'scrapping-jquery-ui-css', plugins_url( 'asset/lib/jquery-ui/css/jquery-ui.css', WPSCRAPER_PLUGIN_FILE ), [], WPSCRAPER_VERSION );
		wp_enqueue_script( 'scrapping-jquery-ui-js', plugins_url( 'asset/lib/jquery-ui/js/jquery-ui.js', WPSCRAPER_PLUGIN_FILE ), [ 'jquery' ], WPSCRAPER_VERSION );
		wp_enqueue_script( 'scrapping-infinite-scroll-js', plugins_url( 'asset/lib/infinite-scroll/infinite-scroll.pkgd.js', WPSCRAPER_PLUGIN_FILE ),[], WPSCRAPER_VERSION );
	}

	/**
	 * Main WPRetail Instance.
	 *
	 * Ensures only one instance of WPRetail is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @static
	 * @see    WPRetail()
	 * @return WPRetail - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
