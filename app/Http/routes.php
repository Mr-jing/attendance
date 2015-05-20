<?php

define('ROUTE_BASE', 'att/public');

$app->get(ROUTE_BASE . '/index', function () use ($app) {
    return $app->welcome();
});
