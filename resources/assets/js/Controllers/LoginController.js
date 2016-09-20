'use strict';

(function(angular) {
    var snuglogin = angular.module('snug-login', ['logincomponent']);

    /**
     * Login Controller
     */
    snuglogin.controller('loginController', function($scope) {

        $scope.$on('login success', function() {
            window.location = '/feeds';
        });

    });
})(angular);