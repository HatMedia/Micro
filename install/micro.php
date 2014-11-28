<?php

$settings = json_decode(file_get_contents('assets/settings.json'));

if($argv[1] == 'settings'):
	echo "setting up database, what are your db settings? \n";

	echo 'DB username:';
	$handle = fopen ("php://stdin","r");
	$user = fgets($handle);

echo 'DB password:';
	$handle = fopen ("php://stdin","r");
	$password = fgets($handle);

echo 'DB host:';
	$handle = fopen ("php://stdin","r");
	$host = fgets($handle);

echo 'DB Database:';
	$handle = fopen ("php://stdin","r");
	$database = fgets($handle);

$settings = array(
	'db' => array(
		'user' => str_replace("\n","",$user),
		'pass' => str_replace("\n","",$password),
		'host' => str_replace("\n","",$host),
		'database' => str_replace("\n","",$database)
	)
);

file_put_contents('assets/settings.json',json_encode($settings));
echo 'Setting file has been udpated .. assets/settings.json';
endif;


if($argv[1] == 'backup'):
	exec('mysqldump --user='.$settings->db->user.' --password='.$settings->db->pass.' --host='.$settings->db->host.' '.$settings->db->database.' > backups/'.time().'-backup.sql');
	echo 'Backup has been created';
endif;
