<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayPal Mode
    |--------------------------------------------------------------------------
    |
    | Modo de operación: 'sandbox' para pruebas, 'live' para producción
    |
    */

    'mode' => env('PAYPAL_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Client ID
    |--------------------------------------------------------------------------
    |
    | ID del cliente de PayPal obtenido desde el dashboard
    |
    */

    'client_id' => env('PAYPAL_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | PayPal Client Secret
    |--------------------------------------------------------------------------
    |
    | Secret del cliente de PayPal obtenido desde el dashboard
    |
    */

    'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | PayPal Currency
    |--------------------------------------------------------------------------
    |
    | Moneda por defecto para las transacciones
    | Opciones comunes: MXN, USD, EUR
    |
    */

    'currency' => env('PAYPAL_CURRENCY', 'MXN'),

];
