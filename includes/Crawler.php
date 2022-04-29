<?php

namespace WPScraper;

/**
 * Admin setting view.
 */
class Crawler {

	/**
	 * Constructer.
	 */
	public function __construct() {
		add_action( 'wp_ajax_wpscraper_ajax_submission', [ $this, 'run_crawler' ] );
		add_action( 'wpscraper_cleanup_scheduled_crawler', [ $this, 'run_scheduled_crawler' ] );

		add_action( 'wp_ajax_nopriv_show_infinite_loop_listing', [ $this, 'scrapping_show_infinite_loop_listingrecords' ] );
		add_action( 'wp_ajax_show_infinite_loop_listing', [ $this, 'scrapping_show_infinite_loop_listingrecords' ] );

		add_action( 'wp_ajax_nopriv_show_district_listing', [ $this, 'show_district_listing' ] );
		add_action( 'wp_ajax_show_district_listing', [ $this, 'show_district_listing' ] );

	}

	/**
	 * show district listing.
	 *
	 * @return void
	 */
	public function show_district_listing() {
		global $wpdb;
		$wpdb_prefix    = $wpdb->prefix;
		$wpdb_tablename = $wpdb_prefix . 'listings';
		// $_POST
		$district    = $_GET['district'];
		$district_id = $_GET['district_val'];

		if ( $district_id != '' ) {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb_tablename WHERE district = %s limit 10", $district ) );
		} else {
			$results = $wpdb->get_results( "SELECT * FROM $wpdb_tablename limit 10" );
		}

		if ( ! empty( $results ) ) {
			$html = '';
			foreach ( $results as $result ) {
				$html .= '<tr id="listing_deal_content_tr" class="listing_deal_content_tr">';
				$html .= '<td data-th="' . esc_attr__( 'Occ Code', 'wpscraper' ) . '">' . $result->occupational_code . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Occupation', 'wpscraper' ) . '"><a class="js-open-modal" href="javascript:void(0)" id="js-open-modal-' . $result->id . '" data-id="' . $result->id . '">' . $result->occupation . '</a></td>';
				$html .= '<td data-th="' . esc_attr__( 'Program Name', 'wpscraper' ) . '">' . $result->program_name . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Program Hours', 'wpscraper' ) . '">' . $result->program_hour . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'District', 'wpscraper' ) . '">' . $result->district . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Action', 'wpscraper' ) . '"><a class="contact_form" href="javascript:void(0)" id="contact_form-' . $result->id . '>" data-occupation="' . $result->occupation . '" data-program_name="' . $result->program_name . '" data-listing_id="' . $result->listing_id . '" data-district="' . $result->district . '">' . _( 'Contact us' ) . '</a></td>';

				$html .= '<input type="hidden" id="description-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->program_description ) . '">';
				$html .= '<input type="hidden" id="ktitle-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->occupation ) . '">';
				$html .= '</tr>';

				$response['success'] = 1;
			}
		} else {
			$html                = '<div class="text-center col-sm-12" id="no_results">';
			$html               .= '<h2> ' . esc_html__( 'No Results', 'wpscraper' ) . ' </h2>';
			$html               .= '</div>';
			$response['success'] = 0;
		}

