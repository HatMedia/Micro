<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/admin', function(Request $request) use ($app) {

    	return 'template';


});

$app->get('/login', function(Request $request) use ($app) {
   return $app['twig']->render('system/views/login.html', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

// todo redirect this to default homepage function
$app->get('/',function() use($app){return false;});


// todo split up controllers
$app->get('/{slug}', function($slug) use($app, $pages) {
	$page = $pages->load($slug);
	$tpl = 'default'.'/'.$page['template'].'.'.$app['config']['template']['extension'];


	// move these to a better location ;)
	$config['app'] = $app;
	$config['page'] = $pages->config;
	$config['url'] = 'http://'.$_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
	$config['path'] = 'http://localhost/micro/Micro/src/themes/default';
	$config['posts'] = $pages->getPagePosts($page['id']);
	$config['pageList'] = $pages->getPageList($page['id']);

	$template = $app['twig']->loadTemplate($tpl);

	return $template->render($config);

});
