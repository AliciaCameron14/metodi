app.controller('appController', ['$scope', '$rootScope', '$routeSegment',
  '$location', 'services', '$route', 'steps', 'Idle', '$uibModal',
  function($scope, $rootScope, $routeSegment, $location, services, $route,
    steps, Idle, $uibModal) {


    $scope.getCurrent = function() {
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

          Idle.watch();

        } else {
          $rootScope.user = {};
          $rootScope.userLoggedIn = false;
          $location.path('/');
        }
      });
    }

    $scope.$on('IdleStart', function() {

       $scope.title = "Sessie inactief";
       $scope.message = "Do you wish to continue your session?";
       $scope.btnYes = "Voortzetten";
       $scope.btnNo = "Uitloggen";

       $scope.warning = ($uibModal.open({
      templateUrl: './views/idle-warning.html',
      scope: $scope
    }));
     });

     $scope.$on('IdleTimeout', function(){
       $scope.logout();

       $scope.title = "Sessie verlopen";
       $scope.message = "U werd automatisch afgemeld na 20 minuten van inactiviteit";
       $scope.btnYes = "Inloggen";
       $scope.btnNo = "OK";

if ($scope.warning) {
  $scope.warning.close();
           $scope.warning = null;
}

       return ($uibModal.open({
      templateUrl: './views/idle-warning.html',
      scope: $scope
    }).result.then(function(){
      $location.path('/');
    }));

     });

     $scope.login = function() {
       services.login($scope.user).then(function(data) {
         if (data.data) { //user exists
           $rootScope.user =  $scope.user = data.data;
           $rootScope.userLoggedIn = true;
           $scope.chain = steps.chain = {step: {}, requirement: {}, functionality: {}, example: {}};
        steps.chain.step = 1;
        Idle.watch();

          $location.path('/requirements');

           $scope.selectTab();
         }
       });
     };

    $scope.logout = function() {
      services.clearCurrentUser().then(function() {
        $rootScope.user = {};
        $rootScope.userLoggedIn = false;
        Idle.unwatch();
        $location.path('/');
      });
        $scope.getCurrent();
    };


    $scope.register = function() {

      $scope.item = {};

      return ($uibModal.open({
          templateUrl: './views/login/register.html',
          scope: $scope
        })
        .result.then(function() {
          $scope.item['userType'] = 1;

          services.addNewUser($scope.item).then(function(data) {
            //login new user
          });
        }));
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

$scope.selectTab = function() {
  for (i = 0; i < $scope.tabs.length; i++) {
    if ($location.path() == "/" + $scope.tabs[i].url) {
      $scope.selectedTab = $scope.tabs[i];
      break;
    } if ($location.path().indexOf("functionalities") != (-1)) {
      $scope.selectedTab = $scope.tabs[0];
      break;
    }
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


$scope.selectTab();
$scope.getCurrent();


  }
]);
