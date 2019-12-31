<?php

use Dingo\Api\Routing\Router;

$api = app(Router::class);
$routeConfig = [
    'middleware' => array_merge(config('modules.api_middleware'), ['api.auth']),
    'namespace' => 'Modules\API\$MODULE$\Http\Controllers',
    'prefix' => '/api/common'
];

$api->version('v1', $routeConfig,
    function (Router $api) {
        $api->get('/', 'IndexController@index');
    }
);
