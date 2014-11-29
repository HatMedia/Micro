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

$app->register(new Silex\Provider\SessionServiceProvider());
use Symfony\Component\HttpFoundation\Request;

//[todo:] envoirment check... (check if you are developing in the dev branch or the master branch)
$app['debug'] = true;

$app['config'] = array(

	'template' => array(
		'extension' => 'html',
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




$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['config']['template']['folder'],
    'twig.options' => array('debug' => true)
));
$function = new Twig_SimpleFunction('is_granted', function($role,$object = null) use ($app){
        return $app['security']->isGranted($role,$object);
    });
    $app['twig']->addFunction($function);


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
    return $app['url_generator']->generate($url);
}));

include('User/UserProvider.php');
$app->register(new Silex\Provider\SecurityServiceProvider(), array());

$app['security.firewalls'] = array(
    'secured' => array(
        'pattern' => '^/admin',
        'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
        'logout' => array('logout_path' => '/admin/logout'),
         'users' => $app->share(function() use ($app) {
				return new App\User\UserProvider($app['db']);
			}),
    ),
);

$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
);


require_once('models/pages.php');
$pages = new Models\pagesModel($app);



require_once('routes.php');
