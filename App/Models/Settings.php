<?php

namespace Models;

class Settings{

	private $settings = array();

	public function __construct($file){
		$settings = $this->readSettings($file);
		if($settings):
			$this->readSettings = $settings;
		endif;
	}

	/*
	 * read the settings file
	 * @param string file the file where the settings are located
	 * @return object with settings
	 */

	public function readSettings($file){
		if(file_exists($file)):
			return json_decode(file_get_contents($file));
		endif;
		return false;
	}

	/*
	 * Obtain a the settings object
	 * @return object
	 *
	 */

	public function obtain(){
		return $this->settings;
	}

}
