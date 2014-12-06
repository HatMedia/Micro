<?php
/*
 * Part of Micro
 *
 * For licensing read the license file provided..
 *
 * @author Matti van de Weem<mvdweem@gmail.com>
 *
 */


define('ROOT',dirname(__DIR__));

$loader = require ROOT."/vendor/autoload.php";

$app = new Silex\Application();
use Herrera\Silex\ActiveLinkServiceProvider;

$app->register(new Silex\Provider\SessionServiceProvider());



$app['debug'] = true;

$app['config'] = array(

	'template' => array(
		'extension' => 'html',
		'folder' => ROOT.'/themes'
	),
	'system' => array(
		'panel' => 'admin'
	),

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


use Silex\Provider\FormServiceProvider;
$app->register(new FormServiceProvider());

use Symfony\Component\Validator\Constraints as Assert;

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));



$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['config']['template']['folder'],
    'twig.options' => array('debug' => true)
));


$app['twig']->addFunction(
	new Twig_SimpleFunction(
	'active',
		function ($name) use ($app) {
			if ($name === $app['request']->get('_route')) {
				if (isset($app['active_link.snippet'])) {
					return $app['active_link.snippet'];
				} else {
					return ' class="active"';
				}
			}
			return '';
		},
			array('is_safe' => array('html'))
	)
);

$isGranted = new Twig_SimpleFunction('is_granted', function($role,$object = null) use ($app){
        return $app['security']->isGranted($role,$object);
});

$app['twig']->addFunction($isGranted);


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());


$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
    return $app['url_generator']->generate($url);
}));

$app['twig']->addFunction(new \Twig_SimpleFunction('timeAgo', function($time) use ($app) {
   	$timeAgo = new TimeAgo();
	return $timeAgo->inWords($time);
}));



include('User/UserProvider.php');
$app->register(new Silex\Provider\SecurityServiceProvider(), array());

$app['security.firewalls'] = array(
    'secured' => array(
        'pattern' => '^/'.$app['config']['system']['panel'].'',
        'form' => array('login_path' => '/login', 'check_path' => '/'.$app['config']['system']['panel'].'/login_check'),
        'logout' => array('logout_path' => '/'.$app['config']['system']['panel'].'/logout'),
         'users' => $app->share(function() use ($app) {
				return new App\User\UserProvider($app['db']);
			}),
    ),
);


$app['security.access_rules'] = array(
    array('^/'.$app['config']['system']['panel'].'', 'ROLE_ADMIN'),
);



require_once('Models/pages.php');
$pages = new Models\Pages($app);

require_once('Models/Users.php');
$app['users'] = new Models\Users($app);



require_once('routes.php');
