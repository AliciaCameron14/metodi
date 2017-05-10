app.config(['$routeSegmentProvider', '$routeProvider', '$locationProvider' , function ($routeSegmentProvider, $routeProvider, $locationProvider)
    {
        $routeSegmentProvider
            .when('/requirements', 'requirements')
            .when('/requirements/functionalities', 'requirements.functionalities')
            .when('/requirements/functionalities/basis', 'requirements.functionalities.basis')
            .when('/requirements/functionalities/basis/example', 'requirements.functionalities.basis.example')
            .when('/search', 'search')
            .when('/topics', 'topics')

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
                // templateUrl:
                // controller:
            }).within()
            .segment('basis', {
                // templateUrl:
                // controller:
            }).within()
            .segment('example', {
                // templateUrl:
                // controller:
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
               $routeProvider.otherwise({redirectTo: '/'});
        $locationProvider.html5Mode(true).hashPrefix('!');
}]);
