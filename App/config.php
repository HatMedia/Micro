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
require_once('Models/Settings.php');
require_once('Models/pages.php');
require_once('Models/Users.php');
$settings = new Models\Settings(ROOT.'/App/micro_config.json');

$loader = require ROOT."/vendor/autoload.php";

$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());

// set up settings  ['todo move']
$siteUrl = 'http://micro.dev/';
$templateFolder = 'themes/';
$theme = 'system';


$app['debug'] = true;
$app['settings'] = $settings;
$app['config'] = array(

	'url' => $siteUrl,
	'template' => array(
		'folder' => $templateFolder,
		'extension' => 'html',
	),
	'system' => array(
		'panel' => 'admin',
		'template' => array(
			'folder' => ROOT.'themes/',
			'theme' => 'system',
			'path' => $siteUrl.$templateFolder.$theme
		),
	),
	'settings' => $settings->obtain()
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
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['config']['template']['folder'],
    'twig.options' => array('debug' => true)
));

$app['twig']->addFunction(new Twig_SimpleFunction('timeAgo', function($time, $object = null) use ($app) {
   	$timeAgo = new TimeAgo();
	return $timeAgo->inWords($time);
}));

$app['twig']->addFunction(new Twig_SimpleFunction('is_granted', function($role,$object = null) use ($app){
	return $app['security']->isGranted($role,$object);
}));
$app['twig']->addFunction(
	new Twig_SimpleFunction(
	'active',
		function ($name) use ($app) {
			if (strpos($app['request']->getUri(),$name) !== false) {
				if (isset($app['active_link.snippet'])) {
					return $app['active_link.snippet'];
				} else {
					return true;
				}
			}
			return false;
		},
			array('is_safe' => array('html'))
	)
);

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
	array('^/'.$app['config']['system']['panel'].'', 'ROLE_EDITOR'),
);

$app['pages'] = new Models\Pages($app);

$app['users'] = new Models\Users($app);

require_once('routes.php');
