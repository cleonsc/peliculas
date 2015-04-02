 var miAplicacion = angular.module('peliculas', ['ngRoute']);

miAplicacion.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/accion', {
        templateUrl: 'views/accion.html',
        controller: 'accionCtrl'
      }).
      when('/romanticas', {
        templateUrl: 'views/romanticas.html',
        controller: 'romanticasCtrl'
      }).
        when('/humor', {
        templateUrl: 'views/humor.html',
        controller: 'humorCtrl'
      }).
        when('/documentales', {
        templateUrl: 'views/documentales.html',
        controller: 'documentalesCtrl'
      }).
        when('/cienciaFiccion', {
        templateUrl: 'views/cienciaFiccion.html',
        controller: 'cienciaFiccionCtrl'
      }).
        when('/home', {
        templateUrl: 'views/home.html',
        controller: 'MainController'
      }).
        when('/terror', {
        templateUrl: 'views/terror.html',
        controller: 'terrorCtrl'
      }).
      otherwise({
        redirectTo: '/home'
      }); 
  }]);


