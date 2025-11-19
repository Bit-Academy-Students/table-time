<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/Customers' => [
            [['_route' => 'getAllCustomers', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createCustomer', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Reservations' => [
            [['_route' => 'getAllReservations', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Restaurants' => [
            [['_route' => 'getAllRestaurants', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createRestaurant', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/Customers/([^/]++)(?'
                    .'|(*:29)'
                .')'
                .'|/Res(?'
                    .'|ervations/([^/]++)(?'
                        .'|(*:65)'
                    .')'
                    .'|taurants/([^/]++)(?'
                        .'|(*:93)'
                    .')'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:130)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        29 => [
            [['_route' => 'getCustomerById', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateCustomer', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteCustomer', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        65 => [
            [['_route' => 'getReservationById', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        93 => [
            [['_route' => 'getRestaurantById', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateRestaurant', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteRestaurant', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        130 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
