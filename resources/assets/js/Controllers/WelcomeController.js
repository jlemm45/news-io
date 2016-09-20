'use strict';

(function(angular) {
    var snugwelcome = angular.module('snug-welcome', ['snugfeed.service.feeds', 'ngCookies', 'togglefeedscomponent', 'dropdown']);

    /**
     * Welcome Controller
     */
    snugwelcome.controller('welcomeController', function($scope,$http,snugfeedFeedsService,$cookies) {

        $scope.loading = false;         //page is loading
        $scope.disabled = true;         //button is disabled
        $scope.feedData = [];

        $scope.$watch('feedData', function(newValue) {
           $scope.disabled = newValue.length == 0;
        });

        /**
         * Save and submit
         */
        $scope.submit = function(feeds) {
            if(typeof feeds !== 'undefined' && feeds.length > 0) {
                $scope.loading = true;
                var selected = [];
                _.each(feeds, function(v, k) {
                    if(v.active) selected.push(v);
                });
                $cookies.putObject('feeds', selected);
                window.location = '/feeds'
            }
        };

    });
})(angular);