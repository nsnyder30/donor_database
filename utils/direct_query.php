<?php
/*
	Created by Neil Snyder 
	File Function: Simple front-end for uploading files to donor database
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<html>
<head>
	<title>Query Tool</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
</head>
<body data-ng-app="queries_app" data-ng-controller="queries_ctl" >
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>
	
	<form action="direct_query.php" method="POST">
		<table title="Tip: Once you've typed your query, hit 'TAB' then 'ENTER' on your keyboard to execute the query rather than clicking the 'Submit' button with your mouse">
			<tr>
				<td class="text-right align-top font-weight-bold">Query:</td>
				<td>
					<textarea name="query" rows="10" cols="200" class="p-0" wrap="physical"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-right"><input type="submit"></td>
			</tr>
		</table>
	</form>
	<?php
	if(isset($_POST['query']))
	{
		echo '<b>Your query: </b>'.$_POST['query'].'<br><br>';
		$query_result = array();
		$conn = new dataSource('db_user');
		$conn->execQuery($_POST['query']);
		while($conn->result != FALSE && $data = $conn->fetchArray())
		{
			$query_result[] = $data;
		}
		$conn->closeDataSource();
		if(count($query_result) > 0)
		{
			echo '<b>Your Query Results:</b><br>';
			testprint_table($query_result);
		} 
		else 
			{echo '<b>Your query returned no results</b>';}
	}
	
	?>
	<!------------------------------------------INCLUDE JAVASCRIPT DEPENDENCIES-------------------------------------------->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.js"></script>
	<script src="/donor_database/lib_js/ui-bootstrap-tpls-2.5.0.min.js?motime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/ui-bootstrap-tpls-2.min.js'); ?>"></script>
	<!-- <script src="queries.js"></script> -->
	<!--------------------------------------------------------------------------------------------------------------------->
</body>
</html>