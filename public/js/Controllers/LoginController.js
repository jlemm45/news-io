'use strict';

var snuglogin = angular.module('snug-login', ['logincomponent']);

/**
 * Login Controller
 */
snuglogin.controller('loginController', function($scope) {

    $scope.loginSuccess = function() {
        window.location = '/feeds';
    }

});