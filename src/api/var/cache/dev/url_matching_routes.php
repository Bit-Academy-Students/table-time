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
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/Customers/([^/]++)(?'
                    .'|(*:29)'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:65)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        29 => [
            [['_route' => 'getCustomerById', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::FindById'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateCustomer', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::Update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'deleteCustomer', '_controller' => 'App\\Customers\\CustomerController\\CustomerController::Delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        65 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
