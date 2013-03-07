<?php

include 'config.php';
include 'Psa_Dully.class.php';

$translate = new transtable($TTCFG['php_array_files']);


$translations = $translate->get_all_translations();

print_r($translations);
//$translate->save_translation('/hr.php', 'wwwwww', 'asdfasdfasdfasdf');


// display template
$dully = new Psa_Dully(dirname(__FILE__) . '/templates');

$dully->assign('data', $translations);
$dully->assign('data', $TTCFG['php_array_files']['var_name']);

echo $dully->fetch('translation_table.tpl');










class transtable{
	
	public $config;
	
	public function __construct($config){
		
		$this->config = $config;
		
	}
	
	
	/**
	 * 
	 */
	public function get_all_translations($for_folder = null){
		
		// file name pattern
		$file_name_pattern = '/^' . $this->config['file_name_pattern'] . '$/';
		
		$di = new RecursiveDirectoryIterator($this->config['root_dir']);
		
		$i = 0; $root_path_len = 0;
		foreach (new RecursiveIteratorIterator($di) as $path => $file) {
			
			if($i == 0)
				$root_path_len = strlen($path);
			$i++;
			
			// file name
			$file_name = $file->getFilename();
			
			// skip directories and file with names that doesn't match the patern
			if($file->isDir() or !preg_match($file_name_pattern, $file_name))
				continue;
				
			// folder relative path
			$folder = '/' . substr_replace(dirname($path), '', 0, $root_path_len);
			
			// include the file with translations
			if(!$for_folder or $for_folder == $folder){
				
				// reset variable with translations
				${$this->config['var_name']} = array();
				
				// include the file
				include $path;
				
				$return[$folder]['translations'][$file_name] = ${$this->config['var_name']};
			}
			else
				$return[$folder]['translations'] = null;
			
			$return[$folder]['tab_name'] = basename($folder);
		}
		
		// find all indexes form each translation array
		foreach ($folder as $folder => $data) {
			
			if($folder[$folder]['translations']){
				foreach ($folder[$folder]['translations'] as $file_name => $translations) {
					
				}
			}
		}
		print_r($return);
		
		return $return;
	}
	
	
	function echo_translation_array($translations, $var_name, $arr_level = ''){
	
		foreach ($translations as $index => $translation){
	
			$arr_level1 = $arr_level . "['" . $index . "']";
	
			if(is_array($translation)){
				echo_translation_array($translation, $var_name, $arr_level1);
			}
			else
				echo '$' . $var_name . $arr_level1 . " = '" . addslashes($translation) . "';\n";
		}
	
	}
	
	
	/**
	 *
	 */
	public function save_translation($file_path_relative, $index, $translation){
	
		// full path to file
		$file_path = $this->config['root_dir'] . $file_path_relative;
		$file_path_clean = realpath($this->config['root_dir'] . $file_path_relative);
		
		if(!$file_path_clean)
			throw new transtable_exception("File $file_path doesn't exists");
		
		// check if file is subdir
		$root_dir = realpath($this->config['root_dir']);
		if(strcmp(substr($file_path_clean, 0, strlen($root_dir)), $root_dir) !== 0)
			throw new transtable_exception("File $file_path_clean is not in subdirecory of $root_dir");
		
		
		${$this->config['var_name']} = array();
		
		// include the file
		include $file_path;
	
		// set new value
		${$this->config['var_name']}[$index] = $translation;
		
		// save new file
		$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
		$dully->assign('t', ${$this->config['var_name']});
		$dully->assign('var_name', $this->config['var_name']);
		file_put_contents($file_path_clean, $dully->fetch('lang_file.tpl'));
	}
}


class transtable_exception extends Exception{}

