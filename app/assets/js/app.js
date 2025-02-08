//
// var app = angular.module('vehicleApp', []);
//
// app.config(function($interpolateProvider) {
//     $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
// });
//
// app.controller('VehicleController', function($scope, $http) {
//
//     console.log('Kontroler VehicleController działa!');
//
//     // vehicle:get
//     $scope.title = 'wynajem pojazdów - lista';
//
//     $http.get('http://localhost:1102/api/vehicle')
//         .then(function(response) {
//
//             $scope.vehicles = response.data.items;
//             console.log('Dane pojazdów:', $scope.vehicles);
//         })
//         .catch(function(error) {
//             console.error('Błąd podczas pobierania danych:', error);
//         });
//
//     $http.post('http://localhost:1102/api/customer', {fullName: 'Marcin', address: 'Bura 15', 'idNumber': 'ASSD', cityId: 155})
//         .then(function(response) {
//             console.log('Odpowiedź z API customer:', response.data);
//             // Możesz dodać coś, co ma się wydarzyć po pomyślnym wysłaniu (np. komunikat dla użytkownika)
//         })
//         .catch(function(error) {
//             console.error('Błąd podczas wysyłania danych do customer:', error);
//         })
//
// });
//
//
// app.controller('GetCodeController', function($scope, $http) {
//
//     console.log('Kontroler get code działa!');
//
//     $http.post('http://localhost:1102/api/customer', {fullName: 'Marcin', address: 'Bura 15', 'idNumber': 'ASSD', cityId: 1})
//         .then(function(response) {
//             console.log('Odpowiedź z API customer:', response.data);
//             // Możesz dodać coś, co ma się wydarzyć po pomyślnym wysłaniu (np. komunikat dla użytkownika)
//         })
//         .catch(function(error) {
//             console.error('Błąd podczas wysyłania danych do customer:', error);
//         });
//
//
// });
//
