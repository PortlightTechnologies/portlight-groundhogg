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

add_action('admin_head', 'webroom_add_css_js_to_admin');

function webroom_add_css_js_to_admin() {
  echo '<style>
    .contact-info-cards{
        flex: 0 0 40% !important;
    }
  </style>';
    echo '<script>

        jQuery(document).ready(function( $ ){
            $("#send-email").click(function(){
                var email_content = $("#email_content_ifr").contents().find("#tinymce").html().replace(/<[^>]*>?/gm, '');
                $("#add-new-note").val(email_content);
                $("#add-note").click();
                });
                $("#sms").find(':submit').click(function(){
                $("#add-new-note").val($("#sms-message").val());
                $("#add-note").click();
            });

            let notesDiv = document.getElementsByClassName('postbox info-card notes  ')[0];
            let btn = document.createElement("button");
            btn.innerHTML = "Print";
            btn.onclick = function () {
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head><title>Notes</title>');
                mywindow.document.write('</head><body >');
                var notes = document.getElementsByClassName('display-notes gh-notes-container');
                for (var i=0, max=notes.length; i < max; i++) {
                mywindow.document.write(notes[i].innerHTML);
                }
                mywindow.document.write('</body></html>');
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                return true;
            };
            notesDiv.appendChild(btn);
            let btnClear = document.createElement("button");
            btnClear.innerHTML = "Clear";
            btnClear.onclick = function () {
                var deleteButton = document.getElementsByClassName('delete-note delete danger');
                for (var i=0, max=deleteButton.length; i < max; i++) {
                    deleteButton[i].getElementsByTagName('a')[0].click();
                }
            };
            notesDiv.appendChild(btnClear);

        });
    

    </script>';
}

?>