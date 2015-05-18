<?php

define('DEBUG', true);

if (defined('DEBUG') && constant('DEBUG')) {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
}
ini_set('error_reporting', -1);
ini_set('log_errors', 'On');

date_default_timezone_set('Asia/Shanghai');

define('DS', DIRECTORY_SEPARATOR);

require 'vendor' . DS . 'autoload.php';

$database = array(
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'test',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule = new Capsule;

$capsule->addConnection($database);


$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();

$capsule->bootEloquent();
