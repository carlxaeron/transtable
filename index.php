<pre>
<?php

include 'config.php';
include 'Psa_Dully.class.php';


/**
 * Router. Finds action to do from GET parameter.
 */
if(isset($_GET['transtable_action']) && $_GET['transtable_action'])
	$action = $_GET['transtable_action'];
else
	$action = 'index';


/**
 * Controller
 */

// main page
if($action == 'index'){
	
	$transtable = new transtable();
	
	// get all translations from the root folder
	$translations = $transtable->get_all_translations('/');
	
	print_r($translations);
	
	// display template
	$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
	
	$dully->assign('translate', $transtable);
	$dully->assign('data', $translations);
	$dully->assign('folder', '/');
	$dully->assign('page_title', $TTCFG['php_array_files']['page_title']);
	$dully->assign('page_content', $dully->fetch('translation_table.tpl'));
	
	echo $dully->fetch('main.tpl');
}

// save translation
else if($action == 'savetranslation'){
	
	$transtable = new transtable();
	
	echo $transtable->save_translation($_POST['file_name'], $_POST['index'], $_POST['translation']);
}

// save index
else if($action == 'saveindex'){

	$transtable = new transtable();

	echo $transtable->add_rename_index($_POST['old_index'], $_POST['new_index'], $_POST['folder']);
}







/**
 * Model
 */
class transtable{
	
	
	/**
	 * Configuration array
	 */
	public $config;
	
	
	/**
	 * Constructor
	 */
	public function __construct($config = null){
		
		// put config array to class scope
		if($config)
			$this->config = $config;
		else{
			global $TTCFG;
			$this->config = $TTCFG['php_array_files'];
		}
	}
	
	
	/**
	 * Return array with all folders (tabs) and corresponding translations
	 */
	public function get_all_translations($for_folder = null){
		
		// file name pattern
		$file_name_pattern = '/^' . $this->config['file_name_pattern'] . '$/';
		
		if($for_folder)
			$for_folder = $this->check_path($for_folder);
		
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
		
		
		// array that holds all indexes from all translation arrays (from each file in folder)
		$return[$folder]['all_indexes'] = array();
		
		
		// find all indexes form each translation array
		// for each folder
		foreach ($return as $folder => $data) {
			// if there are any translations
			if($return[$folder]['translations']){
				// for each file
				foreach ($return[$folder]['translations'] as $file_name => $translations) {
					// for each translation in file
					foreach ($translations as $index => $translation) {
						
						if(is_array($translation)){
							foreach ($this->get_text_index($translation, $index) as $subindexes_text)
								$return[$folder]['all_indexes'][$subindexes_text] = null;
						}
						else{
							$return[$folder]['all_indexes'][$index] = null;
						}
					}
				}
			}
		}
		
		return $return;
	}
	
	
	/**
	 * Recursive function which return text index from multi dimensional array.
	 * For example, for array with elements ['bla']['bla']['bla'] will return bla|bla|bla
	 * 
	 * @param array $translation_array 
	 * @param string $prefix_index
	 */
	protected function get_text_index($translation_array, $prefix_index){
		
		static $return = array();
		
		foreach ($translation_array as $index => $value){
	
			$text_index = $prefix_index . '|' . $index;
			
			if(is_array($value))
				$this->get_text_index($value, $text_index);
			else
				$return[] = $text_index;
		}
		
		return $return;
	}
	
	
	/**
	 * Returns php arrray index from txt index.
	 * For example:
	 * for bla will return ['bla']
	 * for bla|bla|bla will return ['bla']['bla']['bla']
	 * 
	 * @param string $txt_index
	 */
	public function get_php_index($txt_index){
		
		$txt_index = trim($txt_index);
		
		if(!$txt_index)
			throw new transtable_exception("Translation index cannot be empty");
		
		$txt_index = addslashes($txt_index);
		
		// for multi dimensional indexes like bla|bla|bla
		if(strpos($txt_index, $this->config['array_delimiter']) !== false)
			return "['" . str_replace($this->config['array_delimiter'], "']['", $txt_index) . "']";
		else
			return "['" . $txt_index . "']";
	}
	
	
	/**
	 * Saves translation to file
	 * 
	 * @param string $file_path_relative relative path from $TTCFG['php_array_files']['root_dir']
	 * @param string $index text index
	 * @param string $translation translation value
	 * @throws transtable_exception
	 */
	public function save_translation($file_path_relative, $index, $translation){
	
		// full path to file
		$file_path_clean = $this->check_path($file_path_relative, 'return_absolute_path');
		
		${$this->config['var_name']} = array();
		
		// include the file
		include $file_path_clean;
	
		// set new value
		eval('${$this->config[\'var_name\']}' . $this->get_php_index($index) . ' = $translation;');
		
		// save file
		$this->save_translation_file($file_path_clean, $translations);
		
		return 1;
	}
	
	
	/**
	 * Saves translation to file
	 *
	 * @param string $file
	 * @param string $translations
	 * @throws transtable_exception
	 */
	protected function save_translation_file($file, $translations){
	
		$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
		$dully->assign('t', $translations);
		$dully->assign('var_name', $this->config['var_name']);
		if(!file_put_contents($file, $dully->fetch('lang_file.tpl')))
			throw new transtable_exception("Cannot write to file $file");
	}
	
	
	/**
	 * Check if path is in subdirectory of $TTCFG['php_array_files']['root_dir']
	 * and if exists.
	 * 
	 * @param string $file_path_relative
	 * @param bool $return_original_path
	 * @throws transtable_exception
	 * @return string
	 */
	protected function check_path($file_path_relative, $return_absolute_path = 0){
	
		// full path to file
		$file_path_clean = realpath($this->config['root_dir'] . '/' . $file_path_relative);
	
		if(!$file_path_clean)
			throw new transtable_exception("File $file_path doesn't exists");
	
		// check if file is subdir
		$root_dir = realpath($this->config['root_dir']);
		if(strcmp(substr($file_path_clean, 0, strlen($root_dir)), $root_dir) !== 0)
			throw new transtable_exception("File $file_path_clean is not in subdirecory of $root_dir");
		
		if($return_absolute_path)
			return $file_path_clean;
		else
			return $file_path_relative;
	}


