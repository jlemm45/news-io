(function () {

    angular
        .module('app')
        .controller('UserPanelController', [
            'dataService',
            UserPanelController
        ]);

    function UserPanelController(dataService) {
        var vm = this;

        vm.userCount = 0;

        dataService.getUserCount().then(function(resp) {
           vm.userCount = resp.data;
        });

    }

})();
