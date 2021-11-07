<?php
/*
	Created by Neil Snyder 
	File Function: Simple front-end for uploading files to donor database
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<html>
<head>
	<title>Imports</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
</head>
<body data-ng-app="imports_app" data-ng-controller="imports_ctl" >
<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>

	<!-----------------------------------------------WRITE FILE-UPLOAD FORM------------------------------------------------>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="file" name="file_import[]" id="file_import" multiple="multiple"><br>
		<input type="submit">
	</form>
	<!--------------------------------------------------------------------------------------------------------------------->

	<?php
	/*------------------------------IF FILE UPLOADS HAVE BEEN SUBMITTED, BEGIN UPLOAD PROCESS-----------------------------*/
	if(isset($_FILES['file_import']))
	{
		$ds = new dataSource('db_user');

		/*----------------------------------------------DEFINE INPUT QUERIES----------------------------------------------*/
		$imp_queries[0] = "INSERT INTO imports (imp_ts, imp_filename) 
								VALUES (:imp_ts, :imp_filename)";
		$imp_queries[1] = "SELECT imp_id 
							 FROM imports 
							WHERE imp_ts = :imp_ts AND imp_filename = :imp_filename 
						 ORDER BY imp_id DESC 
							LIMIT 1";
		$imp_queries[2] = "UPDATE imports SET imp_source = :imp_source WHERE imp_id = :imp_id";
		$cnt_queries[0] = "INSERT INTO contacts (cnt_first, cnt_last, cnt_email) 
								VALUES (:cnt_first, :cnt_last, :cnt_email) 
			   ON DUPLICATE KEY UPDATE cnt_first = :upd_first";
		$cnt_queries[1] = "SELECT cnt_id, cnt_first, cnt_last 
							 FROM contacts";
		$don_queries[0] = "INSERT INTO donation_history (don_imp_id, don_date, don_amount, don_source_type, don_source_id) 
								VALUES (:don_imp_id, :don_date, :don_amount, :don_source_type, :don_source_id)";
		/*----------------------------------------------------------------------------------------------------------------*/
		
		$uploaded_files = array();
		$excluded_donations = array();

		/*-------------------------------INSERT OVER EACH UPLOADED FILE, PARSE, AND UPLOAD--------------------------------*/
		foreach($_FILES['file_import']['name'] as $key => $name)
		{
			if(strtolower(substr($name, strlen($name)-3, 3))=='csv')
			{
				/*-------------------WRITE RECORD OF UPLOAD TO IMPORTS TABLE, THE QUERY IMPORT RECORD ID------------------*/
				$uploaded_files[] = $name;
				$imp_time = date('y-m-d H:i:s');
				$imp_params = array(array('query_key' => 0, 'params' => array('imp_ts' => $imp_time, 'imp_filename' => $name)));
				$ds->execQuery($imp_queries, TRUE, $imp_params);
				$imp_params[0]['query_key'] = 1;
				$ds->execQuery($imp_queries, TRUE, $imp_params);
				while($ds->result != FALSE && $data = $ds->fetchArray())
				{
					$imp_id = $data['imp_id'];
				}
				/*--------------------------------------------------------------------------------------------------------*/

				/*-----------------------PARSE UPLOADED CSV DATA, ASCERTAIN SOURCE BY COLUMN HEADERS----------------------*/
				$csv = new csv_interface($_FILES['file_import']['tmp_name'][$key]);
				$csv_data = $csv->fetch_data();
				$key_map = array('paypal' => array('TimeZone','Transaction ID','Shipping Address','Reference Txn ID', 'Balance Impact'));
				$csv_sample = current($csv_data);
				foreach($key_map as $format => $keys)
				{
					if(count(array_intersect_key($csv_sample, array_flip($keys))) == count($keys))
						{$filetype = $format;}
				}
				if(!isset($filetype))
					{$filetype = 'facebook';}

				$imp_params = array(array('query_key' => 2, 'params' => array('imp_id' => $imp_id, 'imp_source' => $filetype)));
				$ds->execQuery($imp_queries, TRUE, $imp_params);
				/*--------------------------------------------------------------------------------------------------------*/


				/*-------------------IF SOURCE IS FROM PAYPAL, FILTER DATA ON TYPE = "Donation Payment"-------------------*/
				switch($filetype)
				{
					case 'paypal': 
						$csv_data = array_values(array_intersect_key($csv_data, array_intersect(array_column($csv_data, 'Type'), array('Donation Payment','Subscription Payment')))); 
						break;
					default:
				}
				/*--------------------------------------------------------------------------------------------------------*/

				/*------------------------------------------ITERATE OVER CSV DATA-----------------------------------------*/
				foreach($csv_data as $key => $data)
				{
					/*--------------------------------MAP FIELDS TO PHP VARIABLES------------------------------*/
					switch($filetype)
					{
						case 'paypal':
							$names = explode(' ', $data['Name']);
							$first = isset($names[0]) ? ucwords($names[0]) : '';
							$last = count($names) > 0 ? ucwords(implode(' ', array_splice($names, 1))) : '';
							list($don_date, $don_amount, $cnt_email) = array_values(array_intersect_key($data, array_flip(array('Date', 'Gross', 'From Email Address'))));
							$cnt_first = $first;
							$cnt_last = $last;
							break;
						case 'venmo':
							break;
						case 'facebook':
							list($don_amount, $don_date, $cnt_first, $cnt_last, $cnt_email) = array_values(array_intersect_key($data, array_flip(array('Donation Amount', 'Charge Date', 'First Name', 'Last Name', 'Email Address'))));
							break;
						default:
							list($don_amount, $don_date, $cnt_first, $cnt_last, $cnt_email) = array_values(array_intersect_key($data, array_flip(array('Donation Amount', 'Charge Date', 'First Name', 'Last Name', 'Email Address'))));
					}
					/*-----------------------------------------------------------------------------------------*/


					/*-----------------------------------------------------------------------------------------*/
					// Define default values for each field
					// Perform datatype conversions and format names as titlecase
					$don_date = strtotime($don_date) > strtotime('-') ? date('Y-m-d', strtotime($don_date)) : date('Y-m-d', strtotime('2018-01-01'));
					$cnt_email = $cnt_email == '-' ? null : $cnt_email;
					$cnt_first = ucwords($cnt_first);
					$cnt_last = ucwords($cnt_last);
					$don_amount = floatval(preg_replace('/[^0-9\.]/', '', $don_amount));
					/*-----------------------------------------------------------------------------------------*/


					/*-----------------------------------------------------------------------------------------*/
					// Filter out records without First name, last name, or valid amounts
					// Build input queries
					if($don_amount > 0 && strlen($cnt_first) > 1 && strlen($cnt_last) > 1 ) {
						$don_params[] = array('query_key' => 0, 'params' => array('don_imp_id' => $imp_id,
																			 'don_date' => $don_date, 
																			 'don_amount' => $don_amount, 
																			 'don_source_type' => 'Contact', 
																			 'don_source_id' => null), 
																'cnt_first' => $cnt_first, 
																'cnt_last' => $cnt_last);																
						$cnt_params[] = array('query_key' => 0, 'params' => array('cnt_first' => $cnt_first, 
																				  'cnt_last' => $cnt_last, 
																				  'cnt_email' => $cnt_email, 
																				  'upd_first' => $cnt_first));
					} else {
						/*--------------------------------TRACK FILTERED RECORDS-------------------------------*/
						$excluded_donations[] = array('don_imp_id' => $imp_id, 
													  'don_date' => $don_date, 
													  'don_amount' => $don_amount, 
													  'don_source_type' => 'contact', 
													  'don_source_id' => null);
						/*-------------------------------------------------------------------------------------*/
					}
					/*-----------------------------------------------------------------------------------------*/
				}
				/*--------------------------------------------------------------------------------------------------------*/

				/*------------------------------UPLOAD NEW CONTACTS, THEN READ CONTACTS DATA------------------------------*/
				$cnt_params[] = array('query_key' => 1, 'params' => array());
				$ds->execQuery($cnt_queries, TRUE, $cnt_params);
				$cnt_data = array();
				while($ds->result != FALSE && $data = $ds->fetchArray())
				{
					$cnt_data[] = $data;
				}
				/*--------------------------------------------------------------------------------------------------------*/


				/*--------------------------------------------------------------------------------------------------------*/
				// Iterate over input donations data and map to existing contacts
				// Make note of donations with no matching contact
				foreach($don_params as $pkey => $parray)
				{					
					$cnt_matches = array_values(array_intersect_key($cnt_data, 
																	array_intersect(array_column($cnt_data, 'cnt_first'), array($parray['cnt_first'])), 
																	array_intersect(array_column($cnt_data, 'cnt_last'), array($parray['cnt_last']))));
					if(count($cnt_matches) > 0) 
						{$don_params[$pkey]['params']['don_source_id'] = current($cnt_matches)['cnt_id'];}
					else {
						$tmp = array('Charge Date' => $parray['params']['don_date'], 'Amount' => $parray['params']['don_amount'], 'First Name' => $parray['cnt_first'], 'Last Name' => $parray['cnt_last']);
						$excluded_donations[] = $tmp;
						unset($don_params[$pkey]);
					}
				}
				/*--------------------------------------------------------------------------------------------------------*/
				

				/*-------------------------DISPLAY LIST OF DONATION RECORDS THAT FAILED TO UPLOAD-------------------------*/
				if(count($excluded_donations) > 0)
				{
					echo '<span class="a-color--red a-font--bold">The Following Transactions were not uploaded. Could not match first & last names to database records.</span><br>';
					testprint_table($excluded_donations);
				}
				$ds->execQuery($don_queries, TRUE, $don_params);
				/*--------------------------------------------------------------------------------------------------------*/
			}
		}
		$ds->closeDataSource();

		/*-----------------------------------------SHOW RECORDS OF UPLOADED FILES-----------------------------------------*/
		if(count($uploaded_files) > 0)
		{
			echo '<span class="a-font--bold a-font--underline">Uploaded Files:</span><br>';
			foreach($uploaded_files as $file)
			{
				echo $file.'<br>';
			}
			echo '<br>';
		}
		/*----------------------------------------------------------------------------------------------------------------*/
	}
	/*--------------------------------------------------------------------------------------------------------------------*/