		$response['html'] = $html;
		echo json_encode( $response );
		exit;
	}

	/**
	 * Scrapping show infinite loop listingrecords.
	 *
	 * @return void
	 */
	public function scrapping_show_infinite_loop_listingrecords() {
		global $wpdb;
		$wpdb_prefix    = $wpdb->prefix;
		$wpdb_tablename = $wpdb_prefix . 'listings';
		$limit    = absint( $_GET['limit'] );
		$start    = absint( $_GET['start'] );
		$district = ( isset( $_GET['district'] ) ? sanitize_title( wp_unslash( $_GET['district'] ) ) : '' );

		if ( $district != '' && $district != 'Select District' ) {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb_tablename WHERE district = %s limit %d,%d", $district, $start, $limit ) );
		} else {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb_tablename limit %d,%d", $start, $limit ) );
		}
		if ( ! empty( $results ) ) {
			$html = '';
			foreach ( $results as $result ) {
				$html .= '<tr  id="listing_deal_content_tr" class="listing_deal_content_tr">';
				$html .= '<td data-th="' . esc_attr__( 'Occ Code', 'wpscraper'  ) . '">' . esc_html( $result->occupational_code ) . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Occupation' , 'wpscraper' ) . '"><a class="js-open-modal" href="javascript:void(0)" id="js-open-modal-' . esc_attr( $result->id  ) . '" data-id="' . esc_attr( $result->id  ) . '">' . esc_attr( $result->occupation  ) . '</a></td>';
				$html .= '<td data-th="' . esc_attr__( 'Program Name', 'wpscraper'  ) . '">' . esc_html( $result->program_name ) . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Program Hours', 'wpscraper'  ) . '">' . esc_html( $result->program_hour ) . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'District', 'wpscraper' ) . '">' . esc_html( $result->district ) . '</td>';
				$html .= '<td data-th="' . esc_attr__( 'Action', 'wpscraper' ) . '"><a class="contact_form" href="javascript:void(0)" id="contact_form-' . esc_attr( $result->id ) . '" data-occupation="' . esc_attr( $result->occupation ) . '" data-program_name="' . esc_attr( $result->program_name ) . '" data-listing_id="' . esc_attr( $result->listing_id ) . '" data-district="' . esc_attr( $result->district ) . '">' . esc_html__( 'Contact us', 'wpscraper' ) . '</a></td>';

				$html .= '<input type="hidden" id="description-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->program_description ) . '">';
				$html .= '<input type="hidden" id="ktitle-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->occupation ) . '">';
				$html .= '</tr>';

				$response['success'] = 1;
			}
		} else {
			$html                = '<div class="text-center col-sm-12" id="no_results">';
			$html               .= '<h2> ' . esc_html__( 'No Results', 'wpscraper' ) . ' </h2>';
			$html               .= '<p>' . esc_html__( 'Sorry! There are no more results.', 'wpscraper' ) . '</p>';
			$html               .= '</div>';
			$response['success'] = 0;
		}

		$response['html'] = $html;
		echo json_encode( $response );
		exit;
	}

	/**
	 * Run crawler.
	 *
	 * @return mixed
	 */
	public function run_crawler() {
		$data = $this->run_scheduled_crawler( true );
		wp_send_json_success( $data );
	}

	/**
	 * Run scheduled crawler.
	 *
	 * @param bool $return
	 * @return mixed
	 */
	public function run_scheduled_crawler( $return = false ) {
		global $wpdb;
		$wp_track_table = 'listings';
		$tblname        = $wpdb->prefix . $wp_track_table;

		/* Before Truncate */
		$wpdb->query( 'TRUNCATE TABLE ' . $tblname );
		/* After Truncate */
		$MasterArr = [];
		$cnt       = 0;
		$url       = 'https://web02.fldoe.org/Apprenticeship/search.aspx?a=FL004';
		// $url = "http://localhost/theoshaman/parsingHTML.html";
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$try = 1;
		while ( $try <= 5 ) {
			$output = curl_exec( $ch );
			if ( ! empty( $output ) ) {
				break;
			}
			$try++;
			sleep( mt_rand( 2, 5 ) );
		}

		if ( empty( $output ) ) {
			return;
		}

		curl_close( $ch );

		\phpQuery::newDocumentHTML( $output );
		$items = pq( 'table#ctl00_ContentPlaceHolder1_tblResults tr:contains("Occupations Covered:") td:eq(1) ul li' );

		foreach ( $items as $item ) {
			$item           = pq( $item );
			$occupation     = $item->text();
			$occupation_url = $item->find( 'a' )->attr( 'href' );

			$url = 'https://web02.fldoe.org/Apprenticeship/' . $occupation_url;
			// $url = "http://localhost/theoshaman/details.html";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			$try = 1;
			while ( $try <= 5 ) {
				$output = curl_exec( $ch );
				if ( ! empty( $output ) ) {
					break;
				}
				$try++;
				sleep( mt_rand( 2, 5 ) );
			}

			if ( empty( $output ) ) {
				continue;
			}

			\phpQuery::newDocumentHTML( $output );
			$rows                = pq( 'table#ctl00_ContentPlaceHolder1_tblResults table tr:gt(0)' );
			$program_description = pq( 'table#ctl00_ContentPlaceHolder1_tblResults tr:eq(1) dd ' )->text();
			foreach ( $rows as $row ) {
				$row = pq( $row );
				if ( (int) $row->find( 'td:eq(4)' )->text() === 4 ) {
					$occupational_code                        = $row->find( 'td:eq(0)' )->text();
					$program_name                             = $row->find( 'td:eq(1)' )->text();
					$program_hour                             = $row->find( 'td:eq(2)' )->text();
					$sponsor                                  = $row->find( 'td:eq(3)' )->text();
					$region                                   = $row->find( 'td:eq(4)' )->text();
					$district                                 = $row->find( 'td:eq(5)' )->text();
					$MasterArr[ $cnt ]['occupation']          = $occupation;
					$MasterArr[ $cnt ]['occupational_code']   = $occupational_code;
					$program_name_arr                         = explode( ',', $program_name );
					$MasterArr[ $cnt ]['listing_id']          = ( isset( $program_name_arr[1] ) ) ? $program_name_arr[1] : '';
					$MasterArr[ $cnt ]['program_name']        = ( isset( $program_name_arr[0] ) ) ? $program_name_arr[0] : '';
					$MasterArr[ $cnt ]['program_description'] = $program_description;
					$MasterArr[ $cnt ]['program_hour']        = $program_hour;
					$MasterArr[ $cnt ]['sponsor']             = $sponsor;
					$MasterArr[ $cnt ]['region']              = $region;
					$MasterArr[ $cnt ]['district']            = $district;
					$MasterArr[ $cnt ]['created_at']          = date( 'Y-m-d H:i:s' );
					$MasterArr[ $cnt ]['updated_at']          = date( 'Y-m-d H:i:s' );
					$wpdb->insert( $tblname, $MasterArr[ $cnt ] );
					$cnt++;
				}
			}
		}

		if ( $return ) {
			return $MasterArr;
		}
	}
}
