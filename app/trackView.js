angular.module('gpxiesApp')
    .controller('trackView',
        ['$scope', '$http',
            function ($scope, $http) {
                $scope.test = 'TesT';
                $scope.trackList = [];

                $http.get('data/tracks.json').then(function (response) {
                    $scope.trackList = response.data;
                    $scope.showTrack1 = function (filename) {
                        console.log ('its showTrack in trackView');
                        showTrack(filename);
                    }
                }
                )
            }]);

