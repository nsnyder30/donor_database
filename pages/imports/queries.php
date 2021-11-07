<?php
/*
	Created by Neil Snyder 
	File Function: Database interface for file uploads front-end UI
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');

/*---------------------------------------------------------------------------------------------------------------------*/
// Parse input data to $_POST variable
// Initialize output variable ($results)
$_POST = !isset($_POST['action']) ? json_decode(file_get_contents('php://input'), true) : $_POST;
$results = array('post' => $_POST);
/*---------------------------------------------------------------------------------------------------------------------*/

if(isset($_POST['action']))
{
	$conn = new dataSource('db_user');
	switch($_POST['action'])
	{
		/*---------------------------------------------DEFINE READ OPERATIONS---------------------------------------------*/
		case 'load_import_data':
			$results['import_data'] = array();
			$query = "SELECT imp_id, imp_ts, imp_filename FROM imports";
			$conn->execQuery($query);
			while($conn->result != FALSE && $data = $conn->fetchArray())
			{
				$results['import_data'][] = $data;
			}
			break;
		/*----------------------------------------------------------------------------------------------------------------*/


		/*--------------------------------------------DEFINE DELETE OPERATIONS--------------------------------------------*/
		case 'delete':
			$queries = array();
			$params = array();
			$queries[] = "DELETE donation_history.* FROM donation_history WHERE don_imp_id = :imp_id";
			$queries[] = "DELETE imports.* FROM imports WHERE imp_id = :imp_id";
			$params[] = array('query_key' => 0, 'params' => array('imp_id' => $_POST['imp_id']));
			$params[] = array('query_key' => 1, 'params' => array('imp_id' => $_POST['imp_id']));
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