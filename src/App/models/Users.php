<?php

namespace Models;


class Users{


	public function __construct(){

	}

	public function getUsers(){
		$sql = 'SELECT * FROM users';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();

	}
}
