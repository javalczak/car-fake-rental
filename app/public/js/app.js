var app = angular.module('vehicleApp', []);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

app.controller('VehicleController', function($scope, $http) {

    console.log('Kontroler działa!');
    $scope.title = 'wynajem pojazdów - lista';

        $http.get('http://localhost:1102/api/vehicle')
            .then(function(response) {

                $scope.vehicles = response.data.items;
                console.log('Dane pojazdów:', $scope.vehicles);
            })
            .catch(function(error) {
                console.error('Błąd podczas pobierania danych:', error);
            });
});

