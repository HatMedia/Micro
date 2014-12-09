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
			$count = count($id);
			for($y = 0; $y < $count; $y++):
			 	$sql .= ' ?,';
			endfor;
			$sql = rtrim($sql, ",").')';

			$stmt = $this->app['db']->prepare($sql);
			foreach($id as $x):
				$stmt->bindValue($i, $x);
				$i++;
			endforeach;
		$stmt->execute();
		endif;
	}

	public function getList($id){
		$i = 1;
		$sql = 'SELECT * FROM users WHERE id IN (';
		if(is_array($id)):
			for($y = 0; $y < (count($id) ); $y++):
			 	$sql .= ' ?,';
			endfor;
			$sql = rtrim($sql, ",").')';

			$stmt = $this->app['db']->prepare($sql);
			foreach($id as $x):
				$stmt->bindValue($i, $x);
				$i++;
			endforeach;
			$stmt->execute();
			$return = array();
			foreach($stmt->fetchAll() as $user):

				$return[$user['id']] = $user;

			endforeach;
		endif;
		return $return;
	}

	public function update($id, $settings){
		$sql = 'UPDATE users SET';
		if(isset($settings['username']) && $settings['username'] != ''):
			$sql .= ' username = :us,';
		endif;
		if(isset($settings['classes']) && $settings['classes'] != ''):
			$sql .= ' roles = :rol,';
		endif;
		if(isset($settings['password']) && $settings['password'] != '' && !is_null($settings['password'])):
			$sql .= ' password = :pas,';
		endif;
		$sql = rtrim($sql, ",");
		$sql.=' WHERE id = :id';

		$stmt = $this->app['db']->prepare($sql);
		$stmt->bindValue(':id', $id);

		if(isset($settings['username']) && $settings['username'] != ''):
			$stmt->bindValue(':us', $settings['username']);
		endif;
		if(isset($settings['classes']) && $settings['classes'] != ''):
			$stmt->bindValue(':rol', $settings['classes']);
		endif;
		if(isset($settings['password']) && $settings['password'] != '' && !is_null($settings['password'])):
			$stmt->bindValue(':pas', $this->app['security.encoder.digest']->encodePassword($settings['password'],''));
		endif;
		$stmt->execute();


	}
}
