<?php
/*
	Created by Neil Snyder 
	File Function: Default homepage
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Donor DB Home</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="/donor_database/lib_js/standard_functions.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/standard_functions.js'); ?>"></script>
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
	</style>
</head>
<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>
</body>
</html>