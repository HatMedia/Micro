<?php
namespace Models;


class PagesModel{

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

	public function getDefault(){

		$sql = 'SELECT * FROM pages LIMIT 0,1';
		$stmt = $this->app['db']->prepare($sql);
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
		$sql = 'SELECT * FROM posts WHERE page_id = ? ORDER BY id DESC';
		$statement = $this->app['db']->prepare($sql);
		$statement->bindValue(1,$pid);
		$statement->execute();
		return $statement->fetchAll();

	}


	public function getPageList($pid){
		$sql = 'SELECT * FROM pages';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->execute();

		$pages = $stmt->fetchAll();
		$i= 0;
		foreach($pages as $pag):
			if($pag['id'] == $pid):
				$pages[$i]['status'] = 'current';
			else:
				$pages[$i]['status'] = false;
			endif;
		$i++;
		endforeach;
		return($pages);
	}





}
