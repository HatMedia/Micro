<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;


$app->get('/dashboard', function (){
    $output = 'Hi all!';

    return $output;
});
$app->run();