angular.module('snugfeed.service.user', [])
    .service('snugfeedUserService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

        var loginUser = function (login) {
            //console.log('test');
            return $http.post('/auth/login', login);
        };

        return {
            loginUser: loginUser
        };

    }]);