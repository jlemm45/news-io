'use strict';

(function(angular) {
    var snugwelcome = angular.module('snug-welcome', ['snugfeed.service.feeds', 'ngCookies', 'managefeedscomponent', 'dropdown']);

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
            _.each(feeds, function(v, k) {
                if(v.active) selected.push(v);
            });
            $cookies.putObject('feeds', selected);
            window.location = '/feeds'
        };

    });
})(angular);