angular.module('snugfeed.service.feeds', [])
    .service('snugfeedFeedsService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

        var getFeeds = function () {
            return $http.get("/api/feed");
        };

        var addFeed = function (data) {
            return $http.post("/api/feed", data);
        };

        var updateFeeds = function (feeds) {
            return $http.put("/api/feeds", feeds);
        };

        return {
            getFeeds: getFeeds,
            updateFeeds: updateFeeds,
            addFeed: addFeed
        };

    }]);