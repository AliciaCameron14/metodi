app.config(['$routeSegmentProvider', '$routeProvider', '$locationProvider' , function ($routeSegmentProvider, $routeProvider, $locationProvider)
    {
        $routeSegmentProvider
      //  .when('/','home'),
            .when('/requirements', 'requirements')
            .when('/requirements/functionalities', 'requirements.functionalities')
            .when('/requirements/functionalities/basis', 'requirements.functionalities.basis')
            .when('/requirements/functionalities/basis/example', 'requirements.functionalities.basis.example')
            .when('/search', 'search')
            .when('/topics', 'topics')

            // .segment('home', {
                // templateUrl: 'index.html'
                // controller: 'mainController'
            // })
        // .segment('start', {
        //   templateUrl: './views/start/step1_requirements.html',
        //     controller: 'mainController'
        // })
            // .within()
            .segment('requirements', {
                templateUrl: './views/start/main.html',
                controller: 'mainController',
                resolve: {
                  chain: function (steps) {
                    return steps.getChainData();
                  },
                  requirements: function (steps){
                    return steps.getRequirements();
                  }
                }
            })
            .within()
            .segment('functionalities', {
                // templateUrl: './views/start/step2_functionalities.html'
                // controller: 'mainController'
            }).within()
            .segment('basis', {
                // templateUrl: './views/start/step3_basis.php',
                // controller: 'mainController'
            }).within()
            .segment('example', {
                // templateUrl: './views/start/step4_example.php',
                // controller: 'mainController'
            })
            .up() .up() .up()//start
            .segment('search', {
                templateUrl: './views/search/search.php',
                controller: 'searchController'
            })
            .segment('topics', {
                templateUrl: './views/topics/topics.php',
                controller: 'topicsController',
                resolve: {
                  words: function(wordle){
                    return wordle.getWords();
                  }
                }
            });
            // .segment('redirect', {
            //   default: true,
            //   templateUrl: '<h1><LADEN.../h1>',
            //   controller: 'redirectController'
            // });
               $routeProvider.otherwise({redirectTo: '/'});
        $locationProvider.html5Mode(true).hashPrefix('!');
}]);
