<?php

require 'start.php';

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('BASE_URL', 'http://10.0.119.104/a');
define('PUB_URL', BASE_URL . '/public');
define('VIEW_PATH', APP_PATH . '/views');

use NoahBuscher\Macaw\Macaw;

Macaw::get('/a/', 'App\Controllers\RecordController@getRecord');
Macaw::get('/a/login', 'App\Controllers\PassportController@getLogin');
Macaw::post('/a/login', 'App\Controllers\PassportController@postLogin');
Macaw::post('/a/logout', 'App\Controllers\PassportController@postLogout');
Macaw::get('/a/record/([0-9]{4})/([0-9]{1,2})', 'App\Controllers\RecordController@getIndex');
Macaw::get('/a/record', 'App\Controllers\RecordController@getRecord');
Macaw::post('/a/record', 'App\Controllers\RecordController@postStore');
Macaw::get('/a/about', 'App\Controllers\OtherController@getAbout');
//Macaw::post('/a/ajaxMonth', 'App\Controllers\RecordController@postAjaxMonth');

Macaw::dispatch();
