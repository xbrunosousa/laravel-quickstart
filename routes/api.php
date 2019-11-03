<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {

    $api->group(['prefix' => 'user'], function () use ($api) {
        $api->post('', 'UserController@store');
        $api->group(['middleware' => 'jwt.auth'], function () use ($api) {
            $api->put('', 'UserController@update');
            $api->get('', 'UserController@my');
        });
        $api->post('/verify/{code}', 'UserController@verify');
        $api->group(['prefix' => 'auth'], function () use ($api) {
            $api->post('', 'UserController@login');
            $api->put('/forgot', 'UserController@requestPasswordReset');
            $api->put('/reset_pwd', 'UserController@resetPwd');
        });
    });
});
