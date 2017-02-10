app.controller('appController', ['$scope', '$rootScope', '$routeSegment',
  '$location', 'services', '$route', 'steps',
  function($scope, $rootScope, $routeSegment, $location, services, $route,
    steps) {

    services.getCurrentUser().then(function(data) {

      if (data.data) {
        $rootScope.user = data.data;
        $rootScope.userLoggedIn = true;

        services.getChain().then(function(chain) {
          if (chain.data) {
            steps.chain = chain.data;
          } else {
            $scope.chain = steps.chain = {
              step: {},
              requirement: {},
              functionality: {},
              example: {}
            };
          }
        });

      } else {
        $rootScope.user = {};
        $rootScope.userLoggedIn = false;
        $location.path('/');
      }
    });


    $scope.logout = function() {
      services.clearCurrentUser().then(function() {
        $rootScope.user = {};
        $scope.user = {};
        $rootScope.userLoggedIn = false;
        $location.path('/');
      });
    };

    //In a way, the breadcrumbs are "one step behind"
    steps.breadcrumbs = [{
      //on Functionaliteit page
      url: 'requirements',
      label: 'Requirement',
      step: 1
    }, {
      //on Basis page
      url: 'requirements/functionalities',
      label: 'Functionaliteit',
      step: 2
    }, {
      //on Voorbeeld page
      url: 'requirements/functionalities/basis',
      label: 'Voorbeeld',
      step: 3
    }];

    $scope.tabs = [{
      url: 'requirements',
      label: 'Start'
    }, {
      url: 'search',
      label: 'Zoeken'
    }, {
      url: 'topics',
      label: 'Topics'
    }];

    for (i = 0; i < $scope.tabs.length; i++) {
      if ($location.path() == "/" + $scope.tabs[i].url) {
        $scope.selectedTab = $scope.tabs[i];
      }
    }


    $scope.setSelectedTab = function(tab) {
      $scope.chain = steps.chain;
      $scope.chain.step = steps.chain.step;

      if (tab.url == "requirements") {
        if (steps.chain.step == 2) {
          tab.url = "requirements/functionalities";
        } else if (steps.chain.step == 3) {
          tab.url = "requirements/functionalities/basis";
        } else if (steps.chain.step == 4) {
          tab.url = "requirements/functionalities/basis/example";

        }
      }
      $scope.selectedTab = tab;
    };

    $scope.tabClass = function(tab) {
      if ($scope.selectedTab == tab) {
        return "active";
      } else return "";
    };

  }
]);
