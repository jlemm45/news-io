'use strict';

(function(angular) {
    angular.module('snugfeed.service.feeds', [])
        .service('snugfeedFeedsService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

            var getFeeds = function () {
                return $http.get("/api/feed");
            };

            var getActiveFeeds = function() {
                return $http.get("/api/feed?active");
            };

            var getUnusedFeeds = function () {
                return $http.get("/api/feed?unused");
            };

            var addFeed = function (data) {
                return $http.post("/api/feed", data);
            };

            var removeFeeds = function (ids) {
                return $http.delete("/api/feed", {ids: ids});
            };

            var updateFeeds = function (feeds) {
                return $http.put("/api/feeds", feeds);
            };

            return {
                getFeeds: getFeeds,
                updateFeeds: updateFeeds,
                addFeed: addFeed,
                getUnusedFeeds: getUnusedFeeds,
                getActiveFeeds: getActiveFeeds,
                removeFeeds: removeFeeds
            };

        }]);
})(angular);