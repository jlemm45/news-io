'use strict';

var App = angular.module('newsIO', []);

/**
 * Global site controller
 */
App.controller('main', function($scope,$http) {
    $scope.feeds = {};

    $http.get('/feeds').then(function(data) {
        $scope.feeds = data.data;
    }, function() {
        //
    });
});