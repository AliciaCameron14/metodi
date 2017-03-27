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
      $scope.chain.example.path = example.requirementId+example.functionalityId+example.exampleId;
      $location.path('/requirements/functionalities/basis/example');
      $scope.chain.step = 4;
    };

    $scope.viewImage = function(image) {
      $scope.image = image;
        $scope.imagePath = $scope.chain.example.requirementId+$scope.chain.example.functionalityId+$scope.chain.example.exampleId;
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

    $scope.item = angular.copy(item);
    $scope.item.folder = $scope.item.requirementId+$scope.item.functionalityId+$scope.item.exampleId;

    var uploader = $scope.uploader = new FileUploader({
          url: 'services/addExampleImg',
          formData: [{
            path: $scope.item.folder,
          exampleId: $scope.item.exampleId,
        functionalityId: $scope.item.functionalityId,
        requirementId: $scope.item.requirementId
       }]
      });


      return ($uibModal.open({
          templateUrl: './views/admin/exampleImg_add.html',
          scope: $scope
        }).result.then(function() {

          services.getExamples($scope.chain.functionality).then(
            function(data) {
              $scope.allExamples = data.data;
            });

          services.getExample($scope.item).then(
            function(data) {
              $scope.chain.example = data.data[0];
              $scope.chain.example.path = $scope.item.folder;
            });
        }));
    }

    $scope.deleteExampleImg = function(item) {
      $scope.item = angular.copy(item);
      $scope.images = $scope.item.screenshot;
        $scope.item.path = $scope.chain.example.path = $scope.item.requirementId+$scope.item.functionalityId+$scope.item.exampleId;
        $scope.imagesToRemove = [];


      return ($uibModal.open({
          templateUrl: './views/admin/exampleImg_delete.html',
          scope: $scope
        }).result.then(function() {

          $scope.item.imagesToRemove = $scope.imagesToRemove;

          services.deleteExampleImg($scope.item).then(
            function() {
              services.getExample($scope.item).then(
                function(data) {
                  $scope.chain.example = data.data[0];
                  $scope.chain.example.path = $scope.item.path;
                });
            });
        }));
$route.reload();
    }

    $scope.deleteImg = function(img) {
      for (var i = 0; i < $scope.images.length; i++) {
        if (  $scope.images[i] == img) {
          $scope.images.splice(i, 1);
        }
      }
          $scope.imagesToRemove.push(img);
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
