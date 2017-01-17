app.controller('appController', ['$scope', '$uibModal', '$rootScope', '$routeSegment', function ($scope, $uibModal, $rootScope, $routeSegment) {



    $scope.open = function () {
        $scope.modal = $uibModal.open({
            templateUrl: './views/login/login.html',
            scope: $scope,
            controller: 'loginController'
        });
    };

    $scope.close = function () {
        $scope.modal.close();
    };

    $scope.logout = function () {
        $rootScope.user = false;
    };

    $scope.tabs = [
        {
            url: 'start/requirements',
            label: 'Start'
        }, {
            url: 'search',
            label: 'Zoeken'
        }, {
            url: 'topics',
            label: 'Topics'
        }
    ];

    $scope.selectedTab = $scope.tabs[0];
    $scope.setSelectedTab = function (tab) {
        $scope.selectedTab = tab;
    };

    $scope.tabClass = function (tab) {
        if ($scope.selectedTab == tab) {
            return "active";
        } else return "";
    };

}]);