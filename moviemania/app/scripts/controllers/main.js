'use strict';

/**
 * @ngdoc function
 * @name moviemaniaApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the moviemaniaApp
 */
angular.module('moviemaniaApp')
  .controller('MainCtrl', function ($scope) {
    var movieList = [{
      title: 'Ten Commandments',
      image: 'http://localhost/images/The Silmarillion.JPG',
      description: 'An epic production starring Charlton Heston that chronicles the beginnngs of the Israelites. It includes a cast of hundreds with the main stars are Yul Brynner as Pharaoh, and a lot of other folks most of whom' +
      ' are deceased. Cecil B. DeMiles was the director of this great film, which is yet to be surpassed in scope and power. Other latecomers have tried, but even though the technologies employed are advanced ' +
      'they lack the same impact and authenticty. Even the Red Sea parting scene, while hokey by todays special effect standards, is still breathtaking.'
    },
      {
        title: 'Risen',
        image: 'http://localhost/images/The_Amazng_Spider_Man.jpeg',
        description: 'A true detective story set in the days just after the crucifixion of Jesus. Romulus a roman Centurion is tasked wth the investigation of a missing corpse following a gruesome' +
        ' execution of a local activist alleged to having supernatural powers and who led a band of ragtag followers in a crusade to empower ordinary people to rise up and overthrow the yoke of' +
        'Roman bondage. Unfortunately, the true nature of this revolution was misunderstood'
      },
      {
        title: 'Ivanhoe',
        image: 'http://localhost/images/Thor.jpg',
        description: 'Robert Taylor and Elizabeth Taylor star in this powerful retelling of Sir Walter Scotts epic novel of a knight who rescues a jewish maiden from certain death'
      }
    ];
    $scope.movies = movieList;
    $scope.newMovieTitle = '';
    $scope.newMovieDescription = '';
    $scope.newMovieImage = 'http://localhost/server/php/files/blank/jpg';
    $scope.validateTitle = function () {
      if ($scope.newMovieTitle.length > 0) {
        console.debug($scope.newMovieTitle);
      } else {
        window.alert('Movie title is required');
      }
    };
    $scope.addMovie = function () {
      var movie = {
        title: $scope.newMovieTitle,
        category: $scope.newMovieCategory,
        image: $scope.newMovieImage,
        description: $scope.newMovieDescription
      };
      $scope.movies.push(movie);
    };
    $scope.checkCategorySelected = function () {
      if ($scope.newMovieCategory === '') {
        window.alert('Category cannot be empty');
      }
    };
    $scope.checkDescription = function () {
      console.debug($scope.newMovieDescription);
    };
  })
  .controller('SubCtrl', function($scope) {
    $scope.title = 'Movies I love';
});

