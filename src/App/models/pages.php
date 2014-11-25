<?php
namespace Models;


class pagesModel{

	public static $app;

	public function __construct($app){

		// solve dep inject., move app object to static var
		$this->app = $app;
		// returning void


	}

	public function load($slug){

		$slug = $this->app->escape($slug);
		$sql = 'SELECT * FROM pages WHERE slug = ? LIMIT 0,1';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->bindValue(1, $slug);
		$stmt->execute();

		$pages = $stmt->fetch();
		return($pages);

	}








}