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
//namespace PortlightGroundhogg;

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
				'user_id' => 1,
				'owner_id' => 1,
				'optin_status' => 1,
				'date_created' => '2021-08-15 22:42:46',
				'date_optin_status_changed' => '2021-08-15 22:42:46'
			));

			$contact_id = $wpdb->insert_id;

			$wpdb->insert('wp_gh_notes', array(
				'object_id' => $contact_id,
				'object_type' => 'contact',
				'user_id' => 1,
				'context' => 'sms',
				'content' => $body,
				'timestamp' => '1629066123',
				'date_created' => '2021-08-15 22:42:46'
			));

			$wpdb->insert('wp_gh_contactmeta', array(
				'contact_id' => $contact_id,
				'meta_key' => 'primary_phone',
				'meta_value' => $from
			));

			$results = $wpdb->get_results('SELECT * FROM wp_gh_tags WHERE tag_slug = "mobilecontact"');
			if (empty($results)) {
				$wpdb->insert('wp_gh_tags', array(
					'tag_slug' => 'mobilecontact',
					'tag_name' => 'mobilecontact',
					'contact_count' => 1
				));
				$mobile_contact_tag_id = $wpdb->insert_id;
			} else {
				$mobile_contact_tag_id = $results[0]->tag_id;
				$wpdb->update('wp_gh_tags', array('contact_count' => ($results[0]->contact_count+1)), array('tag_id' => $mobile_contact_tag_id));
			}

			$wpdb->insert('wp_gh_tag_relationships', array(
				'tag_id' => $mobile_contact_tag_id,
				'contact_id' => $contact_id
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
		}
		
	}
	
}

wp_register_script( 'myscript', plugins_url( 'myscript.js', __FILE__ ) );
wp_register_style( 'mystyle', plugins_url( 'mystyle.css', __FILE__ ) );

add_action('loadmyscripthereplz', 'somefunction');

function somefunction(){
   wp_enqueue_script('myscript');
   wp_enqueue_style ( 'mystyle' );
}

do_action('loadmyscripthereplz'); 

?>