<?php

namespace Models;

class Settings{
	
	private $file;
	private $settings;

	public function __construct($file){
		$this->file = $file;
		$settings = $this->readSettings($file);
		if($settings):
			$this->settings = $this->readSettings = $settings;
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


	/*
	 * Get all the themes in the given theme folder
	 *
	 * @param string folder where all the themes are stored
	 * @return array of all themes in folder
	 */
	public function getThemes($folder){
		$themes = glob($folder);
		$return = array();
		$settings = NULL;
		$i = 0;
		foreach($themes as $theme):
			if(file_exists($theme.'/settings.json')):
				$settings = json_decode(file_get_contents($theme.'/settings.json'));
			endif;
			$return[$i] = array(
				'name' => basename($theme),
				'settings' => $settings
			);
			$i++;
		endforeach;
		return $return;

	}


	/*
	 * Save the settings to the json file they came from
	 * @input arrray settings
	 * @return void;
	 */
	public function save($settings){
		file_put_contents($this->file, json_encode($settings));
	}
}
