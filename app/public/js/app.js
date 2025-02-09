var app = angular.module('vehicleApp', []);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

app.controller('VehicleController', function($scope, $http) {
    console.log('Kontroler VehicleController działa!');

    $http.get('http://localhost:1102/api/vehicle')
        .then(function(response) {
            $scope.vehicles = response.data.items;
            $scope.errorMessage = 'Nie można pobrać listy pojazdów';
        })
        .catch(function(error) {
            console.error('Błąd podczas pobierania danych:', error);
        });
});

app.controller('GetCodeController', function($scope, $http) {

    $scope.customerData = {
        fullName: '',
        city: '1',
        address: '',
    };

    $scope.submitForm = function () {

        var fullName = $scope.customerData.fullName;
        var address = $scope.customerData.address;
        var city = $scope.customerData.city;

        $http.post('http://localhost:1102/api/customer', {
            fullName: fullName,
            address: address,
            cityId: city,
        })
            .then(function (response) {
                console.log('API response: ', response.data);

                $scope.result = response.data.success;

                if ($scope.result === false) {
                    $scope.messageFalse = response.data.errorMessage[0];
                    $scope.messageOk = '';
                } else {
                    $scope.messageOk = 'rental code is: ' + response.data.rentalCode;
                    $scope.messageFalse = '';
                }

            })
            .catch(function (error) {
                console.error('Błąd podczas wysyłania danych do customer:', error);
                $scope.messageFalse = error.data.message;
            });
        }
});




