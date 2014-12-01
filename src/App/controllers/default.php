<?php
$render['default']['main'] = function($slug) use ($app,$pages) {
	if($slug):
		$page = $pages->load($slug);
	else:
		$page = $pages->getDefault();
	endif;
	$tpl = 'default'.'/'.$page['template'].'.'.$app['config']['template']['extension'];

	$config['app'] = $app;
	$config['page'] = $pages->config;
	$config['url'] = 'http://'.$_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['PHP_SELF']);
	$config['path'] = 'http://localhost/micro/Micro/src/themes/default';
	$config['posts'] = $pages->getPagePosts($page['id']);
	$config['pageList'] = $pages->getPageList($page['id']);

	$template = $app['twig']->loadTemplate($tpl);


  	return $template->render($config);
};
