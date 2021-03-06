<?php
use Symfony\Component\HttpFoundation\Request;

$render['admin']['home'] = function() use ($app) {
	return $app['twig']->render('system/views/home.html', array(
		'path' => $app['config']['system']['template']['path']
	));
};

$render['admin']['settings'] = function(Request $request) use ($app) {
	$settings = $app['settings']->obtain();
	$themes = $app['settings']->getThemes(ROOT.'/themes/*');
	$data = array(
        'siteName' => $settings->siteName,
        'description' => $settings->description,
    );

	$templateChoises = array();
    	foreach($themes as $theme):
		$templateChoises[$theme['name']] = $theme['name'];
	endforeach;
    $form = $app['form.factory']->createBuilder('form', $data)
      ->add('siteName')
      ->add('description', 'textarea')
        ->add('Thema', 'choice', array(
		'choices' => $templateChoises,'expanded' => false,
		))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
	    $data = $form->getData();
	    $app['settings']->save($data);
	    return $app->redirect($app['url_generator']->generate('settings'));
    }


	return $app['twig']->render('system/views/settings.html', array(
		'path' => $app['config']['system']['template']['path'],
		'form' => $form->createView()
	));
};

/* Temporary placeholder for pages, this will be replaced with the pages routers */
$render['admin']['pages_remove'] = function($id_list) use ($app) {

        $list = array_filter(explode(',',str_replace('_','',$id_list)));
        $app['pages']->remove($list);
        return $app->redirect($app['url_generator']->generate('pages'));


};
$render['admin']['pages_add'] = function(Request $request) use ($app) {
	 $data = array(
        'username' => 'Username',
        'password' => 'password',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('pageName','text',array(
          'label' => 'Pagina naam'
        ))

        ->add('pageDescription','textarea',array(
          'label' => 'Pagina beschrijving'
        ))
        ->add('pageTags','text',array(
          'label' => 'Pagina sleutelwoorden'
        ))
       ->add('template', 'choice', array(
            'choices' => array('ROLE_ADMIN' => 'admin', 'ROLE_USER' => 'user'),
            'expanded' => false,
            'label' => 'Pagina vormgeving'
        ))
        ->add('pageContent','textarea', array(
          'label' => 'paginaInhoud',
          'attr' => array('data-uk-htmleditor'=>'{markdown:true}')
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


	return $app['twig']->render('system/views/pages_new.html', array(
		'path' => $app['config']['system']['template']['path'],
		'form' => $form->createView()
	));

};
$render['admin']['pages_filter'] = function() use ($app) { return 'settings'; };
$render['admin']['pages_edit'] = function() use ($app) { return 'settings'; };

$render['admin']['pages'] = function() use ($app) {
	return $app['twig']->render('system/views/pages.html', array(
		'path' => $app['config']['system']['template']['path'],
		'pages' => $app['pages']->getPages()
	));
};

$render['admin']['users'] = function() use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => $app['config']['system']['template']['path'],
		'users' => $app['users']->getUsers()
	));
};

$render['admin']['users_add'] =  function(Request $request) use ($app) {

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
		'path' => $app['config']['system']['template']['path'],
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
		'path' => $app['config']['system']['template']['path'],
		'form' => $form->createView()
	));
};

$render['admin']['users_insert'] = function(Request $request) use ($app) {
   return $app['twig']->render('system/views/users_new.html', array(
        'request' => $request,
	   	'path' => $app['config']['system']['template']['path']
    ));
};

$render['admin']['users_remove']= function($id_list) use ($app) {

	$list = array_filter(explode(',',str_replace('_','',$id_list)));
	$app['users']->removeUsers($list);
	return $app->redirect($app['url_generator']->generate('users'));
};

$render['admin']['users_filter']= function($filter) use ($app) {
	return $app['twig']->render('system/views/users.html', array(
		'path' => $app['config']['system']['template']['path'],
		'users' => $app['users']->getUsersFrom($filter)
	));
};