	/**
	 * 
	 * @param unknown_type $old_index
	 * @param unknown_type $new_index
	 * @param unknown_type $folder
	 */
	public function add_rename_index($old_index, $new_index, $folder){
	
		$folder = $this->check_path($folder);
		
		//if(!$old_index or !$new_index)
		//	throw new transtable_exception("Error. Old or new index name not set.");
		
		$translations = $this->get_all_translations($folder);
		
		// for each translation file in folder
		foreach ($translations as $folder => $data) {
			
			if($folder == $folder){
				foreach ($data['translations'] as $file_name => $translations) {
					
					//$file_path = 
					
					// rename
					if(isset($translations[$old_index])){
						$this->save_translation_file($folder . $file_name, $this->replace_array_key($translations, $old_text_index, $new_text_index));
					}
				}
			}
		}
	}
	
	
	
	/**
	 * Changes a key in an array while maintaining the order.
	 * 
	 * @param array $array
	 * @param string $old_key
	 * @param string $new_key
	 * @return array
	 */
	protected function replace_array_key($array, $old_text_key, $new_text_key){
		
		// if key is array
		
			// unset value alement and check if other arrays are empty
		
		$keys = array_keys($array);
		$index = array_search($old_key, $keys);
	
		if ($index !== false) {
			$keys[$index] = $new_key;
			$array = array_combine($keys, $array);
		}
	
		return $array;
	}
}


/**
 * Exception transtable_exception
 */
class transtable_exception extends Exception{}

