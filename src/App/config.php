<?php
/*
 * part of Micro
 *
 *
 * @author Matti van de Weem<mvdweem@gmail.com>
 */


define('ROOT',dirname(__DIR__));


$loader = require ROOT."/vendor/autoload.php";

$app = new Silex\Application();

//[todo:] envoirment check... (check if you are developing in the dev branch or the master branch)
$app['debug'] = true;

$app['config'] = array(

	'template' => array(
		'extension' => 'html',
		'cache' => ROOT.'/App/cache',
		'folder' => ROOT.'/themes'
	)


);


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array (
            'driver'    => 'pdo_mysql',
            'host'      => '127.0.0.1',
            'dbname'    => 'micro',
            'user'      => 'def',
            'password'  => 'def',
            'charset'   => 'utf8',
        )
    )
);

$app->register(new Silex\Provider\SecurityServiceProvider(),array(
	'security.firewalls'=>array(
		 'secure' => array(
				'anonymous' => false,
				'pattern' => '^/dashboard$',
				'form' => array('login_path' => '/dashboard/login', 'check_path' => '/dashboard/login_check'),
				'logout' => array('logout_path' => '/dashboard/logout')
			),
		),
		'security.access_rules' => array(
			array('^/dashboard$', 'ROLE_ADMIN')
		),
		'security.role_hierarchy'=> array(
			'ROLE_ADMIN' => array('ROLE_ADMIN')
		),
	)
);

//[TODO] Make this OOP
require_once('models/pages.php');
$pages = new Models\pagesModel($app);

//
$loader = new Twig_Loader_Filesystem($app['config']['template']['folder']);
$twig = new Twig_Environment($loader, array(
    'cache' => $app['config']['template']['cache'],
));
require_once('routes.php');