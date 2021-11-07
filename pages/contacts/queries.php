<?php
/*
	Created by Neil Snyder 
	File Function: Database interface for contacts front-end UI
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');

/*------------------------------------------------------------------------------------------------------------------------*/
// Parse input data to $_POST variable
// Initialize output variable ($results)
$_POST = !isset($_POST['action']) ? json_decode(file_get_contents('php://input'), true) : $_POST;
$results = array('post' => $_POST);
/*------------------------------------------------------------------------------------------------------------------------*/

if(isset($_POST['action']))
{
	$conn = new dataSource('db_user');
	switch($_POST['action'])
	{
		/*---------------------------------------------DEFINE READ OPERATIONS---------------------------------------------*/
		case 'load_contacts_data':
			$results['contacts_data'] = array();
			$query = "SELECT cnt_id, cnt_first, cnt_last, cnt_email, cnt_fb_profile, cnt_phone, cnt_street_addr, cnt_city, cnt_state, cnt_zip, 
							 cnt_country, cnt_mailchimp, cnt_org, org_name AS cnt_org_text, cnt_guest_blogger, cnt_volunteer, cnt_religion, cnt_type, 
							 cnt_personal_details 
						FROM contacts 
			 LEFT OUTER JOIN organizations ON org_id = cnt_org AND org_deleted = cnt_deleted 
					   WHERE cnt_deleted <> 'Y'";
			$query = "SELECT cnt_id, cnt_first, cnt_last, cnt_email, cnt_fb_profile, cnt_phone, cnt_street_addr, cnt_city, cnt_state, cnt_zip, 
							 cnt_country, cnt_mailchimp, cnt_org, org_name AS cnt_org_text, cnt_personal_details 
						FROM contacts 
			 LEFT OUTER JOIN organizations ON org_id = cnt_org AND org_deleted = cnt_deleted 
					   WHERE cnt_deleted <> 'Y'";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['contacts_data'][] = $data;
			}

			$query = "SELECT org_id, org_name AS org_text
						FROM organizations 
					   WHERE org_deleted <> 'Y'";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['org_sources'][] = $data;
			}

			$results['donations_data'] = array();
			$query = "SELECT don_id, don_date, don_amount, don_thankyou, don_source_type, don_source_id, don_notes, don_deleted, 
							 CASE WHEN don_source_type = 'Contact' THEN CONCAT(cnt_first, ' ', cnt_last) WHEN don_source_type = 'Organization' THEN org_name ELSE '' END AS don_source_text 
						FROM donation_history 
			 LEFT OUTER JOIN organizations ON org_id = don_source_id 
			 LEFT OUTER JOIN contacts ON cnt_id = don_source_id";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['donations_data'][] = $data;
			}
			break;
		/*----------------------------------------------------------------------------------------------------------------*/


		/*----------------------------------------DEFINE WRITE & UPDATE OPERATIONS----------------------------------------*/
		case 'insert':
		case 'edit':
			$query = "INSERT INTO contacts (cnt_id, cnt_first, cnt_last, cnt_email, cnt_fb_profile, cnt_phone, cnt_street_addr, 
											cnt_city, cnt_state, cnt_zip, cnt_country, cnt_mailchimp, cnt_org, cnt_personal_details)
						   VALUES (:cnt_id, :cnt_first, :cnt_last, :cnt_email, :cnt_fb_profile, :cnt_phone, :cnt_street_addr, 
								   :cnt_city, :cnt_state, :cnt_zip, :cnt_country, :cnt_mailchimp, :cnt_org, :cnt_personal_details)
		  ON DUPLICATE KEY UPDATE cnt_first = :upd_first, cnt_last = :upd_last, cnt_email = :upd_email, cnt_fb_profile = :upd_fb_profile, 
								  cnt_phone = :upd_phone, cnt_street_addr = :upd_street_addr, cnt_city = :upd_city, cnt_state = :upd_state, 
								  cnt_zip = :upd_zip, cnt_country = :upd_country, cnt_mailchimp = :upd_mailchimp, cnt_org = :upd_org, 
								  cnt_personal_details = :upd_personal_details";
			$params = array(array('query_key' => 0, 'params' => array('cnt_id' => $_POST['cnt_id'], 
																	  'cnt_first' => $_POST['cnt_first'], 
																	  'cnt_last' => $_POST['cnt_last'], 
																	  'cnt_email' => $_POST['cnt_email'], 
																	  'cnt_fb_profile' => $_POST['cnt_fb_profile'], 
																	  'cnt_phone' => $_POST['cnt_phone'], 
																	  'cnt_street_addr' => $_POST['cnt_street_addr'], 
																	  'cnt_city' => $_POST['cnt_city'], 
																	  'cnt_state' => $_POST['cnt_state'], 
																	  'cnt_zip' => $_POST['cnt_zip'], 
																	  'cnt_country' => $_POST['cnt_country'], 
																	  'cnt_mailchimp' => $_POST['cnt_mailchimp'], 
																	  'cnt_org' => $_POST['cnt_org'], 
																	  'cnt_personal_details' => $_POST['cnt_personal_details'], 
																	  'upd_first' => $_POST['cnt_first'], 
																	  'upd_last' => $_POST['cnt_last'], 
																	  'upd_email' => $_POST['cnt_email'], 
																	  'upd_fb_profile' => $_POST['cnt_fb_profile'], 
																	  'upd_phone' => $_POST['cnt_phone'], 
																	  'upd_street_addr' => $_POST['cnt_street_addr'], 
																	  'upd_city' => $_POST['cnt_city'], 
																	  'upd_state' => $_POST['cnt_state'], 
																	  'upd_zip' => $_POST['cnt_zip'], 
																	  'upd_country' => $_POST['cnt_country'], 
																	  'upd_mailchimp' => $_POST['cnt_mailchimp'], 
																	  'upd_org' => $_POST['cnt_org'], 
																	  'upd_personal_details' => $_POST['cnt_personal_details'])));
			$conn->execQuery($query, TRUE, $params);
			if($conn->result != FALSE)
				{$results['edit_result'] = 'success';}
			$results['query'] = $query;
			$results['params'] = $params;
			break;
		/*----------------------------------------------------------------------------------------------------------------*/


		/*--------------------------------------------DEFINE DELETE OPERATIONS--------------------------------------------*/
		case 'delete':
			$queries = array();
			$params = array();
			$queries[] = "DELETE FROM contacts WHERE cnt_id = :cnt_id";
			$params[] = array('query_key' => 0, 'params' => array('cnt_id' => $_POST['cnt_id']));
			$results['queries'] = $queries;
			$results['params'] = $params;
			$conn->execQuery($queries, TRUE, $params);
			break;
		/*----------------------------------------------------------------------------------------------------------------*/
	}
	$conn->closeDataSource();
}
echo json_encode($results);
?>