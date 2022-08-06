<?php 
/*
	Created by Neil Snyder 
	File Function: Simple front-end for managing contact records in donor database
*/
include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/page_init.php');
?>
<html>
<head>
	<title>Contacts</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/donor_database/lib_css/bootstrap/bootstrap.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/bootstrap/bootstrap.css'); ?>">
	<link rel="stylesheet" href="/donor_database/lib_css/standard.css?modtime=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/donor_database/lib_css/standard.css'); ?>">
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/donor_database/includes/header.php'); ?>

	<div data-ng-app="contacts_app" data-ng-controller="contacts_ctl">
		<!---------------------------DEFINE MODAL POP-UP FOR EDITING AND INSERTING RECORDS--------------------------------->
		<script type="text/ng-template" id="myModalContent.html">
			<div id="modal_body" class="modal-body modal-large align-middle">
				<div data-ng-form="inp_frm">
					<table class="mb-3">
						<tr>
							<th class="text-right">First:</th>
							<td><input type="text" data-ng-model="cnt_first" autofocus tabindex="1"></td>
							<th class="text-right">Phone:</th>
							<td><input type="text" data-ng-model="cnt_phone" tabindex="6"></td>
							<th class="text-right">City:</th>
							<td><input type="text" data-ng-model="cnt_city" tabindex="10"></td>
						</tr>
							<th class="text-right">Last:</th>
							<td><input type="text" data-ng-model="cnt_last" tabindex="2"></td>
							<th class="text-right">Email:</th>
							<td><input type="text" data-ng-model="cnt_email" tabindex="7"></td>
							<th class="text-right">State:</th>
							<td><input type="text" data-ng-model="cnt_state" tabindex="11"></td>
						<tr>
							<th class="text-right text-nowrap">Org:</th>
							<td><input type="text" data-ng-model="cnt_org" tabindex="5"></td>
							<th class="text-right">Mailchimp:</th>
							<td><input type="text" data-ng-model="cnt_mailchimp" tabindex="8"></td>
							<th class="text-right">Zip:</th>
							<td><input type="text" data-ng-model="cnt_zip" tabindex="12"></td>
						</tr>
						<tr>
							<th></th><td></td>
							<th class="text-right">FB Profile:</th>
							<td><input type="text" data-ng-model="cnt_fb_profile" tabindex="9"></td>
							<th class="text-right">Country:</th>
							<td><input type="text" data-ng-model="cnt_country" tabindex="13"></td>
						</tr>
						<tr>
							<th class="text-right text-nowrap align-top">Personal Details:</th>
							<td colspan="5"><textarea data-ng-model="cnt_personal_details" rows="10" cols="100" tabindex="16"></textarea></td>
						</tr>
					</table>
					<div ng-messages="inp_frm.$error" class="text-danger">
					</div>
				</div>
				<button class="btn btn-primary js-tabindex" data-ng-click="ok()" tabindex="17">OK</button>
				<button class="btn btn-secondary js-tabindex" data-ng-click="cancel()" tabindex="17">Cancel</button>
			</div>
		</script>
		<!----------------------------------------------------------------------------------------------------------------->
	

		<!--------------------------------------------WRITE TABULAR DATA--------------------------------------------------->
		<button class="a-font--small mt-2 ml-2" data-ng-click="open('insert','cnt')">Add New Contact</button>
		<table class="table table-fixed m-2">
			<caption class="table-title thead-dark">Contacts</caption>
			<thead>
				<tr class="thead-light">
					<th class="text-nowrap text-left a-clickable" data-ng-click="query('load_contacts_data')" colspan="18">Filters</th>
				</tr>
				<tr>
					<td></td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_first_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_last_null_flt"> Nulls Only</td>
					<td><input type="number" data-ng-model="cnt_don_rank_flt_min"></td>
					<td><input type="number" data-ng-model="cnt_don_count_flt_min"></td>
					<td><input type="number" data-ng-model="cnt_don_sum_flt_min"></td>
					<td><input type="date" data-ng-model="cnt_last_thankyou_start_flt"></td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_personal_details_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_mailchimp_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_org_text_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_email_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_fb_profile_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_phone_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_street_addr_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_city_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_state_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_zip_null_flt"> Nulls Only</td>
					<td class="text-center text-nowrap js-tabindex"><input type="checkbox" data-ng-model="cnt_country_null_flt"> Nulls Only</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="text" data-ng-model="cnt_first_flt"></td>
					<td><input type="text" data-ng-model="cnt_last_flt"></td>
					<td><input type="number" data-ng-model="cnt_don_rank_flt_max"></td>
					<td><input type="number" data-ng-model="cnt_don_count_flt_max"></td>
					<td><input type="number" data-ng-model="cnt_don_sum_flt_max"></td>
					<td><input type="date" data-ng-model="cnt_last_thankyou_end_flt"></td>
					<td><input type="text" data-ng-model="cnt_personal_details_flt"></td>
					<td><input type="text" data-ng-model="cnt_mailchimp_flt"></td>
					<td><input type="text" data-ng-model="cnt_org_text_flt"></td>
					<td><input class="js-tabindex w-100" type="text" data-ng-model="cnt_email_flt"></td>
					<td><input class="js-tabindex w-100" type="text" data-ng-model="cnt_fb_profile_flt"></td>
					<td><input type="text" data-ng-model="cnt_phone_flt"></td>
					<td><input type="text" data-ng-model="cnt_streed_addr_flt"></td>
					<td><input type="text" data-ng-model="cnt_city_flt"></td>
					<td><input type="text" data-ng-model="cnt_state_flt"></td>
					<td><input type="text" data-ng-model="cnt_zip_flt"></td>
					<td><input type="text" data-ng-model="cnt_country_flt"></td>
				</tr>
				<tr class="thead-light">
					<th class="compress_cell text-nowrap text-center">Delete</th>
					<th class="bs-table_header--sortable compress_cell text-nowrap text-center a-clickable" data-ng-click="toggle_group_sort('contacts_sort','cnt_first')">First Name</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_last')">Last Name</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_don_rank')">Donor Rank</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_don_count')">Donations Count</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_don_sum')">Total Donations</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_last_thankyou')">Last Thankyou</th>
					<th class="compress_cell text-center text-nowrap"><span class="a-clickable a-text--noselecet" data-ng-click="toggle_group_sort('contacts_sort','cnt_peronsal_details')">Personal Details</span> <button data-ng-click="show_personal_details = !show_personal_details">{{show_personal_details ? 'Collapse' : 'Expand'}}</button></th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_mailchimp')">Mailchimp</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_org')">Org</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_email')">Email</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_fb_profile')">FB Profile</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_phone')">Phone</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_street_addr')">Address</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_city')">City</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_state')">State</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_zip')">Zip</th>
					<th class="compress_cell c-bstable_header--sortable" data-ng-click="toggle_group_sort('contacts_sort','cnt_country')">Country</th>
				</tr>
			</thead>
			<tbody>
				<tr class="hvr-bg-lightyellow " data-ng-repeat="cnt in contacts_data | orderBy: contacts_sort['col'] : contacts_sort[contacts_sort['col']] | filter: cnt_filters()" data-ng-dblclick="open('edit','cnt',{cnt_id:cnt.cnt_id})">
					<td class="text-center border border-gray border-thin"><button type="button" class="c-table__btn c-table__btn--small" data-ng-click="query('delete',{cnt_id:cnt.cnt_id})"><img src="/donor_database/images/delete_x.png" height="16px"></img></button></td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_first}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_last}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_don_rank}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_don_count}}</td>
					<td class="compress_cell text-nowrap text-right">{{number_format(cnt.cnt_don_sum, 2)}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_last_thankyou}}</td>
					<td class="compress_cell text-left" data-ng-click="$parent.show_personal_details = !$parent.show_personal_details">{{show_personal_details ? cnt.cnt_personal_details : cnt.cnt_personal_details.substring(0, 20)+'...'}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_mailchimp}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_org}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_email}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_fb_profile}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_phone}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_street_addr}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_city}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_state}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_zip}}</td>
					<td class="compress_cell text-nowrap text-center">{{cnt.cnt_country}}</td>
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
		var app = angular.module('contacts_app', ['ui.bootstrap']);
		/*-----------------------------------CONTROLLER: MODULE TABLE & CONTROL BUTTONS-----------------------------------*/
		app.controller('contacts_ctl', cntCtl);
		cntCtl.$inject = ['$scope','$http','$uibModal'];
		function cntCtl($scope, $http, $uibModal){
			var cnt_data = [];
			/*-----------------------------------SCOPE FUNCTION: DYNAMICALLY PULL DATA------------------------------------*/
			$scope.query = function(action, params){
				let headers = {'Content-Type': 'application/x-www-form-urlencoded'};
				let config = {headers: {'Content-Type' : 'application/x-www-form-urlencoded;charset=utf-8;'}};
				params = params || {};
				switch(action){
					case 'load_contacts_data':
						var obj = {action: action};
						var retdata = $http({
							method: 'POST', 
							url: 'queries.php', 
							data: JSON.stringify(obj), 
							headers: headers
						}).then(function(response) {
								$scope.contacts_sort = $scope.contacts_sort || {};
								$scope.org_sources = response['data']['org_sources'];
								
								$scope.donations_data = response['data']['donations_data'];
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

								max_rank = Math.max.apply(Math, $scope.donations_data.map(function(d){return d.don_rank;}));
								$scope.contacts_data = response['data']['contacts_data'];
								for(cnt in $scope.contacts_data)
								{
									cnt_arr = $scope.contacts_data[cnt];
									org_matches = $scope.org_sources.filter(function(d){return d.org_id = cnt_arr.cnt_org;});
									$scope.contacts_data[cnt]['org_text'] = org_matches.length > 0 ? org_matches[0].org_text : '';
									don_matches = $scope.donations_data.filter(function(d){return d.don_source_type == 'Contact' && d.don_source_id == cnt_arr.cnt_id});

									$scope.contacts_data[cnt].cnt_don_rank = don_matches.length > 0 ? don_matches[0].don_rank : max_rank + 1;
									$scope.contacts_data[cnt].cnt_don_count = don_matches.length > 0 ? don_matches[0].don_count : 0;
									$scope.contacts_data[cnt].cnt_don_sum = don_matches.length > 0 ? don_matches[0].don_sum : 0;
									
									if(don_matches.filter(function(d){return d.don_thankyou != null;}).length > 0)
									{
										date_arr = don_matches.filter(function(d){return d.don_thankyou != null;}).map(function(d){return new Date(parseInt(d.don_thankyou.substring(0,4)), parseInt(d.don_thankyou.substring(5, 7))-1, parseInt(d.don_thankyou.substring(8, 10)));});
										lty = new Date(Math.max.apply(null, date_arr));
										$scope.contacts_data[cnt].cnt_last_thankyou = lty.getUTCFullYear()+(lty.getUTCMonth() > 8 ? '-' : '-0')+(lty.getUTCMonth()+1)+'-'+lty.getUTCDate();
									} else 
										{$scope.contacts_data[cnt].cnt_last_thankyou = null;}
								}

								$scope.cnt_filters();
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
							$scope.query('load_contacts_data');
						}, function(response) {

						});
						break;
					default:
				}
			};
			/*------------------------------------------------------------------------------------------------------------*/


			/*--------------------------------FUNCTION: FORMAT NUMERIC VALUES WITH COMMAS---------------------------------*/
			$scope.number_format = function(v, d){
				return formatNumber(v, d);
			}
			/*------------------------------------------------------------------------------------------------------------*/


			/*--------------------------------FUNCTION: SORT DATA SET BY SPECIFIED COLUMN---------------------------------*/
			$scope.toggle_group_sort = function(arr,col){
				$scope[arr]['col'] = col;
				$scope[arr][col] = !$scope[arr][col];
			}
			/*------------------------------------------------------------------------------------------------------------*/
			

			/*---------------------------------DEFINE DEFAULT NGMODEL VALUS AND LOAD DATA---------------------------------*/
			$scope.query('load_contacts_data');
			$scope.cnt_don_count_flt_min = 0;
			$scope.cnt_don_count_flt_max = 10000;
			$scope.cnt_don_sum_flt_min = 0;
			$scope.cnt_don_sum_flt_max = 10000000;
			$scope.cnt_don_rank_flt_min = 0;
			$scope.cnt_don_rank_flt_max = 10000;
			$scope.cnt_last_thankyou_start_flt = new Date(new Date().getFullYear()-5, 0, 1);
			$scope.cnt_last_thankyou_end_flt = new Date(new Date().getFullYear(), new Date().getMonth()+1, 0);
			/*-------------------------------------------------------------------------------------------------------------*/
			

			/*-------------------------------TABLE FILTERS: DEFINE FILTER ELEMENT BEHAVIORS-------------------------------*/
			$scope.cnt_filters = function(){
				return function(cnt) {
					let first_flt = $scope.cnt_first_flt !== null ? new RegExp($scope.cnt_first_flt, 'gi') : new RegExp('.*', 'g');
					first_flt = $scope.first_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : first_flt;

					let last_flt = $scope.cnt_last_flt !== null ? new RegExp($scope.cnt_last_flt, 'gi') : new RegExp('.*', 'g');
					last_flt = $scope.last_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : last_flt;

					let email_flt = $scope.cnt_email_flt !== null ? new RegExp($scope.cnt_email_flt, 'gi') : new RegExp('.*', 'g');
					email_flt = $scope.email_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : email_flt;

					let fb_profile_flt = $scope.cnt_fb_profile_flt !== null ? new RegExp($scope.cnt_fb_profile_flt, 'gi') : new RegExp('.*', 'g');
					fb_profile_flt = $scope.fb_profile_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : fb_profile_flt;

					let phone_flt = $scope.cnt_phone_flt !== null ? new RegExp($scope.cnt_phone_flt, 'gi') : new RegExp('.*', 'g');
					phone_flt = $scope.phone_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : phone_flt;

					let street_addr_flt = $scope.cnt_street_addr_flt !== null ? new RegExp($scope.cnt_street_addr_flt, 'gi') : new RegExp('.*', 'g');
					street_addr_flt = $scope.street_addr_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : street_addr_flt;

					let city_flt = $scope.cnt_city_flt !== null ? new RegExp($scope.cnt_city_flt, 'gi') : new RegExp('.*', 'g');
					city_flt = $scope.city_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : city_flt;

					let state_flt = $scope.cnt_state_flt !== null ? new RegExp($scope.cnt_state_flt, 'gi') : new RegExp('.*', 'g');
					state_flt = $scope.state_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : state_flt;

					let zip_flt = $scope.cnt_zip_flt !== null ? new RegExp($scope.cnt_zip_flt, 'gi') : new RegExp('.*', 'g');
					zip_flt = $scope.zip_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : zip_flt;

					let country_flt = $scope.cnt_country_flt !== null ? new RegExp($scope.cnt_country_flt, 'gi') : new RegExp('.*', 'g');
					country_flt = $scope.country_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : country_flt;

					let mailchimp_flt = $scope.cnt_mailchimp_flt !== null ? new RegExp($scope.cnt_mailchimp_flt, 'gi') : new RegExp('.*', 'g');
					mailchimp_flt = $scope.mailchimp_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : mailchimp_flt;

					let org_text_flt = $scope.cnt_org_text_flt !== null ? new RegExp($scope.cnt_org_text_flt, 'gi') : new RegExp('.*', 'g');
					org_text_flt = $scope.org_text_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : org_text_flt;

					let personal_details_flt = $scope.cnt_personal_details_flt !== null ? new RegExp($scope.cnt_personal_details_flt, 'gi') : new RegExp('.*', 'g');
					personal_details_flt = $scope.cnt_personal_details_null_flt ? new RegExp('^(?![\\s\\S])', 'g') : personal_details_flt;

					first_val = cnt.cnt_first === null ? '' : cnt.cnt_first;
					last_val = cnt.cnt_last === null ? '' : cnt.cnt_last;
					last_thankyou_val = cnt.last_thankyou === null ? new Date() : new Date(cnt.cnt_last_thankyou);
					last_thankyou_val.setTime(last_thankyou_val.getTime() + 8*60*60*1000);
					email_val = cnt.cnt_email === null ? '' : cnt.cnt_email;
					fb_profile_val = cnt.cnt_fb_profile === null ? '' : cnt.cnt_fb_profile;
					phone_val = cnt.cnt_phone === null ? '' : cnt.cnt_phone;
					street_addr_val = cnt.cnt_street_addr === null ? '' : cnt.cnt_street_addr;
					city_val = cnt.cnt_city === null ? '' : cnt.cnt_city;
					state_val = cnt.cnt_state === null ? '' : cnt.cnt_state;
					zip_val = cnt.cnt_zip === null ? '' : cnt.cnt_zip;
					country_val = cnt.cnt_country === null ? '' : cnt.cnt_country;
					mailchimp_val = cnt.cnt_mailchimp === null ? '' : cnt.cnt_mailchimp;
					org_text_val = cnt.cnt_org_text === null ? '' : cnt.cnt_org_text;
					personal_details_val = cnt.cnt_personal_details === null ? '' : cnt.cnt_personal_details;

					flt_pass = true;
					flt_pass = flt_pass && ($scope.cnt_first_null_flt && first_val == '' || !$scope.cnt_first_null_flt && first_val.match(first_flt));
					flt_pass = flt_pass && ($scope.cnt_last_null_flt && last_val == '' || !$scope.cnt_last_null_flt && last_val.match(last_flt));
					flt_pass = flt_pass && (cnt.cnt_don_count == null || (cnt.cnt_don_count >= $scope.cnt_don_count_flt_min && cnt.cnt_don_count <= $scope.cnt_don_count_flt_max));
					flt_pass = flt_pass && (cnt.cnt_don_sum == null || (cnt.cnt_don_sum >= $scope.cnt_don_sum_flt_min && cnt.cnt_don_sum <= $scope.cnt_don_sum_flt_max));
					flt_pass = flt_pass && (cnt.cnt_don_rank == null || (cnt.cnt_don_rank >= $scope.cnt_don_rank_flt_min && cnt.cnt_don_rank <= $scope.cnt_don_rank_flt_max));
					flt_pass = flt_pass && (cnt.cnt_last_thankyou == null || $scope.cnt_last_thankyou_start_flt === null || $scope.cnt_last_thankyou_end_flt === null || last_thankyou_val >= $scope.cnt_last_thankyou_start_flt && last_thankyou_val <= $scope.cnt_last_thankyou_end_flt);
					flt_pass = flt_pass && ($scope.cnt_email_null_flt && email_val == '' || !$scope.cnt_email_null_flt && email_val.match(email_flt));
					flt_pass = flt_pass && fb_profile_val.match(fb_profile_flt);
					flt_pass = flt_pass && ($scope.cnt_phone_null_flt && phone_val == '' || !$scope.cnt_phone_null_flt && phone_val.match(phone_flt));
					flt_pass = flt_pass && ($scope.cnt_street_addr_null_flt && street_addr_val == '' || !$scope.cnt_street_addr_null_flt && street_addr_val.match(street_addr_flt));
					flt_pass = flt_pass && ($scope.cnt_city_null_flt && city_val == '' || !$scope.cnt_city_null_flt && city_val.match(city_flt));
					flt_pass = flt_pass && ($scope.cnt_state_null_flt && state_val == '' || !$scope.cnt_state_null_flt && state_val.match(state_flt));
					flt_pass = flt_pass && ($scope.cnt_zip_null_flt && zip_val == '' || !$scope.cnt_zip_null_flt && zip_val.match(zip_flt));
					flt_pass = flt_pass && ($scope.cnt_country_null_flt && country_val == '' || !$scope.cnt_country_null_flt && country_val.match(country_flt));
					flt_pass = flt_pass && ($scope.cnt_mailchimp_null_flt && mailchimp_val == '' || !$scope.cnt_mailchimp_null_flt && mailchimp_val.match(mailchimp_flt));
					flt_pass = flt_pass && ($scope.cnt_org_text_null_flt && org_text_val == '' || !$scope.cnt_org_text_null_flt && org_text_val.match(org_text_flt));
					flt_pass = flt_pass && ($scope.cnt_personal_details_null_flt && personal_details_val == '' || !$scope.cnt_personal_details_null_flt && personal_details_val.match(personal_details_flt));
					return flt_pass
				};
			};
			/*------------------------------------------------------------------------------------------------------------*/			


			/*-------------------------SCOPE FUNCTION: SHOW MODAL FOR EDITING & INSERTING RECORDS-------------------------*/
			$scope.open = function(inp_mode,inp_entity,inp_params){
				var cnt_match = [];
				let params = inp_params || {};
				let inp_val = '';				
				switch(inp_mode+'_'+inp_entity)
				{
					case 'insert_cnt':
						inp_val = {cnt: {cnt_id: null}};
						break;
					case 'edit_cnt': 
						cnt_match = $scope.contacts_data.filter(function(d){return d.cnt_id == params.cnt_id})[0];
						inp_val = {cnt: cnt_match}
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
console.log({result:result});					
					let params = {ent_type: ''};
					switch(inp_mode+'_'+inp_entity)
					{
						case 'insert_cnt':
						case 'edit_cnt': 
							params.ent_type = 'contact';
							params.cnt_id = result.cnt_id || null;
							params.cnt_first = result.cnt_first || '';
							params.cnt_last = result.cnt_last || '';
							params.cnt_email = result.cnt_email || '';
							params.cnt_fb_profile = result.cnt_fb_profile || '';
							params.cnt_phone = result.cnt_phone || '';
							params.cnt_street_addr = result.cnt_street_addr || '';
							params.cnt_city = result.cnt_city || '';
							params.cnt_state = result.cnt_state || '';
							params.cnt_zip = result.cnt_zip || '';
							params.cnt_country = result.cnt_country || '';
							params.cnt_mailchimp = result.cnt_mailchimp || '';
							params.cnt_org_text = result.cnt_org_text || '';
							params.cnt_org = result.cnt_org || '';
							params.cnt_personal_details = result.cnt_personal_details || '';
							break;
					}
					$scope.query(inp_mode, params);
					$('.js-selected').remove();
				}, function() {

				});
			};
			/*-------------------------------------------------------------------------------------------------------------*/			
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
			$scope.org_sources = inp_val.org_sources;
			switch(inp_entity)
			{
				case 'cnt':
					$scope.cnt_id = inp_val.cnt.cnt_id || '';
					$scope.cnt_first = inp_val.cnt.cnt_first || '';
					$scope.cnt_last = inp_val.cnt.cnt_last || '';
					$scope.cnt_email = inp_val.cnt.cnt_email || '';
					$scope.cnt_mailchimp = inp_val.cnt.cnt_mailchimp || '';
					$scope.cnt_phone = inp_val.cnt.cnt_phone || '';
					$scope.cnt_fb_profile = inp_val.cnt.cnt_fb_profile || '';
					$scope.cnt_street_addr = inp_val.cnt.cnt_street_addr || '';
					$scope.cnt_city = inp_val.cnt.cnt_city || '';
					$scope.cnt_state = inp_val.cnt.cnt_state || '';
					$scope.cnt_zip = inp_val.cnt.cnt_zip || '';
					$scope.cnt_country = inp_val.cnt.cnt_country || '';
					$scope.cnt_org = inp_val.cnt.cnt_org || '';
					$scope.cnt_org_id = inp_val.cnt.cnt_org_id || '';
					$scope.cnt_personal_details = inp_val.cnt.cnt_personal_details || '';
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
</body>
</html>