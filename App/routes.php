<?php

$app->get('/dashboard', function (Silex\Application $app){
	$output = 'dboard';
    return $output;
});

$app->get('/dashboard/login', function (Silex\Application $app){
	$output = 'Login';
    return $output;
});

