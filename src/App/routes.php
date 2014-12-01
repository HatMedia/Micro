<?php
use Symfony\Component\HttpFoundation\Request;

include('controllers/load.php');


// admin page
$app->get('/'.$app['config']['system']['panel'],$render['admin']['home']);
$app->get('/'.$app['config']['system']['panel'].'/home',$render['admin']['home']);
$app->get('/'.$app['config']['system']['panel'].'/users',$render['admin']['users']);

// login page
$app->get('/login', function(Request $request) use ($app) {
   return $app['twig']->render('system/views/login.html', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
	   	'path' => 'http://localhost/micro/Micro/src/themes/system'
    ));
});

$app->get('/', $render['default']['main'](false));
$app->get('/{slug}', $render['default']['main']);
