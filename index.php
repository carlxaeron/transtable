<?php

include 'config.php';

$translate = new transtable($TTCFG['php_array_files']);


$translate->get_all_translations();


// display template

class transtable{
	
	public $config;
	
	public function __construct($config){
		
		$this->config = $config;
		
	}
	
	
	/**
	 * 
	 */
	public function get_all_translations(){
		
		// file name pattern
		$file_name_pattern = '/^' . $this->config['file_name_pattern'] . '$/';
		
		$di = new RecursiveDirectoryIterator($this->config['root_dir']);
		foreach (new RecursiveIteratorIterator($di) as $path => $file) {
			
			// file name
			$file_name = $file->getFilename();
			
			// skip directories and file with names that doesn't match the patern
			if($file->isDir() or !preg_match($file_name_pattern, $file_name))
				continue;
				
			// folder name
			$folder_name = basename(dirname($path));
			
			// reset variable with translations
			${$this->config['var_name']} = array();
			
			// include the file
			include $path;
			
			$return[$folder_name][$file_name] = ${$this->config['var_name']};
			
			
		}
		
		return $return;
	}
	
	
	public function save_translation(){
	
	
	
	}
}