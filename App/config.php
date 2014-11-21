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

// set debug information
$app['debug'] = true;


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



require_once('routes.php');