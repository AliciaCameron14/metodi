//Split into separate controllers?

app.controller('mainController', ['$scope', '$rootScope', '$route', 'services', '$location', 'steps', 'chain','requirements', '$uibModal', 'FileUploader',
  function($scope, $rootScope, $route, services, $location, steps, chain, requirements, $uibModal, FileUploader) {

$scope.chain = {};
$scope.functionalityTip = "Zie hieronder een lijst met functionaliteiten. Een functionaliteiten geeft een basisprincipe met concrete voorbeelden/implementaties weer, die je kan gebruiken om aan de bovenliggende requirement tegemoet te komen.";
$scope.basisTip = "Ieder voorbeeld is voor een specifieke doelgroep bedoeld. We onderscheiden de volgende doelgroepen: Ouder, Leerkracht en kind. Kies 'weergeven' om de voorbeelden te tonen.";

$scope.chain = chain;
      $scope.allRequirements = requirements;
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

    $scope.viewImage = function(image) {
      $scope.image = image;
return ($uibModal.open({
  templateUrl: './views/start/image.html',
  scope: $scope,
  windowClass: 'img-modal'

}))
    };

    $scope.editRequirement = function(item) {
    $scope.item = angular.copy(item);
      return ($uibModal.open({
          templateUrl: './views/admin/requirement_edit.html',
          scope: $scope
        }).result.then(function() {
          services.editRequirement($scope.item).then(function(data) {
            if (data) {
              $scope.allRequirements = data.data;
            }
          });
        }));
    }

    $scope.addRequirement = function(){
    $scope.item = {};
      return ($uibModal.open({
          templateUrl: './views/admin/requirement_add.html',
          scope: $scope
        }).result.then(function() {
          services.addRequirement($scope.item).then(function(data) {
            if (data) {
              $scope.allRequirements = data.data;
            }
          });
        }));
    }

    $scope.deleteRequirement = function(item){
      $scope.item = angular.copy(item);

      return ($uibModal.open({
          templateUrl: './views/admin/requirement_delete.html',
          scope: $scope
        }).result.then(function() {
          services.deleteRequirement($scope.item).then(function(data) {
            if (data) {
              $scope.allRequirements = data.data;
            }
          });
        }));
    }

    $scope.editFunctionality = function(item){
    $scope.item = angular.copy(item);
    $scope.item.oldRequirementId = item.requirementId;

      return ($uibModal.open({
          templateUrl: './views/admin/functionality_edit.html',
          scope: $scope
        }).result.then(function() {
          console.log($scope.item);
          services.editFunctionality($scope.item).then(function(data) {
            if (data) {
              $scope.allFunctionalities = data.data;
            }
          });
        }));
    }

    $scope.addFunctionality = function(){
    $scope.item = {};
    $scope.item.requirementId = $scope.chain.requirement.requirementId;
      return ($uibModal.open({
          templateUrl: './views/admin/functionality_add.html',
          scope: $scope
        }).result.then(function() {
          console.log($scope.item);

          services.addFunctionality($scope.item).then(function(data) {
            if (data) {
              $scope.allFunctionalities = data.data;
            }
          });
        }));
    }

    $scope.deleteFunctionality = function(item){
      $scope.item = angular.copy(item);

      return ($uibModal.open({
          templateUrl: './views/admin/functionality_delete.html',
          scope: $scope
        }).result.then(function() {
          services.deleteFunctionality($scope.item).then(function(data) {
            if (data) {
              $scope.allFunctionalities = data.data;
            }
          });
        }));
    }

    $scope.editBasisprincipe = function(item){
    $scope.item = angular.copy(item);

      return ($uibModal.open({
          templateUrl: './views/admin/basisprincipe_edit.html',
          scope: $scope
        }).result.then(function() {
          services.editFunctionality($scope.item).then(function(data) {
            if (data) {
              $scope.allFunctionalities = data.data;
            }
              $scope.chain.functionality =   $scope.item;
          });
        }));
    }

    $scope.editExample = function(item){
      services.getFunctionalities($scope.chain.requirement).then(
        function(data) {
          $scope.allFunctionalities = data.data;
        });
    $scope.item = angular.copy(item);
    $scope.item.oldFunctionalityId = item.functionalityId;
      return ($uibModal.open({
          templateUrl: './views/admin/example_edit.html',
          scope: $scope
        }).result.then(function() {
          services.editExample($scope.item).then(function(data) {
            if (data) {
              $scope.allExamples = data.data;
            }
          });
        }));
    }

    $scope.addExample = function(){
    $scope.item = {};
    $scope.item.requirementId = $scope.chain.requirement.requirementId;
    $scope.item.functionalityId = $scope.chain.functionality.functionalityId;

      return ($uibModal.open({
          templateUrl: './views/admin/example_add.html',
          scope: $scope
        }).result.then(function() {
          console.log($scope.item);

          services.addExample($scope.item).then(function(data) {
            if (data) {
              $scope.allExamples = data.data;
            }
          });
        }));
    }

    $scope.deleteExample = function(item){
      $scope.item = angular.copy(item);

      return ($uibModal.open({
          templateUrl: './views/admin/example_delete.html',
          scope: $scope
        }).result.then(function() {
          services.deleteExample($scope.item).then(function(data) {
            if (data) {
              $scope.allExamples = data.data;
            }
          });
        }));
    }

    $scope.editExampleDesc = function(item){
    $scope.item = angular.copy(item);

      return ($uibModal.open({
          templateUrl: './views/admin/exampleDesc_edit.html',
          scope: $scope
        }).result.then(function() {
          services.editExample($scope.item).then(function(data) {
            if (data) {
              $scope.allExamples = data.data;
            }
              $scope.chain.example =   $scope.item;
          });
        }));
    }

    $scope.addExampleImg = function(item){
      var uploader = $scope.uploader = new FileUploader({
            url: 'upload.php'
        });
// $scope.uploader = new FileUploader();
    $scope.item = angular.copy(item);
    $scope.item.images = [];
    $scope.item.folder = $scope.item.requirementId+$scope.item.functionalityId+$scope.item.exampleId;

    uploader.bind('beforeupload', function (event, item) {
      item.url = $scope.item.requirementId+$scope.item.functionalityId+$scope.item.exampleId;
    });

      return ($uibModal.open({
          templateUrl: './views/admin/exampleImg_add.html',
          scope: $scope
        }).result.then(function() {
$scope.uploader.url = "./content/"+$scope.item.folder;
console.log($scope.uploader.url);

          for (var i = 0; i <  $scope.uploader.queue.length; i++) {
            console.log( $scope.uploader.queue[i]['file']);
            $scope.item.images.push( $scope.uploader.queue[i]['file']);
            $scope.uploader.queue[i]['file']['place'] =   $scope.item.folder;
          }


          services.addExampleImg($scope.item).then(function(data) {
            // if (data) {
            //   $scope.allExamples = data.data;
            // }
            //   $scope.chain.example =   $scope.item;
          });
        }));
    }

    $scope.$watch('chain.step', function(newVal, oldVal) {

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
