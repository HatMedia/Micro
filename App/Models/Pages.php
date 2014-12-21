<?php
namespace Models;


class Pages{

	public $app;
	public $config = array();

	public function __construct($app = true){

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
	 *
	 * Remove an list or a single page
	 * @param array id, list of id's to remove
	 * @return void
	 *
	 */
   	 public function remove($id){
		$i = 1;
		$sql = 'DELETE FROM pages WHERE id IN (';
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

	public function getPages(){
		$sql = 'SELECT * FROM pages';
		$stmt = $this->app['db']->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
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
