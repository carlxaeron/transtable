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
		
		
		
		$patern = '/^' . $this->config['file_name_pattern'] . '$/';
		
		$cur_dir = '';
		
		$di = new RecursiveDirectoryIterator($this->config['root_dir']);
		foreach (new RecursiveIteratorIterator($di) as $path => $file) {
			
			if($file->isDir())
				echo $path . '----<br>';
			
			// skip directories and file with names that doesn't match the patern
			if($file->isDir() or !preg_match($patern, $file->getFilename()))
				continue;
			
			// include the file
			
			// read content
			
			// file name
			
			// folder name
			
			// 
			
			$return[$folder][$filename] = $$this->config['var_name'];
			
			print_r($path);
			
		}
		
	}
	
	
	public function save_translation(){
	
	
	
	}
}