'use strict';

var snugregister = angular.module('snug-register', ['registercomponent']);

snugregister.controller('registerController', function($scope) {
    $scope.$on('register success', function() {
        window.location = '/login';
    });
});