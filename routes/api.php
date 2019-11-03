<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {

    $api->group(['prefix' => 'user'], function () use ($api) {
        $api->post('', 'UserController@store');
    });
});
