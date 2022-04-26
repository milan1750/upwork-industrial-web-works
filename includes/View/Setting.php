<?php
namespace WPScraper\View;

/**
 * Admin Setting View.
 */
class Setting {

	/**
	 * Constructer.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'scrapping_settings_init' ] );
		// add_action( 'admin_init', [ $this, 'admin_setting_view' ] );
	}

	/**
	 * Scrapping settings init.
	 *
	 * @return void
	 */
	public function scrapping_settings_init() {
		register_setting( 'scrapping', 'wpscraper_options' );
		add_settings_section(
			'wpscrapper_settings',
			__( 'Settings for Scrapping Plugin.', 'scrapping' ),
			[ $this, 'wpscrapper_settings_callback' ],
			'scrapping'
		);
		add_settings_field(
			'wpscraper_cronexecution_schedule_period',
			__( 'CRON Execution Period', 'scrapping' ),
			[ $this, 'scrapping_field_scheduled_period' ],
			'scrapping',
			'wpscrapper_settings',
			[
				'option_group' => 'wpscraper_options',
				'id'           => 'wpscraper_cronexecution_schedule_period',
				'name'         => 'wpscraper_cronexecution_schedule_period',
				'value'        => ! empty( get_option( 'wpscraper_options' ) ) ? get_option( 'wpscraper_options' )['wpscraper_cronexecution_schedule_period'] : '',
			]
		);

		add_settings_field(
			'wpscraper_cronexecution_schedule_time',
			__( 'CRON Execution Time', 'scrapping' ),
			[ $this, 'scrapping_field_scheduled_time' ],
			'scrapping',
			'wpscrapper_settings',
			[
				'option_group' => 'wpscraper_options',
				'id'           => 'wpscraper_cronexecution_schedule_time',
				'name'         => 'wpscraper_cronexecution_schedule_time',
				'value'        => ! empty( get_option( 'wpscraper_options' ) ) ? get_option( 'wpscraper_options' )['wpscraper_cronexecution_schedule_time'] : '',
			]
		);
	}

	public function scrapping_field_scheduled_period( $args ) {
		echo '<select name="wpscraper_options[' . $args['id'] . ']" id="' . $args['id'] . '">';
		echo '<option value="hourly" ' . ( 'hourly' === $args['value'] ? 'selected' : '' ) . '>' . esc_html__( 'Hourly', 'wpscraper' ) . '</option>';
		echo '<option value="daily" ' . ( 'daily' === $args['value'] ? 'selected' : '' ) . '>' . esc_html__( 'Daily', 'wpscraper' ) . '</option>';
		echo '<option value="twicedaily" ' . ( 'twicedaily' === $args['value'] ? 'selected' : '' ) . '>' . esc_html__( 'Twicedaily', 'wpscraper' ) . '</option>';
		echo '</select>';
	}

	public function scrapping_field_scheduled_time( $args ) {
		echo '<input type="text" value="' . $args['value'] . '" placeholder="13:50" name="wpscraper_options[' . $args['id'] . ']" id="' . $args['id'] . '"/>';
	}

	public static function scrapping_field_scrapping_custom_days( $args ) {
		$options = get_option( $args['option_group'] );
		$value   = ( ! isset( $options[ $args['name'] ] ) ) ? null : $options[ $args['name'] ];
		echo '<input type="number" id="scrapping_custom_days_toggle" name="scrapping_options[' . esc_attr( $args['name'] ) . ']" value ="' . esc_attr( $value ) . '" placeholder="Custom Days">';
	}

	/**
	 * Settings callbacjk.
	 *
	 * @param mixed $args
	 * @return void
	 */
	public static function wpscrapper_settings_callback( $args ) {
	}

	/**
	 * Setting Section Callback.
	 *
	 * @return void
	 */
	function setting_section() {
		echo '<p>' . esc_html( 'CRON Settings', 'wpscraper' ) . '</p>';
	}

	/**
	 * Admin Menu.
	 *
	 * @return void
	 */
	function admin_menu() {
		add_menu_page(
			__( 'Scrapping', 'wpscraper' ),
			__( 'Scrapping', 'wpscraper' ),
			'manage_options',
			'wpscraper',
			[ $this, 'my_admin_page_contents' ],
			'dashicons-schedule',
			3
		);
	}

	/**
	 * Admin Page Contents.
	 *
	 * @return void
	 */
	public function my_admin_page_contents() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'scrapping_messages', 'scrapping_messages', __( 'Settings Saved', 'scrapping' ), 'updated' );
		}
		// show error/update messages
		settings_errors( 'scrapping_messages' );
		echo '<div class="wrap">';
		echo '<form action="options.php" method="post" id="scrapping-form">';
		// output security fields for the registered setting "wporg"
		settings_fields( 'scrapping' );
		// output setting sections and their fields
		// (sections are registered for "wporg", each field is registered to a specific section)
		do_settings_sections( 'scrapping' );
		// output save settings button

		echo '<div class="wpscraper-admin-buttons">';

		echo '<button type="submit" name="submit" id="submit" class="button button-primary">' . esc_html__( 'Save Setting', 'wpscraper' ) . '</button>';
		echo '&nbsp;&nbsp;<button type="button" class="button button-default wpscraper-run">' . esc_html__( 'Run Crawler', 'wpscraper' ) . '</button>';

		echo '</div>';

		echo '</form>';
		echo '</div>';
	}
}
