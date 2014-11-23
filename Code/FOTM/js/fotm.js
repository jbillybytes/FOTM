'use strict';

var fotmApp = angular.module('fotmApp', ['fotmForm', 'fotmSearch', 'fotmTranscr']);

// Module for crowdsourcing page
var fotmForm = angular.module('fotmForm', []);

fotmForm.controller('formCtrl', ['$scope', function($scope) {

	// Fields in the first part of the form
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

	// Fields in the second part of the form
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

	// Fields in the third part of the form
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
		}
	};

	// Fields in the fourth part of the form
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

	// Fields in the last (fifth) part of the form
	$scope.child = {
		number : {
			check : function() {
				$scope.isNumber($scope.child.number);
			},
			error : false
		},
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
		if ((/^\d+$/.test(name.input)) || name.input === "" || name.input === undefined) {
			name.error = false;
		}
		else {
			name.error = true;
		}
	}

	$scope.isFloat = function(name) {
		if ((/^\d+(\.\d+)?$/.test(name.input)) || name.input === "" || name.input === undefined) {
			name.error = false;
		}
		else {
			name.error = true;
		}
	}

	$scope.isDate = function(input) {
		return /^(0\d|1[0-2])\/([0-2]\d|3[01])\/\d{4}$/.test(input);
	}

	$scope.isUrl = function(input) {
		return (/^(https?:\/\/|www\.)/.test(input)) && !(/\s/.test(input));
	}

	// Part of the form
	$scope.part = 1;

	// Checks for errors in any of the fields
	$scope.hasError = function(formPart) {
		for (var index in formPart) {
			if (formPart[index].error) {
				return true;
			}
		}
		return false;
	}

	// Goes to the next part of the form
	// It also checks for errors , and prevent the user from going to the next part if there are any errors
	// If there is no error, it updates the completion of the form, but only on first time (when the user clicks on previous and then next, the value won't change)
	$scope.next = function () {
		if (($scope.hasError($scope.news)) || ($scope.hasError($scope.ensl)) || ($scope.hasError($scope.runw)) || ($scope.hasError($scope.event)) || ($scope.hasError($scope.child))) {
			alert("You have some errors in your form, please correct them before going to the next part.");
		}
		else {
			$scope.progressOnNext($scope.part);
			$scope.part += 1;
		}
	}

	// Goes to the previous part of the form
	$scope.previous = function () {
		if ($scope.part != 1){
			$scope.progressOnPrevious($scope.part);
			$scope.part -= 1;
		}
	}

	// Prevent the user from submitting the form when pressing the "Enter" key
	// Without this function, the user may submit the form by pressing this key inadvertently
	$scope.blockEnter = function(keyEvent) {
		if (keyEvent.which === 13) {
			keyEvent.preventDefault();
		}
	}

	// Function called when the user goes to the next part
	$scope.progressOnNext = function(n) {
		switch (n) {
			case 1:
				$("#1").attr("class", "progtrckr-done");
				break;
			case 2:
				$("#2").attr("class", "progtrckr-done");
				break;
			case 3:
				$("#3").attr("class", "progtrckr-done");
				break;
			case 4:
				$("#4").attr("class", "progtrckr-done");
				break;
		}
	}

	// Function called when the user goes to the previous part
	$scope.progressOnPrevious = function(n) {
		switch (n) {
			case 2:
				$("#1").attr("class", "progtrckr-todo");
				break;
			case 3:
				$("#2").attr("class", "progtrckr-todo");
				break;
			case 4:
				$("#3").attr("class", "progtrckr-todo");
				break;
			case 5:
				$("#4").attr("class", "progtrckr-todo");
				break;
		}
	}

	// Function called on the submission of the form, when the user click on "Back to transcriber" or "Submit"
	// First, it assigns the right action to the form
	// Then, it checks if any error is present in the form, and if this is the case, displays a message and prevent the user from submitting
	$scope.submitForm = function(action) {
		$("#form").attr('action', action);
		if (($scope.hasError($scope.news)) || ($scope.hasError($scope.ensl)) || ($scope.hasError($scope.runw)) || ($scope.hasError($scope.event)) || ($scope.hasError($scope.child))) {
			alert("You have some errors in your form, please correct them before submitting.");
		}
		else {
			$("#form").submit();
		}
	}
}]);

// Module for home page
var fotmSearch = angular.module('fotmSearch', []);

fotmSearch.controller('searchCtrl', ['$scope', '$timeout', function($scope, $timeout) {
	angular.element(document).ready(function() {
		$("#search-form").submit(function() {
			if($("#tags-input").val()=="") {
				$("#tags-input").remove();
			}
		});
		$timeout($scope.fade_out, 3000);
	});

	$scope.fade_out = function() {
		$("#form-success").fadeOut("slow");
		$("#form-failure").fadeOut("slow", function() {
			window.location = 'home.php';
		});
	}
}]);

// Module for transcriber page
var fotmTranscr = angular.module('fotmTranscr', []);

fotmTranscr.controller('transcrCtrl', ['$scope', '$timeout', function($scope, $timeout) {
	angular.element(document).ready(function() {
		$timeout($scope.fade_out, 3000);
	});

	$scope.fade_out = function() {
		$("#form-success").fadeOut("slow");
		$("#form-failure").fadeOut("slow", function() {
			window.location = 'transcriber.php';
		});
	}
}]);