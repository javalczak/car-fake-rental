var app = angular.module('vehicleApp', []);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

app.controller('VehicleController', function($scope, $http) {

    $http.get('http://localhost:1102/api/vehicle')
        .then(function(response) {
            $scope.vehicles = response.data.items;
        })
        .catch(function(error) {
            console.error('Błąd podczas pobierania danych:', error);
            $scope.errorMessage = 'Nie można pobrać listy pojazdów';
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

app.controller('rentVehicleController', function($scope, $http) {

    $scope.submitFormRent = function () {

        var inputElement = document.getElementById("vehicleId");
        var vehicleId = inputElement.getAttribute("title");
        var rentalCode = $scope.rentalData.rentalCode;

        $http.post('http://localhost:1102/api/rent', {
            rentalCode: rentalCode,
            vehicleId: vehicleId,
        })

            .then(function (response) {
                console.log('API response: ', response.data);
                $scope.result = response.data.success;

                if ($scope.result === false) {
                    $scope.messageFalse = response.data.errorMessage[0];
                    $scope.messageOk = '';
                } else {
                    $scope.messageOk = response.data.message;
                    $scope.messageFalse = '';
                }
            })
            .catch(function (error) {
                console.error('Błąd podczas wysyłania danych do rent:', error);
                $scope.messageFalse = error.data.message;
            });
    }
});

app.controller('releaseVehicleController', function($scope, $http) {

    $scope.submitFormRent = function () {

        var inputElement = document.getElementById("vehicleId");
        var vehicleId = inputElement.getAttribute("title");
        var rentalCode = $scope.rentalData.rentalCode;

        $http.post('http://localhost:1102/api/release', {
            rentalCode: rentalCode,
            vehicleId: vehicleId,
        })

            .then(function (response) {
                console.log('API response: ', response.data);
                $scope.result = response.data.success;

                if ($scope.result === false) {
                    $scope.messageFalse = response.data.errorMessage[0];
                    $scope.messageOk = '';
                } else {
                    $scope.messageOk = response.data.message;
                    $scope.messageFalse = '';
                }
            })
            .catch(function (error) {
                console.error('Błąd podczas wysyłania danych do rent:', error);
                $scope.messageFalse = error.data.message;
            });
    }
});




