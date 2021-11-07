var app=angular.module("imports_app", ['ui.bootstrap']);
app.controller('imports_ctl', impCtl)
impCtl.$inject = ['$scope','$http','$uibModal'];
function impCtl($scope, $http, $uibModal){
	var import_data = [];

	/*--------------------------------------SCOPE FUNCTION: DYNAMICALLY PULL DATA--------------------------------------*/
	$scope.query = function(action, params){
		let headers = {'Content-Type': 'application/x-www-form-urlencoded'};
		let config = {headers: {'Content-Type' : 'application/x-www-form-urlencoded;charset=utf-8;'}};
		params = params || {};
		switch(action){
			/*--------------------------------------------CALL READ QUERIES--------------------------------------------*/
			case 'load_import_data':
				var obj = {action: action};
				var retdata = $http({
					method: 'POST', 
					url: 'queries.php', 
					data: JSON.stringify(obj), 
					headers: headers
				}).then(function(response) {
						console.log({load_import_success:response, data:response['data']});
						$scope.import_data = response['data']['import_data'];
				}, function(response) {
						console.log({load_modules_fail:response});
				});
				break;
			/*---------------------------------------------------------------------------------------------------------*/


			/*----------------------------------CALL WRITE, UPDATE, OR DELETE QUERIES----------------------------------*/
			case 'add':
			case 'update':
			case 'delete':						
				params['action'] = action;
				var retdata = $http({
					method: 'POST', 
					url: 'queries.php', 
					data: JSON.stringify(params), 
					headers: headers
				}).then(function(response){
					console.log({success:response});
				}, function(response) {
					console.log({failure:response});
				});
				break;
			/*---------------------------------------------------------------------------------------------------------*/
			default:
			}
	};

	/*---------------------------------------------LOAD DATA ON PAGE INIT----------------------------------------------*/
	$scope.query('load_import_data');
	/*-----------------------------------------------------------------------------------------------------------------*/

	/*-----------------------------------------------DELETE IMPORT RECORD-----------------------------------------------*/
	$scope.delete_imp = function(imp_id) {
		let params = {ent_type:'import', imp_id: imp_id};
		$scope.query('delete', params);
		$('.js-selected').remove();
		$scope.query('load_import_data');
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
};


/*---------------------------------------------------------------------------------------------------------------------*/
// PAGE LOAD FUNCTION: DISPLAY HIDDEN ELEMENTS
//		AngularJS elments hidden using data-ng-if are not hidden until the AngularJS app is loaded, causing a brief "flicker"
// effect unless they are initially hidden by CSS. js-load-wait class is used to remove "display:none" propertes on elements 
// once AngularJS app is loaded
$(document).ready(function(){
	$('.js-load_wait').show();
})
/*---------------------------------------------------------------------------------------------------------------------*/