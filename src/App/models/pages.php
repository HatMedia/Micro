<?php
namespace Models;


class pagesModel{

	public $app;
	public $config = array();

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


	/*

		Get list of all posts on a page

		@param int $pid Page id to get list from
		@return array of posts


	*/
	public function getPagePosts($pid){
		$sql = 'SELECT * FROM posts WHERE page_id = ?';
		$statement = $this->app['db']->prepare($sql);
		$statement->bindValue(1,$pid);
		$statement->execute();
		return $statement->fetchAll();

	}





}
