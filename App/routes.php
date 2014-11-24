<?php

$app->get('/dashboard', function (Silex\Application $app){
	$output = 'dboard';
    return $output;
});

$app->get('/dashboard/login', function (Silex\Application $app){
	$output = 'Login';
    return $output;
});


// todo redirect this to default homepage function
$app->get('/',function() use($app){return false;});


// todo split up controllers
$app->get('/{slug}', function($slug) use($app, $pages) {
	return(print_r($pages->load($slug)));
});

