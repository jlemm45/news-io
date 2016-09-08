(function () {
    'use strict';

    angular.module('app')
        .service('userService', [
            '$http',
            userService
        ]);

    function userService($http) {

        return {
            searchUsers: searchUsers,
            getUserInfo: getUserInfo
        };

        function searchUsers(name) {
            return $http.get('/api/data/users?name='+name);
        }

        function getUserInfo(id) {
            return $http.get('/api/data/users/'+id);
        }
    }
})();
