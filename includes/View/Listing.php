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
		add_shortcode( 'form_occuption_description_records', array($this,'scrapping_form_occuption_description_html' ));
	}

	/**
	 * Frontend Listing.
	 *
	 * @param mixed $args Args.
	 * @return void
	 */
	public function scrapping_form_listing_html( $args ) {
		global $wpdb;

		$wpdb_tablename = $wpdb->prefix.'posts';
        //echo "SELECT guid FROM $wpdb_tablename WHERE post_content LIKE '%[form_occuption_description_records]%' AND post_type = 'page' Limit 1";exit;
        $page_id = $wpdb->get_var("SELECT ID FROM $wpdb_tablename WHERE post_content LIKE '%[form_occuption_description_records]%' AND post_type = 'page' Limit 1");

		$wpdb_tablename = $wpdb->prefix . 'listings';
		// $sql = 'SELECT DISTINCT(district) as unique_district,id,occupation FROM ' .$wpdb_tablename .' GROUP by (unique_district) order by id asc limit 10' ;
		$occupations = $wpdb->get_results( 'SELECT occupation,id,district AS unique_district FROM ' . $wpdb_tablename . ' GROUP by (occupation) order by id asc ' );
		$districts   = $wpdb->get_results( 'SELECT DISTINCT(district) as unique_district,id,occupation FROM ' . $wpdb_tablename . ' GROUP by (unique_district) order by id asc' );
		$current_url = get_permalink( get_the_ID() );
		echo '<div class="genesisprd-container">';
		echo '<form autocomplete="off" class="form-inline" method="get" action="' . esc_attr( get_permalink( $page_id ) ) . '">';
		echo '<div class="form-group lp-search-bar lp-suggested-search">';
		echo '<div class="pos-relative">';
		echo '<div class="what-placeholder pos-relative" data-holder="">';
		echo '<select name="occupation" id="occupation" class="form-select" aria-label="Default select example" required>';
		echo '<option value="">' . esc_html__( 'Select Occupation', 'wpretail' ) . '</option>';
		if ( ! empty( $occupations ) ) {
			foreach ( $occupations as $listing ) {
				echo '<option value="' . $listing->occupation . '" data-district="' . esc_attr( $listing->unique_district ) . '">' . esc_html( $listing->occupation ). '</option>';
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
				echo '<option value="' . esc_attr( $listing->id ). '">' . esc_html( $listing->unique_district ) . '</option>';
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
		$wpdb_prefix    = $wpdb->prefix;
		$wpdb_tablename = $wpdb_prefix . 'listings';
		$sql            = 'SELECT DISTINCT * FROM ' . $wpdb_tablename . ' LIMIT 10';
		$results        = $wpdb->get_results( $sql, OBJECT );
		echo '<div class="container">';
		echo '<table class="rwd-table checkpoint1">';
		echo '<tbody id="listing_deal_content_body">';
		echo '<tr>';
		echo '<th>'.esc_html__( 'Occ Code', 'wpscraper' ) . '</th>';
		echo '<th>'.esc_html__( 'Occupation', 'wpscraper' ) . '</th>';
		echo '<th>'.esc_html__( 'Program Name', 'wpscraper' ) . '</th>';
		echo '<th>'.esc_html__( 'Program Hours', 'wpscraper' ) . '</th>';
		echo '<th>'.esc_html__( 'District', 'wpscraper' ) . '</th>';
		echo '<th>'.esc_html__( 'Action', 'wpscraper' ) . '</th>';
		echo '</tr>';
		if ( ! empty( $results ) ) :
			foreach ( $results as $result ) :
				echo '<tr id="listing_deal_content_tr" class="listing_deal_content_tr">';
				echo '<td data-th="' . esc_attr__( 'Occ Code', 'wpscraper' ) . '">' . esc_html( $result->occupational_code ) . '</td>';
				echo '<td data-th="' . esc_attr__( 'Occupation', 'wpscraper' ) . '">';
				echo '<a class="js-open-modal" href="javascript:void(0)" id="js-open-modal-' . esc_attr( $result->id ) . '" data-id="' . esc_attr( $result->id ) . '">' . esc_html( $result->occupation ) . '</a>';
				echo '</td>';
				echo '<td data-th="' . esc_attr__( 'Program Name', 'wpscraper' ) . '">' . esc_html( $result->program_name ) . '</td>';
				echo '<td data-th="' . esc_attr__( 'Program Hours', 'wpscraper' ) . '">' . esc_html( $result->program_hour ) . '</td>';
				echo '<td data-th="' . esc_attr__( 'District', 'wpscraper' ) . '">' . esc_html( $result->district ) . '</td>';
				echo '<td data-th="' . esc_attr__( 'Action', 'wpscraper' ) . '">';
				echo '<a class="contact_form" href="javascript:void(0)" id="contact_form-' . esc_attr( $result->id ) . '" data-occupation="' . esc_attr( $result->occupation ) . '" data-program_name="' . esc_attr( $result->program_name ) . '" data-listing_id="' . esc_attr( $result->listing_id ) . '" data-district="' . esc_attr( $result->district ) . '">' .esc_html__( 'Contact us', 'wpscraper' ) . '</a><br/>';
				echo '</td>';
				echo '</tr>';
				echo '<input type="hidden" id="description-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->program_description ) . '">';
				echo '<input type="hidden" id="ktitle-' . esc_attr( $result->id ) . '" value="' . esc_attr( $result->occupation ) . '">';
			endforeach;
		endif;
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		echo '<div id="modal-dialog" class="genesisprd_contact_dialog" style="display: none" align="center">';
		echo \FrmFormsController::get_form_shortcode(
			[
				'id'          => 7,
				'title'       => false,
				'description' => true,
			]
		);
		echo '</div>';
		echo '<div id="modal-dialog" class="genesisprd_dialog" style="display: none" align="center">';
		echo '<p class="desc"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="arrange arrange--wrap arrange--3-units arrange--30 " id="load_genesisprd_data"></div>';
		echo '<div id="load_genesisprd_message" class="genesisprd-col4 loading_data" style="text-align:center;"></div>';
		echo '<div class="infinite_scroll_filter_data"></div>';
		echo '</section>';
		echo '</div>';
		wp_enqueue_script( 'wpscraper_js' );
		wp_enqueue_script('wpscraper_js-infinite-scroll-js', plugins_url('asset/lib/infinite-scroll/infinite-scroll.pkgd.js',WPSCRAPER_PLUGIN_FILE) );
	}

	/**
	 * Description Short Code Implementation.
	 *
	 * @return void
	 */
	public function scrapping_form_occuption_description_html(){
        global $wpdb;
        $wpdb_prefix = $wpdb->prefix;
        $wpdb_tablename = $wpdb_prefix.'listings';
        $region_current_url = $_REQUEST['region_current_url'];
        $id = $_REQUEST['id'];
        $description_page = get_page_by_path('/description',$post_type = 'page');
	    $occupation = $_REQUEST['occupation'];
        $sql = "SELECT occupation,program_description,program_name,program_hour,occupational_code,district,sponsor,region,listing_id FROM $wpdb_tablename where occupation = '".$occupation."' order by id asc ";
        if( isset($occupation) ){
            $listings = $wpdb->get_results($sql);


            if(!empty($listings)):

            ?>
                <div class="description">
                    <div class="heading_title">
                        <h3 class="pull-left"><?php echo $listings[0]->occupation ?></h3>
                        <?php if($region_current_url!=''){?>
                        <a href="<?php echo $region_current_url;?>" class="pull-right"><?php echo esc_html_e( 'Back' );?></a>
                        <?php } ?>
                    </div>
                    <div class="description">
                        <p><?php echo $listings[0]->program_description?></p>
                    </div>
                </div>
                <div class="container">
                    <table class="rwd-table checkpoint">
                        <tbody>
                            <tr>
                                <th><?php echo esc_html_e( 'Occ Code' );?></th>
                                <th><?php echo esc_html_e( 'Program Name' );?></th>
                                <th><?php echo esc_html_e( 'Program Hours' );?></th>
                                <!-- <th><?php echo esc_html_e( 'Sponsor' );?></th>
                                <th><?php echo esc_html_e( 'Region' );?></th> -->
                                <th><?php echo esc_html_e( 'District' );?></th>
                                <th><?php echo esc_html_e( 'Action' );?></th>
                            </tr>
							<?php
								foreach($listings as $listing): ?>
								<tr>
                                <td data-th="<?php echo esc_html_e( 'Occ Code' );?>">
                                    <?php echo $listing->occupational_code;?>
                                </td>
                                <td data-th="<?php echo esc_html_e( 'Program Name' );?>">
                                    <?php echo $listing->program_name;?>
                                </td>
                                <td data-th="<?php echo esc_html_e( 'Program Hours' );?>">
                                    <?php echo $listing->program_hour;?>
                                </td>
                                <!-- <td data-th="Sponsor">
                                 <?php echo $listing->sponsor;?>
                                </td>
                                <td data-th="Region">
                                    <?php echo $listing->region;?>
                                </td> -->
                                <td data-th="<?php echo esc_html_e( 'District' );?>">
                                    <?php echo $listing->district;?>
                                </td>
                                <td data-th="<?php echo esc_html_e( 'Action' );?>">
                                    <a class="contact_form" href="javascript:void(0)" id="contact_form-<?php echo $listing->id;?>" data-occupation="<?php echo $listing->occupation;?>" data-program_name="<?php echo $listing->program_name;?>" data-listing_id="<?php echo $listing->listing_id;?>" data-district="<?php echo $listing->district;?>"><?php echo esc_html_e('Contact us');?></a><br/>
                                </td>

                            	</tr>
								<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="modal-dialog" class="genesisprd_contact_dialog" style="display: none" align="center">
                    <?php echo \FrmFormsController::get_form_shortcode( array( 'id' => 7, 'title' => false, 'description' => true ) ); ?>
                </div>

            <?php
            endif;
			wp_enqueue_script( 'wpscraper_js' );
        }
    }
}
