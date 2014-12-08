<?php

include('controllers/load.php');
use Symfony\Component\HttpFoundation\Request;

// admin home
$app->get('/'.$app['config']['system']['panel'],$render['admin']['home']);
$app->get('/'.$app['config']['system']['panel'].'/home',$render['admin']['home'])->bind('home');

// users
$app->get('/'.$app['config']['system']['panel'].'/users',$render['admin']['users'])->bind('users');
$app->match('/'.$app['config']['system']['panel'].'/users/new',$render['admin']['users_add'])->bind('add_user');
$app->get('/'.$app['config']['system']['panel'].'/users/filter/{filter}',$render['admin']['users_filter'])->bind('filter_users');
$app->get('/'.$app['config']['system']['panel'].'/users/remove/{id_list}',$render['admin']['users_remove'])->bind('delete_users');
$app->match('/'.$app['config']['system']['panel'].'/users/edit/{id_list}',$render['admin']['users_edit'])->bind('edit_users');

// pages

// hooks

// settings


// login page
$app->get('/login', function(Request $request) use ($app) {
   return $app['twig']->render('system/views/login.html', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
	   	'path' => 'http://localhost/micro/Micro/src/themes/system'
    ));
});

// cms front
$app->get('/', $render['default']['main'](false)); // should redir this instead of algo this.. [todo]
$app->get('/{slug}', $render['default']['main']);
