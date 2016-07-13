(function () {
    'use strict';

    angular.module('app')
        .service('dataService', [
            '$http',
            dataService
        ]);

    function dataService($http) {

        var service = {
            getArticlesReadCount: getArticlesReadCount
        };

        return service;

        function getArticlesReadCount() {
            return $http.get('/api/data/articles');
        }
    }
})();
