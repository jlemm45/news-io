(function () {

    angular
        .module('app')
        .controller('UsersController', [
            'userService', '$q',
            UsersController
        ]);

    function UsersController(userService, $q) {
        var vm = this;

        vm.user = {};

        vm.querySearch = querySearch;
        vm.onUserSelect = onUserSelect;

        function querySearch(query) {

            var deferred = $q.defer();

            userService.searchUsers(query).then(function (resp) {
                deferred.resolve(resp.data.data);
            });

            return deferred.promise;
        }

        function onUserSelect(user) {
            userService.getUserInfo(user.id).then(function(resp) {
                vm.user = resp.data;
            })
        }
    }

})();
