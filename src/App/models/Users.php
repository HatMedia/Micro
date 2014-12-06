<?php

namespace Models;


class Users{


	public $app;
	public $config = array();

	public function __construct($app = true){

		// solve dep inject., move app object to static var
		$this->app = $app;
		// returning void


	}


	public function getUsers(){
		$sql = 'SELECT * FROM users';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();

	}

	public function getUsersFrom($filter){
		$filter = $this->app->escape($filter);
		$sql = 'SELECT * FROM users WHERE roles = ?';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->bindValue(1, $filter);
		$stmt->execute();

		$pages = $stmt->fetchAll();
		return($pages);


	}

	public function insertUser($username, $password, $role){

		try{
			$username = $this->app->escape($username);
			$password = $this->app->escape($password);
			$role = $this->app->escape($role);
			$password =  $this->app['security.encoder.digest']->encodePassword($password, '');
			$sql = 'INSERT INTO users (username,password,roles) VALUES (?,?,?)';
			$stmt = $this->app['db']->prepare($sql);
			$stmt->bindValue(1, $username);
			$stmt->bindValue(2, $password);
			$stmt->bindValue(3, $role);

			$stmt->execute();
		} catch (Exception $e) {
			return false;
		}
		return true;
	}

	public function removeUsers($id){
		$i = 1;
		$sql = 'DELETE FROM users WHERE id IN (';
		if(is_array($id)):
			for($y = 0; $y < (count($id) ); $y++):
			 	$sql .= ' ?,';
			endfor;
			$sql = rtrim($sql, ",").')';
			echo $sql;

			$stmt = $this->app['db']->prepare($sql);
			foreach($id as $x):
				$stmt->bindValue($i, $x);
				$i++;
			endforeach;
		$stmt->execute();
		endif;



	}
}
