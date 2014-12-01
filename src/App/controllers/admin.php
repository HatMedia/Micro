<?php

$render['admin']['home'] = function() use ($app) {
	return $app['twig']->render('system/views/home.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system'
	));
};

$render['admin']['users'] = function() use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system'
	));
};
