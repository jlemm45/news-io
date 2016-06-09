'use strict';

var App = angular.module('newsIO', ['article']);

/**
 * Global site controller
 */
App.controller('main', function($scope,$http) {

    $scope.feeds = [];

    $scope.lastFeedID = 43;
    $scope.sidebarToggle = false;

    $scope.articleFilter = false;

    $scope.getArticles = function(page) {
        var endpoint = '/articles';
        if(page) endpoint+='?start='+$scope.lastFeedID;
        $http.get(endpoint).then(function(data) {
            $scope.feeds = $scope.feeds.concat(data.data);
            $scope.lastFeedID = data.data[data.data.length - 1].id;
        }, function() {
            //
        });
    };

    $scope.toggleSidebar = function() {
        $scope.sidebarToggle = $scope.sidebarToggle ? false : true;
    };

    $scope.filterArticles = function(id) {
        $scope.articleFilter = id;
    };

    $scope.getArticles(false);
});