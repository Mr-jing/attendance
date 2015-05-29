<?php

define('ROUTE_BASE', 'attendance/public');

// 首页
$app->get(ROUTE_BASE . '/', array(
    'as' => 'home',
    'uses' => 'App\Http\Controllers\RecordController@getIndex',
));

// 登录页面
$app->get(ROUTE_BASE . '/login', array(
    'as' => 'login',
    'uses' => 'App\Http\Controllers\PassportController@getLogin',
));

// 登录操作
$app->post(ROUTE_BASE . '/login', array(
    'uses' => 'App\Http\Controllers\PassportController@postLogin',
));

// 退出操作
$app->post(ROUTE_BASE . '/logout', array(
    'uses' => 'App\Http\Controllers\PassportController@postLogout',
));

// 考勤记录页面
$app->get(ROUTE_BASE . '/record/{year:\d{4}}/{month:\d{1,2}}', array(
    'uses' => 'App\Http\Controllers\RecordController@getRecord',
));

// 关于页面
$app->get(ROUTE_BASE . '/about', function () {
    return view('about');
});