?>

	<!------------------------------------------------SHOW LIST OF IMPORTS------------------------------------------------->
	<div class="container-fluid p-0">
		<div class="container-fluid sticky-top bg-light m-1 p-0">
		</div>
		
		<div class="m-1"> 
			<table class="table table-fixed m-2">
				<caption class="table-title thead-dark">Imports</caption>
				<thead>
					<tr class="thead-light">
						<th class="compress_cell text-nowrap text-center">Filename</th>
						<th class="compress_cell text-nowrap text-center">Date/time</th>
						<th class="compress_cell text-nowrap text-center">Delete</th>
					</tr>
				</thead>
				<tbody>
					<tr data-ng-repeat="imp in import_data track by imp.imp_id">
						<td class="text-nowrap text-center">{{imp.imp_filename}}</td>
						<td class="text-nowrap text-center">{{imp.imp_ts}}</td>
						<td class="text-nowrap text-center"><img src="/donor_database/images/delete_x.png" width="16" class="a-clickable" data-ng-click="delete_imp(imp.imp_id)"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!--------------------------------------------------------------------------------------------------------------------->


	<!------------------------------------------INCLUDE JAVASCRIPT DEPENDENCIES-------------------------------------------->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.js"></script>
	<script src="/donor_database/lib_js/ui-bootstrap-tpls-2.5.0.min.js?motime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/ui-bootstrap-tpls-2.min.js'); ?>"></script>
	<script src="imports.js"></script>
	<!--------------------------------------------------------------------------------------------------------------------->
</body>
</html>