/**
 * Created by jefferson on 15/11/2015.
 */

angular.module('starter.controllers', [])
    .controller('LoginCtrl', ['$scope', '$http', '$state', 'OAuth', 'OAuthToken',
        function ($scope, $http, $state, OAuth, OAuthToken) {
            $scope.login = function (data) {
                OAuth.getAccessToken(data).then(function () {
                        $state.go('tabs.orders');
                        // console.log(OAuthToken.getToken());
                    }, function (data) {
                        $scope.error_login = 'Usuário ou senha inválidos.';
                    }
                );
            }
        }
    ])

    .controller('OrdersCtrl', ['$scope', '$http', '$state',
        function ($scope, $http, $state) {
            $scope.getOrders = function () {
                $http.get('http://api.pedidos.dev/orders').then(
                    function (data) {
                        $scope.orders = data.data._embedded.orders;
                        console.log($scope.orders);
                    }
                )
            };
            $scope.show = function (order) {
                $state.go('tabs.show', {id: order.id});
            };

            $scope.doRefresh = function () {
                $scope.getOrders();
                $scope.$broadcast('scroll.refreshComplete');
            };

            $scope.onOrderDelete = function (order) {
                $http.delete('http://api.pedidos.dev/orders/' + order.id).then(
                    function success(response) {
                        $scope.getOrders();
                    },
                    function failure(response) {
                        console.log('failed deleting order');
                    }
                )
            };

            $scope.getOrders();

        }
    ])

    .controller('OrderShowCtrl', ['$scope', '$http', '$stateParams',
        function ($scope, $http, $stateParams) {
            $scope.getOrder = function () {
                $http.get('http://api.pedidos.dev/orders/' + $stateParams.id).then(
                    function (data) {
                        $scope.data = data.data;
                    }
                )
            }
            $scope.getOrder();
        }
    ])

    .controller('OrdersAddCtrl', ['$scope', '$http', '$state',
        function ($scope, $http, $state) {
            $scope.clients = [];
            $scope.ptypes = [];
            $scope.products = [];
            $scope.statusList = ['Pendente', 'Processando', 'Entregue'];

            $scope.resetOrder = function () {
                $scope.order = {
                    client_id: '',
                    ptype_id: '',
                    item: []
                };
            };
            $scope.getClients = function () {
                $http.get('http://api.pedidos.dev/clients').then(
                    function (data) {
                        $scope.clients = data.data._embedded.clients;
                    }
                )
            };
            $scope.getPtypes = function () {
                $http.get('http://api.pedidos.dev/ptypes').then(
                    function (data) {
                        $scope.ptypes = data.data._embedded.ptypes;
                    }
                )
            };
            $scope.getProducts = function () {
                $http.get('http://api.pedidos.dev/products').then(
                    function (data) {
                        $scope.products = data.data._embedded.products;
                    }
                )
            };

            $scope.setPrice = function (index) {
                var product_id = $scope.order.item[index].product_id;
                for (var i in $scope.products) {
                    if ($scope.products.hasOwnProperty(i) && $scope.products[i].id == product_id) {
                        $scope.order.item[index].price = $scope.products[i].price;
                        break;
                    }
                }
                $scope.calculateTotalRow(index);
            };

            $scope.addItem = function () {
                $scope.order.item.push({
                    product_id: '',
                    quantity: '',
                    price: 0,
                    total: 0
                })
            };

            $scope.calculateTotalRow = function (index) {
                $scope.order.item[index].total = $scope.order.item[index].quantity * $scope.order.item[index].price;
                calculateTotal();
            }

            calculateTotal = function () {
                $scope.order.total = 0;
                for (var i in $scope.order.item) {
                    if ($scope.order.item.hasOwnProperty(i)) {
                        $scope.order.total += $scope.order.item[i].total;
                    }
                }
            }

            $scope.save = function () {
                $http.post('http://api.pedidos.dev/orders', $scope.order).then(
                    function (data) {
                        $scope.resetOrder();
                        $state.go('tabs.orders');
                    }
                )
            }

            $scope.resetOrder();
            $scope.getClients();
            $scope.getPtypes();
            $scope.getProducts();
        }
    ])

    .controller('LogoutCtrl', ['$scope', 'OAuthToken', '$state', '$ionicHistory',
        function ($scope, OAuthToken, $state, $ionicHistory) {
            $scope.logout = function () {
                OAuthToken.removeToken();
                $ionicHistory.clearCache();
                $ionicHistory.clearHistory();
                $ionicHistory.nextViewOptions({
                    disabledBack: true,
                    historyRoot: true
                })
                $state.go('login');
            }
        }
    ])

    .controller('RefreshModalCtrl', ['$rootScope', '$scope', 'OAuth', 'authService', '$timeout', function ($rootScope, $scope, OAuth, authService, $timeout) {

        function destroyModal() {
            if ($rootScope.modal) {
                $rootScope.modal.hide();
                $rootScope.modal = false;
            }
        }

        $scope.$on('event:auth-loginConfirmed', function () {
            destroyModal();
        });

        OAuth.getRefreshToken().then(function () {
            $timeout(function () {
                authService.loginConfirmed();
            }, 1000)
        }, function () {

        });

    }]);