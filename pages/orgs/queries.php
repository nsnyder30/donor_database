<?php
/*
	Created by Neil Snyder 
	File Function: Database interface for organizations front-end UI
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
		case 'load_org_data':
			$results['org_data'] = array();
			$query = "SELECT org_id, org_name, org_prim_contact, CONCAT(cnt_first, ' ', cnt_last) AS org_prim_contact_text, 
							 org_website, org_address 
						FROM organizations 
			 LEFT OUTER JOIN contacts ON cnt_id = org_prim_contact 
					   WHERE org_deleted <> 'Y'";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['org_data'][] = $data;
			}

			$query = "SELECT cnt_id, CONCAT(cnt_first, ' ', cnt_last) AS cnt_text 
						FROM contacts";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['cnt_sources'][] = $data;
			}
			break;
		/*----------------------------------------------------------------------------------------------------------------*/


		/*----------------------------------------DEFINE WRITE & UPDATE OPERATIONS----------------------------------------*/
		case 'insert':
		case 'edit':
			$query = "INSERT INTO organizations (org_id, org_name, org_prim_contact, org_website, org_address) 
						   VALUES (:org_id, :org_name, :org_prim_contact, :org_website, :org_address) 
		  ON DUPLICATE KEY UPDATE org_name = :upd_name, org_prim_contact = :upd_prim_contact, 
								  org_website = :upd_website, org_address = :upd_address";
			$params = array(array('query_key' => 0, 'params' => array('org_id' => $_POST['org_id'], 
																	  'org_name' => $_POST['org_name'], 
																	  'org_prim_contact' => $_POST['org_prim_contact'], 
																	  'org_website' => $_POST['org_website'], 
																	  'org_address' => $_POST['org_address'], 
																	  'upd_name' => $_POST['org_name'], 
																	  'upd_prim_contact' => $_POST['org_prim_contact'], 
																	  'upd_website' => $_POST['org_website'], 
																	  'upd_address' => $_POST['org_address'])));
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
			$queries[] = "DELETE FROM organizationS WHERE org_id = :org_id";
			$params[] = array('query_key' => 0, 'params' => array('org_id' => $_POST['org_id']));
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