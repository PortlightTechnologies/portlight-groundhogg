<?php
/*
 * Plugin Name: Portlight Groundhogg
 * Plugin URI:
 * Description: Portlight Groundhogg.
 * Version: 1.0
 * Author: Portlight Technologies
 * Author URI:
 * Text Domain: portlight-groundhogg
 * Domain Path: /languages
 */
namespace PortlightGroundhogg;

if (!empty($_POST['From']) && !empty($_POST['Body']) && !empty($_POST['To'])) {
	
	$_POST['From'] = str_replace("+", "", $_POST['From']);
	
	if(is_numeric($_POST['From']) && strlen($_POST['From']) == 11) {
		
		global $wpdb;
		
		$from = $_POST['From'];
    	$body = $_POST['Body'];

		$results = $wpdb->get_results('SELECT * FROM wp_gh_contacts WHERE email = "'.$from.'@carrier.com"');

		if (empty($results)) {
			$wpdb->insert('wp_gh_contacts', array(
				'email' => $from.'@carrier.com',
				//'first_name' => 'First Test',
				//'last_name' => 'Last Test',
				'user_id' => 1,
				'owner_id' => 1,
				'optin_status' => 1,
				'date_created' => '2021-08-15 22:42:46',
				'date_optin_status_changed' => '2021-08-15 22:42:46'
			));

			$wpdb->insert('wp_gh_notes', array(
				'object_id' => $wpdb->insert_id,
				'object_type' => 'contact',
				'user_id' => 1,
				'context' => 'sms',
				'content' => $body,
				'timestamp' => '1629066123',
				'date_created' => '2021-08-15 22:42:46'
			));

		} else {
			$wpdb->insert('wp_gh_notes', array(
				'object_id' => $results[0]->ID,
				'object_type' => 'contact',
				'user_id' => 1,
				'context' => 'sms',
				'content' => $body,
				'timestamp' => '1629066123',
				'date_created' => '2021-08-15 22:42:46'
			));
			//$wpdb->update('wp_gh_contacts', array('first_name' => 'abc'), array('ID' => $results[0]->ID));
		}
		
	}
	
}

?>