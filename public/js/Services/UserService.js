'use strict';

(function(angular) {
    angular.module('snugfeed.service.user', [])
        .service('snugfeedUserService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

            var loginUser = function (login) {
                return $http.post('/auth/login', login);
            };

            var registerUser = function (register) {
                return $http.post('/auth/register', register);
            };

            var getUserStatus = function () {
                return $http.get('/auth/status');
            };

            var updatePassword = function (data) {
                return $http.put('/api/user', data);
            };

            return {
                loginUser: loginUser,
                registerUser: registerUser,
                getUserStatus: getUserStatus,
                updatePassword: updatePassword
            };

        }]);
})(angular);