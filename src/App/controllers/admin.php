<?php
use Symfony\Component\HttpFoundation\Request;

$render['admin']['home'] = function() use ($app) {
	return $app['twig']->render('system/views/home.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system'
	));
};

$render['admin']['users'] = function() use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system',
		'users' => $app['users']->getUsers()
	));
};

$render['admin']['users_add'] =  function(Request $request) use ($app) {

	    // some default data for when the form is displayed the first time
    $data = array(
        'username' => 'Username',
        'password' => 'password',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('username')
        ->add('password', 'password')
        ->add('classes', 'choice', array(
            'choices' => array('ROLE_ADMIN' => 'admin', 'ROLE_USER' => 'user'),
            'expanded' => false,
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
		if($app['users']->insertUser($data['username'],$data['password'],$data['classes'])):
        	return $app->redirect('../users');
		endif;
		return false;
    }


	return $app['twig']->render('system/views/users_new.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system',
		'form' => $form->createView()
	));
};

$render['admin']['users_insert'] = function(Request $request) use ($app) {
   return $app['twig']->render('system/views/users_new.html', array(
        'request' => $request,
	   	'path' => 'http://localhost/micro/Micro/src/themes/system'
    ));
};


$render['admin']['users_filter']= function($filter) use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system',
		'users' => $app['users']->getUsersFrom($filter)
	));
};