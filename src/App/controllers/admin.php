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
        	return $app->redirect($app['url_generator']->generate('users'));
		endif;
		return false;
    }


	return $app['twig']->render('system/views/users_new.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system',
		'form' => $form->createView()
	));
};

$render['admin']['users_edit'] =  function($id_list, Request $request) use ($app) {


	$ids = explode(',',str_replace('_','',rtrim($id_list, ",")));

	$userData = $app['users']->getList($ids);
	$data = array();
	foreach($ids as $id):
		$data[$id.'_username'] = $userData[$id]['username'];
		$data[$id.'_classes'] = $userData[$id]['roles'];
	endforeach;
	$form = $app['form.factory']->createBuilder('form', $data);
	foreach($ids as $id):
	$form
		->add($id.'_username')
        ->add($id.'_password', 'password', array(
			'required' => false
		))
        ->add($id.'_classes', 'choice', array(
            'choices' => array('ROLE_ADMIN' => 'admin', 'ROLE_USER' => 'user'),
            'expanded' => false,
        ));
	endforeach;



    $form = $form->getForm();
    $form->handleRequest($request);

    if ($form->isValid()) {
        $postData = $form->getData();
		$data = array();
		foreach($postData as $key => $val):
			$info = explode('_',$key);
			$data[$info[0]][$info[1]] = $val;
		endforeach;

		foreach($data as $key => $val):
			$app['users']->update($id,$val);
		endforeach;
		return $app->redirect($app['url_generator']->generate('users'));
    }


	return $app['twig']->render('system/views/users_edit.html', array(
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

$render['admin']['users_remove']= function($id_list) use ($app) {

	$list = array_filter(explode(',',str_replace('_','',$id_list)));
	$app['users']->removeUsers($list);
	return $app->redirect($app['url_generator']->generate('users'));
};

$render['admin']['users_filter']= function($filter) use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => 'http://localhost/micro/Micro/src/themes/system',
		'users' => $app['users']->getUsersFrom($filter)
	));
};