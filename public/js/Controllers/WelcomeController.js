'use strict';

var snugwelcome = angular.module('snug-welcome', ['snugfeed.service.feeds', 'ngCookies', 'managefeedscomponent', 'newfeedcomponent']);

/**
 * Welcome Controller
 */
snugwelcome.controller('welcomeController', function($scope,$http,snugfeedFeedsService,$cookies) {

    $scope.loading = false;         //page is loading

    /**
     * Save and submit
     */
    $scope.submit = function(feeds) {
        $scope.loading = true;
        var selected = [];
        $.each(feeds, function(k,v) {
            if(v.active) selected.push(v);
        });
        $cookies.putObject('feeds', selected);
        window.location = '/feeds'
    }

});