<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/Menus' => [
            [['_route' => 'getAllMenus', '_controller' => 'App\\Menus\\MenusController\\MenuController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createMenu', '_controller' => 'App\\Menus\\MenusController\\MenuController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Products' => [
            [['_route' => 'getAllProducts', '_controller' => 'App\\Products\\ProductsController\\ProductsController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createProduct', '_controller' => 'App\\Products\\ProductsController\\ProductsController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Reservations' => [
            [['_route' => 'getAllReservations', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'createReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Restaurants' => [
            [['_route' => 'restaurant_list', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::FindAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'restaurant_create', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/Restaurants/authenticate' => [[['_route' => 'restaurant_authenticate', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Authenticate'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/Menus/([^/]++)(?'
                    .'|(*:25)'
                .')'
                .'|/Products/([^/]++)(?'
                    .'|(*:54)'
                .')'
                .'|/Res(?'
                    .'|ervations/([^/]++)(?'
                        .'|(*:90)'
                    .')'
                    .'|taurants/(\\d+)(?'
                        .'|(*:115)'
                    .')'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:153)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        25 => [
            [['_route' => 'getMenuById', '_controller' => 'App\\Menus\\MenusController\\MenuController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateMenu', '_controller' => 'App\\Menus\\MenusController\\MenuController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteMenu', '_controller' => 'App\\Menus\\MenusController\\MenuController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        54 => [
            [['_route' => 'getProductById', '_controller' => 'App\\Products\\ProductsController\\ProductsController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateProduct', '_controller' => 'App\\Products\\ProductsController\\ProductsController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteProduct', '_controller' => 'App\\Products\\ProductsController\\ProductsController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        90 => [
            [['_route' => 'getReservationById', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteReservation', '_controller' => 'App\\Reservations\\ReservationController\\ReservationController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        115 => [
            [['_route' => 'restaurant_show', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'restaurant_update', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'restaurant_delete', '_controller' => 'App\\Restaurants\\RestaurantController\\RestaurantController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        153 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
