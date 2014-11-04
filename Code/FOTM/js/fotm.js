'use strict';

var fotmApp = angular.module('fotmApp', ['fotmForm']);

var fotmForm = angular.module('fotmForm', []);

fotmForm.controller('fotmCtrl', ['$scope', function($scope){

	$scope.news = {
		name : {
			check : function () {
				$scope.hasNumber($scope.news.name);
			},
			error : false
		},
		city : {
			check : function () {
				$scope.hasNumber($scope.news.city);
			},
			error : false
		},
		county : {
			check : function () {
				$scope.hasNumber($scope.news.county);
			},
			error : false
		},
		date : {
			check : function () {
				if ($scope.isDate($scope.news.date.input) || $scope.news.date.input === "" || $scope.news.date.input === undefined) {
					$scope.news.date.error = false;
				}
				else {
					$scope.news.date.error = true;
				}
			},
			error : false
		},
		page : {
			check : function () {
				$scope.isNumber($scope.news.page);
			},
			error : false
		},
		url : {
			check : function () {
				if (($scope.isUrl($scope.news.url.input)) || $scope.news.url.input === "" || $scope.news.url.input === undefined) {
					$scope.news.url.error = false;
				}
				else {
					$scope.news.url.error = true;
				}
			},
			error : false
		},
	};

	$scope.ensl = {
		first : {
			check : function() {
				$scope.hasNumber($scope.ensl.first);
			},
			error : false
		},
		last : {
			check : function () {
				$scope.hasNumber($scope.ensl.last);
			},
			error : false
		},
		city : {
			check : function () {
				$scope.hasNumber($scope.ensl.city);
			},
			error : false
		},
		county : {
			check : function () {
				$scope.hasNumber($scope.ensl.county);
			},
			error : false
		},
		prevFirst : {
			check : function () {
				$scope.hasNumber($scope.ensl.prevFirst);
			},
			error : false
		},
		prevLast : {
			check : function () {
				$scope.hasNumber($scope.ensl.prevLast);
			},
			error : false
		}
	};

	$scope.runw = {
		name : {
			check : function() {
				$scope.hasNumber($scope.runw.name);
			},
			error : false
		},
		age : {
			check : function () {
				$scope.isNumber($scope.runw.age);
			},
			error : false
		},
		height : {
			check : function () {
				$scope.isFloat($scope.runw.height);
			},
			error : false
		},
		weight : {
			check : function () {
				$scope.isFloat($scope.runw.weight);
			},
			error : false
		},
		color : {
			check : function () {
				$scope.hasNumber($scope.runw.color);
			},
			error : false
		},
		lang : {
			check : function () {
				$scope.hasNumber($scope.runw.lang);
			},
			error : false
		},
		prof : {
			check : function () {
				$scope.hasNumber($scope.runw.prof);
			},
			error : false
		}
	};

	$scope.event = {
		countySold : {
			check : function() {
				$scope.hasNumber($scope.event.countySold);
			},
			error : false
		},
		citySold : {
			check : function () {
				$scope.hasNumber($scope.event.citySold);
			},
			error : false
		},
		countyRun : {
			check : function () {
				$scope.hasNumber($scope.event.countyRun);
			},
			error : false
		},
		cityRun : {
			check : function () {
				$scope.hasNumber($scope.event.cityRun);
			},
			error : false
		},
		where : {
			check : function () {
				$scope.hasNumber($scope.event.where);
			},
			error : false
		},
		number : {
			check : function () {
				$scope.isNumber($scope.event.number);
			},
			error : false
		},
		age : {
			check : function () {
				$scope.isNumber($scope.event.age);
			},
			error : false
		},
		reward : {
			check : function () {
				$scope.isNumber($scope.event.reward);
			},
			error : false
		}
	};

	$scope.child = {
		number : {
			check : function() {
				$scope.isNumber($scope.child.number);
			},
			error : false
		},
		// range : function() {
		// 	if ($scope.child.number.input === undefined || $scope.child.number.input === "") {
		// 		return [];
		// 	}
		// 	else {
		// 		return new Array(Math.min(parseInt($scope.child.number.input),10));
		// 	}
		// },
		oneName : {
			check : function () {
				$scope.hasNumber($scope.child.oneName);
			},
			error : false
		},
		oneAge : {
			check : function () {
				$scope.isNumber($scope.child.oneAge);
			},
			error : false
		},
		twoName : {
			check : function () {
				$scope.hasNumber($scope.child.twoName);
			},
			error : false
		},
		twoAge : {
			check : function () {
				$scope.isNumber($scope.child.twoAge);
			},
			error : false
		},
		threeName : {
			check : function () {
				$scope.hasNumber($scope.child.threeName);
			},
			error : false
		},
		threeAge : {
			check : function () {
				$scope.isNumber($scope.child.threeAge);
			},
			error : false
		},
		fourName : {
			check : function () {
				$scope.hasNumber($scope.child.fourName);
			},
			error : false
		},
		fourAge : {
			check : function () {
				$scope.isNumber($scope.child.fourAge);
			},
			error : false
		}
	};

	$scope.hasNumber = function(name) {
		if (/\d/.test(name.input)) {
			name.error = true;
		}
		else {
			name.error = false;
		}
	}

	$scope.isNumber = function(name) {
		if ((/^[\d]+$/.test(name.input)) || name.input === "" || name.input === undefined) {
			name.error = false;
		}
		else {
			name.error = true;
		}
	}

	$scope.isFloat = function(name) {
		if ((/^[\d]+(\.[\d]+)?$/.test(name.input)) || name.input === "" || name.input === undefined) {
			name.error = false;
		}
		else {
			name.error = true;
		}
	}

	$scope.isDate = function(input) {
		return /^(0\d|1[0-2])\/([0-2]\d|3[01])\/[\d]{4}$/.test(input);
	}

	$scope.isUrl = function(input) {
		return (/^(https?:\/\/|www\.)/.test(input)) && !(/[\s]/.test(input));
	}

	$scope.part = 1;

	$scope.hasError = function(formPart) {
		for (var index in formPart) {
			if (formPart[index].error) {
				return true;
			}
		}
		return false;
	}

	$scope.next = function () {
		if (($scope.hasError($scope.news)) || ($scope.hasError($scope.ensl)) || ($scope.hasError($scope.runw)) || ($scope.hasError($scope.event)) || ($scope.hasError($scope.child))) {
			alert("You have some errors in your form, please correct them before going to the next part.");
		}
		else {
			$scope.part += 1;
		}
	}



	$scope.previous = function () {
		$scope.part -= 1;
	}

	$scope.submit = function(event) {
		if (($scope.hasError($scope.news)) || ($scope.hasError($scope.ensl)) || ($scope.hasError($scope.runw)) || ($scope.hasError($scope.event)) || ($scope.hasError($scope.child))) {
			alert("You have some errors in your form, please correct them before submitting.");
			event.preventDefault();
			return false;
		}
	}

	$scope.blockEnter = function(keyEvent) {
		if (keyEvent.which === 13) {
			keyEvent.preventDefault();
		}
	}

}]);