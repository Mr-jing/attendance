<?php

define('ROUTE_BASE', 'attendance/public');


$app->get(ROUTE_BASE . '/index', array(
    'as' => 'home',
    'uses' => 'RecordController@getRecord',
));

$app->get(ROUTE_BASE . '/about', array(
    'as' => 'about',
    'uses' => 'OtherController@getAbout',
));

$app->get(ROUTE_BASE . '/login', array(
    'as' => 'login',
    'uses' => 'App\Http\Controllers\PassportController@getLogin',
));

$app->post(ROUTE_BASE . '/login', array(
    'uses' => 'App\Http\Controllers\PassportController@postLogin',
));

$app->post(ROUTE_BASE . '/logout', array(
    'uses' => 'App\Http\Controllers\PassportController@postLogout',
));

$app->get(ROUTE_BASE . '/record', array(
    'as' => 'record',
    'uses' => 'RecordController@getRecord',
));

$app->get(ROUTE_BASE . '/record/([0-9]{4})/([0-9]{1,2})', array(
    'uses' => 'RecordController@getIndex',
));

$app->post(ROUTE_BASE . '/record', array(
    'uses' => 'RecordController@postStore',
));
