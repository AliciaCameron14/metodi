<?php 
require 'FirePHPCore/fb.php';
    include_once 'system/system.php';
FB::log('Log message');
?>

    <!doctype html>
    <html ng-app="app" lang="en">

    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>MeToDi</title>
        <base href="/metodi/">

        <!-- To Do: Batch load -->
        <!-- Include JS Scripts  -->
        <!-- Libraries-->
        <script src="./js/000-lib/000-jquery-2.2.1.min.js"></script>
        <script src="./js/000-lib/001-jquery-ui-1.12.1.min.js"></script>
        <script src="./js/000-lib/002-bootstrap.min.js"></script>
        <script src="./js/000-lib/003-angular.js"></script>
        <script src="./js/000-lib/004-angular-route.js"></script>
        <script src="./js/000-lib/005-angular-route-segment.min.js"></script>
        <script src="./js/000-lib/006-ui-bootstrap-tpls-2.4.0.js"></script>


        <!-- AngularJS -->
        <script src="./js/100-modules/101-app.js"></script>
        <script src="./js/200-routes/200-routes.js"></script>
        <script src="./js/300-controllers/300-app.js"></script>
        <script src="./js/300-controllers/301-login.js"></script>
        <script src="./js/300-controllers/300-start.js"></script>
        <script src="./js/300-controllers/300-search.js"></script>
        <script src="./js/300-controllers/300-topics.js"></script>




        <!-- Include CSS -->
        <link rel="stylesheet" href="./css/000-jquery-ui.min.css" type="text/css" />
        <link rel="stylesheet" href="./css/010-bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="./css/020-font-awesome.min.css" type="text/css" />
        <link rel="stylesheet" href="./css/900-site.css" type="text/css" />

    </head>

    <body>
        <div class="container-fluid" ng-controller="appController">
            <div class="row">

                <!-- Logged In -->
                <div ng-if="user">
                    <header class="menu col-lg-10 col-lg-offset-1">
                        <div style="" class="col-lg-9">
                            <i class="fa fa-circle fa-4x" aria-hidden="true"></i>
                            <h1 style="display:inline;">METODI</h1>
                        </div>

                        <div style="" class="col-lg-3">
                            <div style="float:right;" class="dropdown">
                                <div class="dropdown-toggle" type="button" data-toggle="dropdown"><span class="caret"></span>Username</div>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">Account Settings</a>
                                    </li>
                                    <li>
                                        <a href="#">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>

                    <div style="" class="col-lg-10 col-lg-offset-1">
                        <ul class="text-center nav nav-tabs">
                            <li ng-class="tabClass(tab)" ng-repeat="tab in tabs" tab="tab">
                                <a ng-href={{tab.url}} ng-click="setSelectedTab(tab)">{{tab.label}}</a>
                            </li>
                        </ul>
                        <div class="col-lg-10 col-lg-offset-1" app-view-segment="0"></div>
                    </div>
                </div>

                <!-- else: Show login modal-->
                <a class="btn btn-default" href ng-click="open()">Login</a>
                <a class="btn btn-default" href ng-click="logout()">log out</a>


            </div>
        </div>
    </body>

    </html>