var app = angular.module('vehicleApp', []);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

app.controller('VehicleController', function($scope, $http) {
    console.log('Kontroler VehicleController działa!');

    // vehicle:get
    $scope.title = 'wynajem pojazdów - lista';
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

    $scope.errorMessage = '';
    $scope.message = '';

    console.log('Kontroler GetCodeController działa!');

    $scope.customerData = {
        fullName: '',
        cityId: 1,
        address: '',
    };

    $scope.submitForm = function () {
        console.log('form działa!');

        var fullName = $scope.customerData.fullName;
        var address = $scope.customerData.address;

        $http.post('http://localhost:1102/api/customer', {
            fullName: fullName,
            address: address,
            cityId: 1,

        })
            .then(function (response) {
                console.log('Odpowiedź z API:', response.data);
                $scope.message = response.data.message[1];
                $scope.messageType = response.data.success;
                console.log('Odpowiedź z API:', 'poformatowano');
            })
            .catch(function (error) {
                console.error('Błąd podczas wysyłania danych do customer:', error);
                $scope.errorMessage = response.data.message[1];
                $scope.errorMssageType = response.data.success;
                console.log('Odpowiedź z API:', 'nie wieszlo na pozionie web');
            });
    }

});




