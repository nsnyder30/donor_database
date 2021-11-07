<?php
/*
	Created by Neil Snyder 
	File Function: User login interface for donor database frontend
*/

/*----------------------------------------------------DECLARE VARIABLES---------------------------------------------------*/
$login_fail = '';
/*------------------------------------------------------------------------------------------------------------------------*/


/*-----------------------------------LOAD PAGE INIT, LOGOUT CHECK, QUERY EXISTING USERS-----------------------------------*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
if(isset($_GET['logout'])){session_destroy();}
$ds_spl = new dataSource('db_user');
$user_list = array();
$query = "SELECT usr_username 
			FROM db_users";
$ds_spl->execQuery($query);
while($ds_spl-> result != FALSE && $data = $ds_spl->fetchArray())
{
	$user_list[] = $data['usr_username'];
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------GET SESSION PERMISSIONS-------------------------------------------------*/
$_SESSION['permissions'] = array();
if(count(array_intersect_key($_POST, array_flip(array('submitted', 'username', 'password')))) == 3)
{
	$user = preg_replace($input_clean_regex, '', $_POST['username']);
	$password = md5($_POST['password']);
	$query = "SELECT usr_permissions 
				FROM db_users
			   WHERE usr_username = :username 
			     AND usr_password = :password";
	$params = array(array('query_key' => 0, 
						  'params' => array('username' => $user, 
											'password' => $password)));
	$ds_spl->execQuery(array($query), TRUE, $params);
	while($ds_spl->result != FALSE && $data = $ds_spl->fetchArray())
	{
		$_SESSION['ddb_user'] = $user;
		$_SESSION['permissions'] = array_merge($_SESSION['permissions'], explode(',', $data['usr_permissions']));
	}
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*-------------------------------------ON SUCCESSFUL LOGIN, REDIRECT TO ORIGINAL PAGE-------------------------------------*/
if(isset($_SESSION['ddb_user']) && count($_SESSION['permissions']) > 0)
{
	if(isset($_SESSION['url_return']))
		{header('Location: '.$_SESSION['url_return']);} 
	else 
		{header('Location: /donor_database/pages/home.php');}		
	exit();	
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*----------------------------------------------CHECK FOR FAILED LOGIN ATTMPT---------------------------------------------*/
if(isset($_POST['submitted']))
{	
	if(count($_SESSION['permissions']) == 0)
	{
		$access_success = 'N';
		$login_fail = '<span class="text-danger">Invalid username or password</span>';
	} 
	else 
		{$access_success = 'Y';}
	
	if(isset($_POST['username']))
		{$user = $_POST['username'];}
	else
		{$user = '';}
	
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strlen($_SERVER['HTTP_X_FORWARDED_FOR']) > 0)
	{
		$proxy_ip = $_SERVER['REMOTE_ADDR'];
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		$proxy_ip = '';
	}
	
	$query = "INSERT INTO access_log (acc_ip, acc_proxy_ip, acc_username, acc_success) 
				   VALUES (:ip, :proxy_ip, :user, :success)";
	$params = array(array('query_key' => 0, 'params' => array('ip' => $ip, 'proxy_ip' => $proxy_ip, 'user' => $user, 'success' => $access_success)));
	$ds_spl->execQuery($query, TRUE, $params);
}
/*------------------------------------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>SPL Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		html, body{
			height: 100%;
		}
		.full-height{height: 100%;}
		.full-width{width: 100%;}
		.c-login{
			
		}
		.jumbotron{
			height: 100%;
		}
		.a-bgcolor--red{background-color: #F00;}
		.a-bgcolor--yellow{background-color: #FF0;}
		.a-bgcolor--green{background-color: #0F0;}
		html{font-size: 1rem;}
		@include media-breakpoint-up(sm){font-size: 1.2rem}
		@include media-breakpoint-up(md){font-size: 1.4rem}
		@include media-breakpoint-up(lg){font-size: 1.6rem}
	</style>
</head>
<body data-ng-app="app_login" data-ng-controller="ctl_login">	
	<div class="full-height full-width container">
		<div class="row d-flex align-content-center h-75">
			<div class="col-sm">
			</div>
			<form action="/donor_database/utils/login.php" method="POST" class="col-sm-8 c-login border pt-1 pb-4 w-75">
				<div class="w-100 text-center h2">Login</div>
				<div class="row">
					<div class="col-sm-12 p-1 text-center font-weight-bold">
						<?php echo $login_fail; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4 p-1 text-right font-weight-bold">
						Username:
					</div>
					<div class="col-sm-8 p-0 justify-content-left">
						<input type="text" name="username" autofocus>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4 p-1 text-right font-weight-bold">
						Password:
					</div>
					<div class="col-sm-8 p-0 justify-content-left">
						<input type="password" name="password">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4 p-1 text-right font-weight-bold">
					</div>
					<div class="col-sm-8 p-0 justify-content-left">
						<input type="submit" name="submitted" value="Log In">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 p-0 text-center justify-content-center">
						<a href="/donor_database/utils/create_account.php">Create Account</a>
					</div>
				</div>
			</form>
			<div class="col-sm">
			</div>
		</div>
	</div>
</body>
</html>
<?php $ds_spl->closeDataSource(); ?>