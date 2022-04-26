<?php
namespace WPScraper\View;

/**
 * Frontend Listing View.
 */
class Listing {

	/**
	 * Constructer.
	 */
	public function __construct() {
		add_shortcode( 'form_listing_records', [ $this, 'scrapping_form_listing_html' ] );
	}

    public function scrapping_form_listing_html( $args ) {
		global $wpdb;
		$wpdb_tablename = $wpdb->prefix . 'listings';
		// $sql = 'SELECT DISTINCT(district) as unique_district,id,occupation FROM ' .$wpdb_tablename .' GROUP by (unique_district) order by id asc limit 10' ;
		$occupations     = $wpdb->get_results( 'SELECT occupation,id,district AS unique_district FROM ' . $wpdb_tablename . ' GROUP by (occupation) order by id asc ' );
		$districts       = $wpdb->get_results( 'SELECT DISTINCT(district) as unique_district,id,occupation FROM ' . $wpdb_tablename . ' GROUP by (unique_district) order by id asc' );
		$current_url = get_permalink( get_the_ID() );
		echo '<div class="genesisprd-container">';
		echo '<form autocomplete="off" class="form-inline" method="get" action="' . esc_attr( get_permalink( get_the_ID() ) ) . '">';
		echo '<div class="form-group lp-search-bar lp-suggested-search">';
		echo '<div class="pos-relative">';
		echo '<div class="what-placeholder pos-relative" data-holder="">';
		echo '<select name="occupation" id="occupation" class="form-select" aria-label="Default select example" required>';
		echo '<option value="">' . esc_html__( 'Select Occupation', 'wpretail' ) . '</option>';
		if ( ! empty( $occupations ) ) {    
			foreach ( $occupations as $listing ) {
				?>
			<option value="<?php echo $listing->id; ?>" data-district="<?php echo $listing->unique_district; ?>"><?php echo $listing->occupation; ?></option>
				<?php
			}
		}
		echo '</select>';
		echo '<input type="hidden" id="region_current_url" name="region_current_url" value="' . sanitize_url( $current_url ) . '" >';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group pull-right searchhidemarginright">';
		echo '<div class="lp-search-bar-right lp-search-bar-right-searchmain">';
		echo '<input value="Search" class="lp-search-btn lp-search-btn-submit" type="submit">';
		echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '<section id="features" class="features-listing-deals">';
		echo '<div class="html_data"><h3 class="explore-city_title">' . esc_html__( 'Workforce Education', 'wpscraper' ) . '</h3> </div>';
		echo '<div class="main-right">';
		echo '<div class="form-group lp-search-bar lp-suggested-search district-col4">';
		echo '<div data-option="no" class="what-placeholder pos-relative">';
		echo '<select name="district-search" id="district-search" class="form-select" aria-label="Default select example">';
		echo '<option value="">' . esc_html( 'Select County', 'wpscraper' ) . '</option>';
		if ( ! empty( $districts ) ) {
			foreach ( $districts as $listing ) {
				?>
			<option value="<?php echo $listing->id; ?>"><?php echo $listing->unique_district; ?></option>
				<?php
			}
		}
		echo '</select>';
		echo '<input type="hidden" id="district-select-id" name="district_id" value="" >';
		echo '</div>';
		echo '</div>';
		echo '<div data-option="no" class="what-placeholder reset_col">';
		echo '<input value="Reset" class="lp-search-btn" id="reset_district_action" type="submit">';
		echo '</div>';
		echo '</div>';
		echo '<div class="arrange arrange--wrap arrange--3-units arrange--30 " id="listing_deal_content">';
					global $wpdb;
					$wpdb_prefix    = $wpdb->prefix;
					$wpdb_tablename = $wpdb_prefix . 'listings';
					$sql            = 'SELECT DISTINCT * FROM ' . $wpdb_tablename . ' LIMIT 10';
					$results        = $wpdb->get_results( $sql, OBJECT );
					// $results=array_unique($results);
		?>
					<div class="container">
						<table class="rwd-table checkpoint1">
							<tbody id="listing_deal_content_body">
								<tr>
									<th><?php echo esc_html_e( 'Occ Code' ); ?></th>
									<th><?php echo esc_html_e( 'Occupation' ); ?></th>
									<th><?php echo esc_html_e( 'Program Name' ); ?></th>
									<th><?php echo esc_html_e( 'Program Hours' ); ?></th>
									<th><?php echo esc_html_e( 'District' ); ?></th>
									<th><?php echo esc_html_e( 'Action' ); ?></th>
								</tr>
					<?php
					if ( ! empty( $results ) ) :
						foreach ( $results as $result ) :

							?>
								<tr id="listing_deal_content_tr" class="listing_deal_content_tr">
									<td data-th="<?php echo esc_html_e( 'Occ Code' ); ?>">
										<?php echo $result->occupational_code; ?>
									</td>
									<td data-th="<?php echo esc_html_e( 'Occupation' ); ?>">
										<a class="js-open-modal" href="javascript:void(0)" id="js-open-modal-<?php echo $result->id; ?>" data-id="<?php echo $result->id; ?>"><?php echo $result->occupation; ?></a>
									</td>
									<td data-th="<?php echo esc_html_e( 'Program Name' ); ?>">
										<?php echo $result->program_name; ?>
									</td>
									<td data-th="<?php echo esc_html_e( 'Program Hours' ); ?>">
										<?php echo $result->program_hour; ?>
									</td>
									<td data-th="<?php echo esc_html_e( 'District' ); ?>">
										<?php echo $result->district; ?>
									</td>
									<td data-th="<?php echo esc_html_e( 'Action' ); ?>">
										<a class="contact_form" href="javascript:void(0)" id="contact_form-<?php echo $result->id; ?>" data-occupation="<?php echo $result->occupation; ?>" data-program_name="<?php echo $result->program_name; ?>" data-listing_id="<?php echo $result->listing_id; ?>" data-district="<?php echo $result->district; ?>"><?php echo esc_html_e( 'Contact us' ); ?></a><br/>
									</td>
								</tr>
								<input type="hidden" id="description-<?php echo $result->id; ?>" value="<?php echo $result->program_description; ?>">
								<input type="hidden" id="ktitle-<?php echo $result->id; ?>" value="<?php echo $result->occupation; ?>">
							<?php
					endforeach;
					endif;
					?>
							</tbody>
						</table>
					</div>
					<div id="modal-dialog" class="genesisprd_contact_dialog" style="display: none" align="center">
						<?php
						echo \FrmFormsController::get_form_shortcode(
							[
								'id'          => 7,
								'title'       => false,
								'description' => true,
							]
						);
						?>
					</div>
					
					<div id="modal-dialog" class="genesisprd_dialog" style="display: none" align="center">
						<p class="desc"></p>
					</div>
				</div>
				
				<div class="arrange arrange--wrap arrange--3-units arrange--30 " id="load_genesisprd_data"></div>
				<div id="load_genesisprd_message" class="genesisprd-col4 loading_data" style="text-align:center;"></div>
				<div class="infinite_scroll_filter_data"></div>
					
			</section>
		</div>
		<?php
	}
}
