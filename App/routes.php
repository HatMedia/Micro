<?php

$app->get('/dashboard', function (Silex\Application $app){
	$output = 'dboard';
    return $output;
});

$app->get('/dashboard/login', function (Silex\Application $app){
	$output = 'Login';
    return $output;
});

$app->get('/{page}', function (Silex\Application $app){
	$post = $app['db']->fetchAssoc('SELECT * FROM pages');
	return($post['name']);
});

