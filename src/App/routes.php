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
$app->get('/{slug}', function($slug) use($app, $pages, $twig) {
	$page = $pages->load($slug);
	$template = 'default'.'/'.$page['template'].'.'.$app['config']['template']['extension'];
	if(file_exists(ROOT.'/themes/'.$template)):
		// load up template file before rendering ...
			 //[ ins line here ]

		return $twig->render($template, array());
	endif;
	return 'Err no tmpl found';
});

