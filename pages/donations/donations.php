<?php 
/*
	Created by Neil Snyder 
	File Function: Simple front-end for managing donation records in donor database
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<html>
<head>
	<title>Donations</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/donor_database/lib_css/bootstrap/bootstrap.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/bootstrap/bootstrap.css'); ?>">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>

	<div data-ng-app="donations_app" data-ng-controller="donations_ctl">
		<!---------------------------DEFINE MODAL POP-UP FOR EDITING AND INSERTING RECORDS--------------------------------->
		<script type="text/ng-template" id="myModalContent.html">
			<div id="modal_body" class="modal-body align-middle">
				<div data-ng-form="inp_frm">
					{{don_source_id}}
					<table class="mb-3">
						<tr>
							<th class="text-right">Source Type:</th>
							<td><select data-ng-model="don_source_type" data-ng-options="type for type in source_types" autofocus tabindex="1"></td>
							<th class="text-right">Source:</th>
							<td data-ng-if="don_source_type == 'Contact'">
								<select data-ng-model="$parent.don_source_id" data-ng-options="cnt.cnt_id as cnt.cnt_text for cnt in cnt_sources" tabindex="2">
							</td>
							<td data-ng-if="don_source_type == 'Organization'">
								<select data-ng-model="$parent.don_source_id" data-ng-options="org.org_id as org.org_text for org in org_sources" tabindex="2">
							</td>
						</tr>
						<tr>
							<th class="text-right">Amount:</th>
							<td><input type="number" data-ng-model="don_amount" tabindex="3"></td>
							<th class="text-right">Date:</th>
							<td><input type="date" data-ng-model="don_date" tabindex="4"></td>
						</tr>
						<tr>
							<th class="text-right">Thankyou:</th>
							<td><input type="date" data-ng-model="don_thankyou" tabindex="5"></td>
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
		<button class="a-font--small mt-2 ml-2" data-ng-click="open('insert','don')">Add New Donation</button>
		<table class="table table-fixed m-2">
			<caption class="table-title thead-dark">Donations</caption>
			<thead>
				<tr class="thead-light">
					<th class="text-nowrap text-center a-clickable" data-ng-click="query('load_donations_data')" colspan="10">Filters</th>
				</tr>
				<tr>
					<td></td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="don_stype_null_flt" tabindex="6"> Nulls Only</td>
					<td class="text-center text-nowrap"><input class="js-tabindex" type="checkbox" data-ng-model="don_source_null_flt" tabindex="8"> Nulls Only</td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_amount_flt_min" tabindex="10"></td>
					<td><input class="js-tabindex" type="date" data-ng-model="don_date_start_flt" tabindex="12"></td>
					<td><input class="js-tabindex" type="date" data-ng-model="don_thankyou_start_flt" tabindex="14"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_count_flt_min" tabindex="16"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_sum_flt_min" tabindex="18"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_rank_flt_min" tabindex="20"></td>
				</tr>
				<tr>
					<td></td>
					<td><input class="js-tabindex" type="text" data-ng-model="don_source_type_flt" tabindex="7"></td>
					<td><input class="js-tabindex" type="text" data-ng-model="don_source_flt" tabindex="9"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_amount_flt_max" tabindex="11"></td>
					<td><input class="js-tabindex" type="date" data-ng-model="don_date_end_flt" tabindex="13"></td>
					<td><input class="js-tabindex" type="date" data-ng-model="don_thankyou_end_flt" tabindex="15"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_count_flt_max" tabindex="17"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_sum_flt_max" tabindex="19"></td>
					<td><input class="js-tabindex" type="number" data-ng-model="don_rank_flt_max" tabindex="21"></td>
				</tr>
				<tr class="thead-light">
					<th class="compress_cell text-nowrap text-center">Delete</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_source_type')">Source Type</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_source_text')">Source</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_amount')">Amount</th>
					<th class="text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_date')">Date</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_thankyou')">Thank You Note</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_count')"># of Donations</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_sum')">Total Donations</th>
					<th class="compress_cell text-nowrap text-center" data-ng-click="toggle_group_sort('donations_sort','don_rank')">Total Rank</th>
				</tr>
			</thead>
			<tbody>
				<tr class="hvr-bg-lightyellow" data-ng-repeat="don in donations_data | orderBy: donations_sort['col'] : donations_sort[donations_sort['col']] | filter: don_filters()" data-ng-dblclick="open('edit','don',{don:don})">
					<td class="text-center border border-gray border-thin"><button type="button" class="c-table__btn c-table__btn--small" data-ng-click="query('delete',{don_id:don.don_id})"><img src="/donor_database/images/delete_x.png" height="16px"></img></button></td>
					<td class="compress_cell text-nowrap text-center">{{don.don_source_type}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_source_text}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_amount}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_date}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_thankyou}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_count}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_sum}}</td>
					<td class="compress_cell text-nowrap text-center">{{don.don_rank}}</td>
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
		var app = angular.module('donations_app', ['ui.bootstrap']);
		/*-----------------------------------CONTROLLER: MODULE TABLE & CONTROL BUTTONS-----------------------------------*/
		app.controller('donations_ctl', donCtl);
		donCtl.$inject = ['$scope','$http','$uibModal'];
		function donCtl($scope, $http, $uibModal){
			var don_data = [];
			$scope.source_types = ['Contact','Organization'];
			$scope.source_list = {Contact:[],Organization:[]};
			/*-----------------------------------SCOPE FUNCTION: DYNAMICALLY PULL DATA------------------------------------*/
			$scope.query = function(action, params){
				let headers = {'Content-Type': 'application/x-www-form-urlencoded'};
				let config = {headers: {'Content-Type' : 'application/x-www-form-urlencoded;charset=utf-8;'}};
				params = params || {};
				switch(action){
					case 'load_donations_data':
						var obj = {action: action};
						var retdata = $http({
							method: 'POST', 
							url: 'queries.php', 
							data: JSON.stringify(obj), 
							headers: headers
						}).then(function(response) {
								console.log({msg:'donations loaded', data:response});
								$scope.donations_sort = $scope.donations_sort || {};
								$scope.donations_data = response['data']['donations_data'];
								$scope.cnt_sources = response['data']['cnt_sources'];
								$scope.org_sources = response['data']['org_sources'];
								for(don in $scope.donations_data)
								{
									don_arr = $scope.donations_data[don];
									if(typeof don_arr.don_amount !== 'undefined')
									{
										$scope.donations_data[don].don_amount = don_arr.don_amount == parseFloat(don_arr.don_amount) ? parseFloat(don_arr.don_amount) : don_arr.don_amount;
									}
								}
								
								for(don in $scope.donations_data)
								{
									don_arr = $scope.donations_data[don];
									don_matches = $scope.donations_data.filter(function(d){return d.don_source_type == don_arr.don_source_type && d.don_source_id == don_arr.don_source_id;});
									$scope.donations_data[don]['don_count'] = don_matches.length;
									$scope.donations_data[don]['don_sum'] = don_matches.map(function(d){return d.don_amount;}).reduce(function(c, v){return c + v;});
								}
								
								source_list = $scope.donations_data.map(function(d){return d.don_source_type+': '+d.don_source_id.toString();});
								unique_donations = $scope.donations_data.filter(function(d, k){return k == source_list.indexOf(d.don_source_type+': '+d.don_source_id.toString());})
								unique_donations = unique_donations.map(function(d){return d.don_sum;});
								unique_donations = unique_donations.sort(function(a, b){return a > b ? -1 : 1;});
								for(don in $scope.donations_data)
								{
									$scope.donations_data[don]['don_rank'] = unique_donations.indexOf($scope.donations_data[don]['don_sum'])+1;
								}
								
								$scope.don_filters();
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
							console.log({success:response});
							$scope.query('load_donations_data');
						}, function(response) {
							console.log({failure:response});
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
			$scope.query('load_donations_data');
			
			$scope.don_date_start_flt = new Date(new Date().getFullYear()-5, 0, 1);
			$scope.don_date_end_flt = new Date(new Date().getFullYear(), new Date().getMonth()+1, 0);

			$scope.don_thankyou_start_flt = new Date(new Date().getFullYear()-5, 0, 1);
			$scope.don_thankyou_end_flt = new Date(new Date().getFullYear(), new Date().getMonth()+1, 0);
			
			$scope.don_count_flt_min = 0;
			$scope.don_count_flt_max = 10000;

			$scope.don_sum_flt_min = 0;
			$scope.don_sum_flt_max = 1000000;

			$scope.don_rank_flt_min = 0;
			$scope.don_rank_flt_max = 10000;

			$scope.don_amount_flt_min = 0;
			$scope.don_amount_flt_max = 10000;

			if(typeof $_GET['month'] !== 'undefined')
			{
				let yr = parseInt($_GET['month'].substr(0,2)) + 2000;
				let mn = parseInt($_GET['month'].substr(3, 2));
				$scope.don_date_start_flt = new Date(yr, mn-1, 1);
				$scope.don_date_end_flt = new Date(yr, mn-1, new Date(yr, mn, 0).getDate());
			}
			/*------------------------------------------------------------------------------------------------------------*/
			

			/*-------------------------------TABLE FILTERS: DEFINE FILTER ELEMENT BEHAVIORS-------------------------------*/
			$scope.don_filters = function(){
				return function(don) {
					let stype_flt = $scope.don_stype_flt !== null ? new RegExp($scope.don_stype_flt, 'gi') : new RegExp('.*', 'g');
					stype_flt = $scope.stype_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : stype_flt;

					let source_flt = $scope.don_source_flt !== null ? new RegExp($scope.don_source_flt, 'gi') : new RegExp('.*', 'g');
					source_flt = $scope.source_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : source_flt;
					
					stype_val = don.don_source_type === null ? '' : don.don_source_type;
					source_val = don.don_source_text === null ? '' : don.don_source_text;

					code_val = don.don_code === null ? '' : don.don_code;
					type_val = don.don_type === null ? '' : don.don_type;
					lock_val = don.don_lock === null ? '' : don.don_lock;
					date_val = don.don_date === null ? new Date() : new Date(don.don_date);
					date_val.setTime(date_val.getTime() + 8*60*60*1000);
					thankyou_val = don.don_thankyou === null ? new Date() : new Date(don.don_thankyou);
					thankyou_val.setTime(thankyou_val.getTime() + 8*60*60*1000);

					flt_pass = true;
					flt_pass = flt_pass && stype_val.match(stype_flt);
					flt_pass = flt_pass && source_val.match(source_flt);
					flt_pass = flt_pass && (don.don_amount == null || (don.don_amount >= $scope.don_amount_flt_min && don.don_amount <= $scope.don_amount_flt_max));
					flt_pass = flt_pass && (don.don_count == null || (don.don_count >= $scope.don_count_flt_min && don.don_count <= $scope.don_count_flt_max));
					flt_pass = flt_pass && (don.don_sum == null || (don.don_sum >= $scope.don_sum_flt_min && don.don_sum <= $scope.don_sum_flt_max));
					flt_pass = flt_pass && (don.don_rank == null || (don.don_rank >= $scope.don_rank_flt_min && don.don_rank <= $scope.don_rank_flt_max));
					flt_pass = flt_pass && ($scope.don_date_start_flt === null || $scope.don_date_end_flt === null || date_val >= $scope.don_date_start_flt && date_val <= $scope.don_date_end_flt);
					flt_pass = flt_pass && ($scope.don_thankyou_start_flt === null || $scope.don_thankyou_end_flt === null || thankyou_val >= $scope.don_thankyou_start_flt && thankyou_val <= $scope.don_thankyou_end_flt);
					return flt_pass
				};
			};
			/*------------------------------------------------------------------------------------------------------------*/			


			/*-------------------------SCOPE FUNCTION: SHOW MODAL FOR EDITING & INSERTING RECORDS-------------------------*/
			$scope.open = function(inp_mode,inp_entity,inp_params){
				var don_match = [];
				let params = inp_params || {};
				let inp_val = '';				
				switch(inp_mode+'_'+inp_entity)
				{
					case 'insert_don':
						inp_val = {
							don: {
								don_id: null, 
								don_source_type: 'Contact', 
								don_source_id: $scope.cnt_sources[0].cnt_id}, 
							cnt_sources: $scope.cnt_sources, 
							org_sources: $scope.org_sources
						};
						break;
					case 'edit_don': 
						inp_val = {
							don: params.don, 
							cnt_sources: $scope.cnt_sources, 
							org_sources: $scope.org_sources
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
						case 'insert_don': 
						case 'edit_don': 
							params.ent_type = 'donation';
							params.don_id = result.don_id || null;
							params.don_source_type = result.don_source_type || 'Contact';
							params.don_source_id = result.don_source_id || null;
							params.don_date = result.don_date || '';
							params.don_thankyou = result.don_thankyou || '';
							params.don_amount = result.don_amount || 0;
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
			console.log({inp_params:inp_params});			
			$('.js-tabindex').not('.modal-content .js-tabindex').each(function(){
				$(this).attr('data-tabindex', $(this).attr('tabindex'));
				$(this).attr('tabindex', -1);
				$(this).attr('disabled', true);
			});
			let inp_mode = inp_params.inp_mode;
			let inp_entity = inp_params.inp_entity;
			let inp_val = inp_params.inp_val;
			$scope.cnt_sources = inp_val.cnt_sources;
			$scope.org_sources = inp_val.org_sources;
			$scope.source_types = ['Contact','Organization'];
			switch(inp_entity)
			{
				case 'don':
					$scope.don_id = inp_val.don.don_id || '';
					$scope.don_source_type = inp_val.don.don_source_type || '';
					$scope.don_source_id = inp_val.don.don_source_id || '';
					$scope.don_amount = inp_val.don.don_amount || '';

					if(typeof inp_val.don.don_date != 'undefined' && inp_val.don.don_date != null)
					{
						$scope.don_date = new Date(inp_val.don.don_date) || new Date();
						$scope.don_date.setTime($scope.don_date.getTime() + 8*60*60*1000);
					}
					else
						{$scope.don_date = null;}

					if(typeof inp_val.don.don_thankyou != 'undefined' && inp_val.don.don_thankyou != null)
					{
						$scope.don_thankyou = new Date(inp_val.don.don_thankyou) || new Date();
						$scope.don_thankyou.setTime($scope.don_thankyou.getTime() + 8*60*60*1000);
					}
					else
						{$scope.don_thankyou = null;}
					break;
			}
			$scope.ok = function() {
				$scope.don_date = $scope.don_date == "" ? null : $scope.don_date;
				$('.js-tabindex').not('.modal-content .js-tabindex').each(function(){
					$(this).attr('tabindex', $(this).attr('data-tabindex'));
					$(this).attr('disabled', false);
				});
				$('[tabindex=1]').focus();
				$uibModalInstance.close($scope);
			};
			
			$scope.cancel = function() {
				$scope.don_thankyou = $scope.don_thankyou == "" ? null : $scope.don_thankyou;
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
		/*-----------------------------------------------------------------------------------------------------------------*/
	</script>
	<!--------------------------------------------------------------------------------------------------------------------->
</body>
</html>