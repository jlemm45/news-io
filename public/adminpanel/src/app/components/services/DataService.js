(function () {
    'use strict';

    angular.module('app')
        .service('dataService', [
            '$http',
            dataService
        ]);

    function dataService($http) {

        return {
            getArticlesReadCount: getArticlesReadCount,
            getUserCount: getUserCount
        };

        function getArticlesReadCount() {
            return $http.get('/api/data/articles');
        }

        function getUserCount() {
            return $http.get('/api/data/users-count');
        }
    }
})();
