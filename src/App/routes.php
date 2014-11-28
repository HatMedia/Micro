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
	$page = $pages->load($slug);
	$tpl = 'default'.'/'.$page['template'].'.'.$app['config']['template']['extension'];



	$config['app'] = $app;
	$config['page'] = $pages->config;
	$config['path'] = 'http://localhost/micro/Micro/src/themes/default';
	$config['posts'] = $pages->getPagePosts($page['id']);


	$template = $app['twig']->loadTemplate($tpl);
	return $template->render($config);

});
