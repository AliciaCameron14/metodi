//Split into separate controllers?

app.controller('mainController', ['$scope', 'services', '$location', 'steps', 'chain','requirements', '$uibModal',
  function($scope, services, $location, steps, chain, requirements, $uibModal) {

$scope.chain = {};
$scope.functionalityTip = "Zie hieronder een lijst met functionaliteiten. Een functionaliteiten geeft een basisprincipe met concrete voorbeelden/implementaties weer, die je kan gebruiken om aan de bovenliggende requirement tegemoet te komen.";
$scope.basisTip = "Ieder voorbeeld is voor een specifieke doelgroep bedoeld. We onderscheiden de volgende doelgroepen: Ouder, Leerkracht en kind. Kies 'weergeven' om de voorbeelden te tonen.";

//   chain.getChainData().then(function(data){
//   $scope.chain = data.data;
//   // steps.chain = data.data;
// });
$scope.chain = chain;
      $scope.allRequirements = requirements;
      // services.updateChain($scope.chain);
      // $scope.chain.step = 1;



      // console.log("main!");
      // console.log($scope.chain.step);

    // $scope.chain = steps.chain;
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
      console.log($scope.chain.example);

    };

    $scope.viewImage = function(image) {
      $scope.image = image;
return ($uibModal.open({
  templateUrl: './views/start/image.html',
  scope: $scope,
  windowClass: 'img-modal'

}))
    };



    $scope.$watch('chain.step', function(newVal, oldVal) {
      // if (newVal === oldVal) { //initializing
      //
      //   return;
      // }

      // else
      //steps.chain = $scope.chain;
      switch ($scope.chain.step) {
        case 1:
          $scope.chain.requirement = $scope.chain.functionality =
            $scope.chain.example = {};
          if (!$scope.allRequirements) {
            services.getRequirements().then(function(data) {
              $scope.allRequirements = data.data;
            });
          }
          services.updateChain($scope.chain);
          break;

        case 2:
          $scope.chain.functionality = $scope.chain.example = {};
          services.getFunctionalities($scope.chain.requirement).then(
            function(data) {
              $scope.allFunctionalities = data.data;
            });
          services.updateChain($scope.chain);
          break;

        case 3:
          $scope.chain.example = {};
          services.getExamples($scope.chain.functionality).then(
            function(data) {
              $scope.allExamples = data.data;
            });
          services.updateChain($scope.chain);
          break;

          case 4:
          services.updateChain($scope.chain);
          break;

        default:
          // services.updateChain($scope.chain);
      }

    });

  }
]);
