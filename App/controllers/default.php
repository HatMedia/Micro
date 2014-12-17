<?php
$render['default']['main'] = function($slug) use ($app) {
	if($slug):
		$page = $app['pages']->load($slug);
	else:
		$page = $app['pages']->getDefault();
	endif;
	$tpl = 'default'.'/'.$page['template'].'.'.$app['config']['template']['extension'];

	$config['app'] = $app;
	$config['page'] = $app['pages']->config;
	$config['url'] = 'http://'.$_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
	$config['path'] = 'http://micro.dev/themes/default';
	$config['posts'] = $app['pages']->getPagePosts($page['id']);
	$config['pageList'] = $app['pages']->getPageList($page['id']);

	$template = $app['twig']->loadTemplate($tpl);


  	return $template->render($config);
};
