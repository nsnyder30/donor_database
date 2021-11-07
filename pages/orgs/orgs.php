<?php 
/*
	Created by Neil Snyder 
	File Function: Simple front-end for managing organizations in donor database
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<html>
<head>
	<title>Organizations</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/donor_database/lib_css/bootstrap/bootstrap.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/bootstrap/bootstrap.css'); ?>">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>

	<div data-ng-app="org_app" data-ng-controller="org_ctl">
		<!---------------------------DEFINE MODAL POP-UP FOR EDITING AND INSERTING RECORDS--------------------------------->
		<script type="text/ng-template" id="myModalContent.html">
			<div id="modal_body" class="modal-body align-middle">
				<div data-ng-form="inp_frm">
					<table class="mb-3">
						<tr>
							<th class="text-right">Name:</th>
							<td><input class="js-tabindex" type="text" data-ng-model="org_name" autofocus tabindex="1"></td>
							<th class="text-right">Website:</th>
							<td><input class="js-tabindex" type="text" data-ng-model="org_website" tabindex="3"></td>
						</tr>
						<tr>
							<th class="text-right text-nowrap">Primary Contact:</th>
							<td><select data-ng-model="org_prim_contact" data-ng-options="cnt.cnt_id as cnt.cnt_text for cnt in cnt_sources" tabindex="2"></td>
							<th class="text-right">Address:</th>
							<td><input class="js-tabindex" type="text" data-ng-model="org_address" tabindex="4"></td>
						</tr>
					</table>
					<div ng-messages="inp_frm.$error" class="text-danger">
					</div>
				</div>
				<button class="btn btn-primary" data-ng-click="ok()" tabindex="6">OK</button>
				<button class="btn btn-secondary" data-ng-click="cancel()" tabindex="7">Cancel</button>
			</div>
		</script>
		<!----------------------------------------------------------------------------------------------------------------->
	

		<!--------------------------------------------WRITE TABULAR DATA--------------------------------------------------->
		<button class="a-font--small mt-2 ml-2" data-ng-click="open('insert','org')">Add New Organization</button>
		<table class="table table-fixed m-2">
			<caption class="table-title thead-dark">Organizations</caption>
			<thead>
				<tr class="thead-light">
					<th class="text-nowrap text-center a-clickable" data-ng-click="query('load_org_data')" colspan="10">Filters</th>
				</tr>
				<tr>
					<td></td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="org_name_null_flt" tabindex="6"> Nulls Only</td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="org_prim_contact_null_flt" tabindex="8"> Nulls Only</td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="org_website_null_flt" tabindex="8"> Nulls Only</td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="org_address_null_flt" tabindex="8"> Nulls Only</td>
				</tr>
				<tr>
					<td></td>
					<td><input class="js-tabindex" type="text" data-ng-model="org_name_flt" tabindex="7"></td>
					<td><input class="js-tabindex" type="text" data-ng-model="org_prim_contact_text_flt" tabindex="7"></td>
					<td><input class="js-tabindex" type="text" data-ng-model="org_website_flt" tabindex="7"></td>
					<td><input class="js-tabindex" type="text" data-ng-model="org_address_flt" tabindex="7"></td>
				</tr>
				<tr class="thead-light">
					<th class="compress_cell text-nowrap text-center">Delete</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('org_sort','org_name')">Name</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('org_sort','org_prim_contact_text')">Primary Contact</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('org_sort','org_website')">Website</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('org_sort','org_address')">Address</th>
				</tr>
			</thead>
			<tbody>
				<tr class="hvr-bg-lightyellow" data-ng-repeat="org in org_data | orderBy: org_sort['col'] : org_sort[org_sort['col']] | filter: org_filters()" data-ng-dblclick="open('edit','org',{org_id:org.org_id})">
					<td class="text-center border border-gray border-thin"><button type="button" class="c-table__btn c-table__btn--small" data-ng-click="query('delete',{org_id:org.org_id})"><img src="/donor_database/images/delete_x.png" height="16px"></img></button></td>
					<td class="compress_cell text-nowrap text-center">{{org.org_name}}</td>
					<td class="compress_cell text-nowrap text-center">{{org.org_prim_contact_text}}</td>
					<td class="compress_cell text-nowrap text-center">{{org.org_website}}</td>
					<td class="compress_cell text-nowrap text-center">{{org.org_address}}</td>
				</tr>
			</tbody>
		</table>
		<!----------------------------------------------------------------------------------------------------------------->
	</div>


	<!----------------------------------------INCLUDE JAVASCRIPT DEPENDENCIES---------------------------------------------->
	<script src="/donor_database/lib_js/jquery-3.3.1.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/jquery-3.3.1.js'); ?>"></script>
	<script src="/donor_database/lib_js/bootstrap/bootstrap.min.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/bootstrap/bootstrap.min.js'); ?>"></script>
	<script src="/donor_database/lib_js/angular.min.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/angular.min.js'); ?>"></script>
	<script src="/donor_database/lib_js/standard_functions.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/standard_functions.js'); ?>"></script>
	<script src="/donor_database/lib_js/ui-bootstrap-tpls-2.5.0.min.js?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_js/ui-bootstrap-tpls-2.min.js'); ?>"></script>
	<!--------------------------------------------------------------------------------------------------------------------->


	<!----------------------------------------DEFINE ANGULARJS I/O FUNCTIONS----------------------------------------------->
	<script>	
		var uri_vars = window.location.search.substr(1).split("&");
		var $_GET = {};
		for(i in uri_vars)
		{
			$_GET[decodeURIComponent(uri_vars[i].split("=")[0])] = decodeURIComponent(uri_vars[i].split("=")[1]);
		}
		var app = angular.module('org_app', ['ui.bootstrap']);
		/*-----------------------------------CONTROLLER: MODULE TABLE & CONTROL BUTTONS-----------------------------------*/
		app.controller('org_ctl', orgCtl);
		orgCtl.$inject = ['$scope','$http','$uibModal'];
		function orgCtl($scope, $http, $uibModal){
			var org_data = [];
			$scope.source_types = ['Contact','Organization'];
			$scope.source_list = {Contact:[],Organization:[]};
			/*-----------------------------------SCOPE FUNCTION: DYNAMICALLY PULL DATA------------------------------------*/
			$scope.query = function(action, params){
				let headers = {'Content-Type': 'application/x-www-form-urlencoded'};
				let config = {headers: {'Content-Type' : 'application/x-www-form-urlencoded;charset=utf-8;'}};
				params = params || {};
				switch(action){
					case 'load_org_data':
						var obj = {action: action};
						var retdata = $http({
							method: 'POST', 
							url: 'queries.php', 
							data: JSON.stringify(obj), 
							headers: headers
						}).then(function(response) {
								$scope.org_sort = $scope.org_sort || {};
								$scope.org_data = response['data']['org_data'];
								$scope.cnt_sources = response['data']['cnt_sources'];
								$scope.org_filters();
						}, function(response) {

						});
						break;
					case 'add':
					case 'insert':
					case 'edit':
					case 'delete':
						params['action'] = action;
						var retdata = $http({
							method: 'POST', 
							url: 'queries.php', 
							data: JSON.stringify(params), 
							headers: headers
						}).then(function(response){
							$scope.query('load_org_data');
						}, function(response) {

						});
						break;
					default:
				}
			};
			/*------------------------------------------------------------------------------------------------------------*/


			/*--------------------------------FUNCTION: SORT DATA SET BY SPECIFIED COLUMN---------------------------------*/
			$scope.toggle_group_sort = function(arr,col){
				$scope[arr]['col'] = col;
				$scope[arr][col] = !$scope[arr][col];
			}
			/*------------------------------------------------------------------------------------------------------------*/


			/*---------------------------------DEFINE DEFAULT NGMODEL VALUS AND LOAD DATA---------------------------------*/
			$scope.query('load_org_data');
			/*-------------------------------------------------------------------------------------------------------------*/
			

			/*-------------------------------TABLE FILTERS: DEFINE FILTER ELEMENT BEHAVIORS-------------------------------*/
			$scope.org_filters = function(){
				return function(org) {
					let name_flt = $scope.org_name_flt !== null ? new RegExp($scope.org_name_flt, 'gi') : new RegExp('.*', 'g');
					name_flt = $scope.org_name_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : name_flt;

					let prim_contact_text_flt = $scope.org_prim_contact_text_flt !== null ? new RegExp($scope.org_prim_contact_text_flt, 'gi') : new RegExp('.*', 'g');
					prim_contact_text_flt = $scope.org_prim_contact_text_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : prim_contact_text_flt;

					let website_flt = $scope.org_website_flt !== null ? new RegExp($scope.org_website_flt, 'gi') : new RegExp('.*', 'g');
					website_flt = $scope.org_website_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : website_flt;

					let address_flt = $scope.org_address_flt !== null ? new RegExp($scope.org_address_flt, 'gi') : new RegExp('.*', 'g');
					address_flt = $scope.org_address_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : address_flt;


					name_val = org.org_name === null ? '' : org.org_name;
					prim_contact_text_val = org.org_prim_contact_text === null ? '' : org.org_prim_contact_text;
					website_val = org.org_website === null ? '' : org.org_website;
					address_val = org.org_address === null ? '' : org.org_address;

					flt_pass = true;
					flt_pass = flt_pass && name_val.match(name_flt);
					flt_pass = flt_pass && prim_contact_text_val.match(prim_contact_text_flt);
					flt_pass = flt_pass && website_val.match(website_flt);
					flt_pass = flt_pass && address_val.match(address_flt);
					return flt_pass
				};
			};
			/*------------------------------------------------------------------------------------------------------------*/			


			/*-------------------------SCOPE FUNCTION: SHOW MODAL FOR EDITING & INSERTING RECORDS-------------------------*/
			$scope.open = function(inp_mode,inp_entity,inp_params){
				var org_match = [];
				let params = inp_params || {};
				let inp_val = '';				
				switch(inp_mode+'_'+inp_entity)
				{
					case 'insert_org':
						inp_val = {
							org: {org_id: null}, 
							cnt_sources: $scope.cnt_sources, 
						};
						break;
					case 'edit_org': 
						org_match = $scope.org_data.filter(function(d){return d.org_id == params.org_id})[0];
						inp_val = {
							org: org_match, 
							cnt_sources: $scope.cnt_sources, 
						}
						break;
				}
				var modalInstance = $uibModal.open({
					templateUrl: 'myModalContent.html', 
					controller: 'modal_ctl', 
					size: 'lg', 
					windowClass: 'target_modal show align-middle', 
					resolve: {
						inp_params: function() {return {
							inp_mode: inp_mode, 
							inp_entity: inp_entity,
							inp_val: inp_val,
						};}
					}
				})
				
				modalInstance.result.then(function(result){
					let params = {ent_type: ''};
					switch(inp_mode+'_'+inp_entity)
					{
						case 'insert_org': 
						case 'edit_org': 
							params.ent_type = 'organization';
							params.org_id = result.org_id || null;
							params.org_name = result.org_name || '';
							params.org_prim_contact = result.org_prim_contact || null;
							params.org_website = result.org_website || '';
							params.org_address = result.org_address || '';
							break;
					}
					$scope.query(inp_mode, params);
					$('.js-selected').remove();
				}, function() {

				});
			};
			/*------------------------------------------------------------------------------------------------------------*/			
		};		
		/*----------------------------------------------------------------------------------------------------------------*/
		
		
		/*----------------------------CONTROLLER: MODAL POPUP FOR EDITING & INSERTING RECORDS-----------------------------*/
		app.controller('modal_ctl', ModalCtl);
		ModalCtl.$inject = ['$scope','$uibModalInstance','inp_params'];
		function ModalCtl($scope,$uibModalInstance,inp_params)
		{
			$('.js-tabindex').not('.modal-content .js-tabindex').each(function(){
				$(this).attr('data-tabindex', $(this).attr('tabindex'));
				$(this).attr('tabindex', -1);
				$(this).attr('disabled', true);
			});
			let inp_mode = inp_params.inp_mode;
			let inp_entity = inp_params.inp_entity;
			let inp_val = inp_params.inp_val;
			$scope.cnt_sources = inp_val.cnt_sources;
			switch(inp_entity)
			{
				case 'org':
					$scope.org_id = inp_val.org.org_id || '';
					$scope.org_name = inp_val.org.org_name || '';
					$scope.org_prim_contact = inp_val.org.org_prim_contact || null;
					$scope.org_website = inp_val.org.org_website || '';
					$scope.org_address = inp_val.org.org_address || '';
					break;
			}
			$scope.ok = function() {
				$('.js-tabindex').not('.modal-content .js-tabindex').each(function(){
					$(this).attr('tabindex', $(this).attr('data-tabindex'));
					$(this).attr('disabled', false);
				});
				$('[tabindex=1]').focus();
				$uibModalInstance.close($scope);
			};
			
			$scope.cancel = function() {
				$('.js-tabindex').not('.modal-content .js-tabindex').each(function(){
					$(this).attr('tabindex', $(this).attr('data-tabindex'));
					$(this).attr('disabled', false);
				});
				$('[tabindex=1]').focus();
				$uibModalInstance.dismiss('cancel');
			}
		}
		/*----------------------------------------------------------------------------------------------------------------*/


		/*----------------------------------PAGE LOAD FUNCTION: DISPLAY HIDDEN ELEMENTS-----------------------------------*/
		// AngularJS elments hidden using data-ng-if are not hidden until the AngularJS app is loaded, causing a brief "flicker"
		// effect unless they are initially hidden by CSS.
		// js-load-wait class is used to remove "display:none" propertes on elements once AngularJS app is loaded
		$(document).ready(function(){
			$('.js-load_wait').show();
		});
		/*----------------------------------------------------------------------------------------------------------------*/
	</script>
	<!--------------------------------------------------------------------------------------------------------------------->
</body>
</html>