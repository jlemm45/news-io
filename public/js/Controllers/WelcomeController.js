'use strict';

var snugwelcome = angular.module('snug-welcome', ['snugfeed.service.feeds', 'ngCookies']);

/**
 * Welcome Controller
 */
snugwelcome.controller('welcomeController', function($scope,$http,snugfeedFeedsService,$cookies) {

    $scope.feeds = {};              //hold all selectable feeds
    $scope.loading = false;         //page is loading

    //load feeds on load
    snugfeedFeedsService.getFeeds().then(function(data) {
        $scope.feeds = data.data;
    });

    /**
     * Click on feed
     * @param feed
     */
    $scope.activateFeed = function(feed) {
        feed.active = feed.active ? false : true;
    };

    /**
     * Save and submit
     */
    $scope.submit = function() {
        $scope.loading = true;
        var feeds = [];
        $.each($scope.feeds, function(k,v) {
            if(v.active) feeds.push(v);
        });
        $cookies.putObject('feeds', feeds);
        window.location = '/feeds'
    }

});