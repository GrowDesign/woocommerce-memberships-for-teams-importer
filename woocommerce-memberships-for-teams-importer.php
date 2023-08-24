<?php
/**
 * Plugin Name:       WooCommerce Membership For Teams Importer
 * Plugin URI:        https://github.com/GrowDesign/woocommerce-memberships-for-teams-importer
 * Description:       Handle the additional code needed to customize WooCommerce Membership for Teams
 * Version:           2023.8.23
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author:            Bradford Knowlton
 * Author URI:        https://bradknowlton.com/
 * License:           BSD 3-clause
 * License URI:       https://opensource.org/licenses/BSD-3-Clause
 * Domain Path:       /languages
 */
 
 /**
 * Filter CSV User Membership import data before processing an import.
 *
 * @since 1.6.0
 *
 * @param array $import_data the imported data as associative array
 * @param string $action either 'create' or 'merge' (update) a User Membership
 * @param array $columns CSV columns raw data
 * @param array $row CSV row raw data
 * @param \stdClass $job import job
 */
function wc_memberships_csv_import_user_memberships_for_teams_data( $import_data, $action, $columns, $row, $job ) {
	global $team_id;
	if(isset($row['team_id']) && is_numeric($row['team_id']) && 0 < $row['team_id'] ){
		$team_id = $row['team_id'];
	}
    return $import_data;
}
add_filter( 'wc_memberships_csv_import_user_memberships_data', 'wc_memberships_csv_import_user_memberships_for_teams_data', 10, 5 );

/**
 * Fires upon creating or updating a User Membership from import data.
 *
 * @since 1.6.0
 *
 * @param \WC_Memberships_User_Membership $user_membership User Membership object
 * @param string $action either 'create' or 'merge' (update) a User Membership
 * @param array $data import data
 * @param \stdClass $job import job
 */
function wc_memberships_csv_import_user_membership_for_teams( $user_membership, $action, $import_data, $job ) {
	global $team_id;
	if ( 0 < $team_id ){
		$team = wc_memberships_for_teams_get_team( $team_id );
		$team->add_member( $import_data['user_id'] );
	}
}
add_filter( 'wc_memberships_csv_import_user_membership', 'wc_memberships_csv_import_user_membership_for_teams', 10, 4 );
