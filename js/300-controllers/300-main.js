//Split into separate controllers?

app.controller('mainController', ['$scope', 'services', '$location',
  '$routeSegment', '$route', '$rootScope', 'steps',
  function($scope, services, $location, $routeSegment, $route, $rootScope,
    steps) {

    $scope.chain = steps.chain;
    $scope.breadcrumbs = steps.breadcrumbs;

    $scope.selectBreadcrumb = function(breadcrumb) {
      $scope.chain.step = breadcrumb.step;
    };

    $scope.selectRequirement = function(requirement) {
      $scope.chain.requirement = requirement;
      $location.path('/requirements/functionalities');
      $scope.chain.step = 2;
    };

    $scope.selectFunctionality = function(functionality) {
      $scope.chain.functionality = functionality;
      $location.path('/requirements/functionalities/basis');
      $scope.chain.step = 3;
    };

    $scope.selectExample = function(example) {
      $scope.chain.example = example;
      $location.path('/requirements/functionalities/basis/example');
      $scope.chain.step = 4;
    };



    $scope.$watch('chain.step', function(newVal, oldVal) {
      steps.chain = $scope.chain;

      switch ($scope.chain.step) {
        case 1:
          $scope.chain.requirement = $scope.chain.functionality =
            $scope.chain.example = {};
          if (!$scope.allRequirements) {
            services.getRequirements().then(function(data) {
              $scope.allRequirements = data.data;
            });
          }
          services.updateChain(steps.chain);
          break;

        case 2:
          $scope.chain.functionality = $scope.chain.example = {};
          services.getFunctionalities($scope.chain.requirement).then(
            function(data) {
              $scope.allFunctionalities = data.data;
            });
          services.updateChain(steps.chain);
          break;

        case 3:
          $scope.chain.example = {};
          services.getExamples($scope.chain.functionality).then(
            function(data) {
              $scope.allExamples = data.data;
            });
          services.updateChain(steps.chain);
          break;

          case 4:
          services.updateChain(steps.chain);
          break;

        default:
          services.updateChain(steps.chain);
      }

    }, true);

  }
]);
