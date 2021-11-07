<?php
/*
	Created by Neil Snyder 
	File Function: Database interface for donations front-end UI
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
		case 'load_donations_data':
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

			$query = "SELECT cnt_id, CONCAT(cnt_first, ' ', cnt_last) AS cnt_text 
						FROM contacts";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['cnt_sources'][] = $data;
			}

			$query = "SELECT org_id, org_name AS org_text
						FROM organizations";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['org_sources'][] = $data;
			}
			break;
		/*----------------------------------------------------------------------------------------------------------------*/


		/*----------------------------------------DEFINE WRITE & UPDATE OPERATIONS----------------------------------------*/
		case 'insert':
		case 'edit':
			$query = "INSERT INTO donation_history (don_id, don_date, don_amount, don_thankyou, don_source_type, don_source_id) 
						   VALUES (:don_id, :don_date, :don_amount, :don_thankyou, :don_source_type, :don_source_id) 
		  ON DUPLICATE KEY UPDATE don_date = :upd_date, don_amount = :upd_amount, don_thankyou = :upd_thankyou, don_source_type = :upd_source_type, don_source_id = :upd_source_id";
			$params = array(array('query_key' => 0, 'params' => array('don_id' => $_POST['don_id'], 
																	  'don_date' => $_POST['don_date'] == '' ? null : $_POST['don_date'], 
																	  'don_amount' => $_POST['don_amount'], 
																	  'don_thankyou' => $_POST['don_thankyou'] == '' ? null : $_POST['don_thankyou'], 
																	  'don_source_type' => $_POST['don_source_type'], 
																	  'don_source_id' => $_POST['don_source_id'], 
																	  'upd_date' => $_POST['don_date'] == '' ? null : $_POST['don_date'], 
																	  'upd_amount' => $_POST['don_amount'], 
																	  'upd_thankyou' => $_POST['don_thankyou'] == '' ? null : $_POST['don_thankyou'], 
																	  'upd_source_type' => $_POST['don_source_type'], 
																	  'upd_source_id' => $_POST['don_source_id'])));
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
			$queries[] = "DELETE FROM donation_history WHERE don_id = :don_id";
			$params[] = array('query_key' => 0, 'params' => array('don_id' => $_POST['don_id']));
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