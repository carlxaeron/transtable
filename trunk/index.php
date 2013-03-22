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
 * Controller part
 */

// main page
if($action == 'index'){
	
	$transtable = new transtable();
	
	// get all translations from folder
	if(isset($_GET['transtable_folder']) && $_GET['transtable_folder'])
		$folder = $_GET['transtable_folder'];
	else
		$folder = '/';
	
	$translations = $transtable->get_all_translations($folder);
	$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
	
	if($translations){
		$dully->assign('translate', $transtable);
		$dully->assign('data', $translations);
		$dully->assign('folder', $folder);
		$dully->assign('page_title', $TTCFG['php_array_files']['page_title']);
		$dully->assign('enable_html_editor', $TTCFG['php_array_files']['enable_html_editor']);
		$dully->assign('enable_edit_index', $TTCFG['php_array_files']['enable_edit_index']);
		$dully->assign('enable_delete_translation', $TTCFG['php_array_files']['enable_delete_translation']);
		$dully->assign('enable_add_translation', $TTCFG['php_array_files']['enable_add_translation']);
	}
	else
		$dully->assign('no_translations', 1);
	$dully->assign('page_content', $dully->fetch('translation_table.tpl'));
	
	// display template
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
	echo $transtable->rename_index($_POST['old_index'], $_POST['new_index'], $_POST['folder']);
}

// delete index (row)
else if($action == 'deleteindex'){
	$transtable = new transtable();
	echo $transtable->delete_index($_POST['index'], $_POST['folder']);
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
		
		$return = array();
		
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
			$folder = '/' . substr_replace(dirname($path), '', 0, $root_path_len-1);
			
			
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
		
		
		if($return){
			// find all indexes form each translation array
			// for each folder
			foreach ($return as $folder => $data) {
				// if there are any translations
				if($return[$folder]['translations']){
					
					// sort translations by file name
					ksort ($return[$folder]['translations']);
					
					// array that holds all indexes from all translation arrays (from each file in folder)
					$return[$folder]['all_indexes'] = array();
					
					// for each file
					foreach ($return[$folder]['translations'] as $file_name => $translations) {
						// for each translation in file
						foreach ($translations as $index => $translation) {
							
							if(is_array($translation)){
								
								foreach ($this->get_text_indexes($translation, $index) as $subindexes_text)
									$return[$folder]['all_indexes'][$subindexes_text] = null;
							}
							else{
								$return[$folder]['all_indexes'][$index] = null;
							}
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
	protected function get_text_indexes($translation_array, $prefix_index){
		
		static $return = array();
		
		foreach ($translation_array as $index => $value){
	
			$text_index = $prefix_index . '|' . $index;
			
			if(is_array($value))
				$this->get_text_indexes($value, $text_index);
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
	
		print_r(${$this->config['var_name']});
		
		// set new value
		eval('${$this->config[\'var_name\']}' . $this->get_php_index($index) . ' = $translation;');
		
		print_r(${$this->config['var_name']});
		
		// save file
		$this->write_translation_file($file_path_clean, $t);
		
		return 1;
	}
	
	
	/**
	 * Saves translation to file
	 *
	 * @param string $file
	 * @param string $translations
	 * @throws transtable_exception
	 */
	protected function write_translation_file($file, $translations){
		
		$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
		$dully->assign('t', $translations);
		$dully->assign('var_name', $this->config['var_name']);
		
		// php file content
		$file_content = $dully->fetch('translation_file.tpl');
		
		// normalize new lines
		str_replace(array("\r\n","\r"), "\n", $file_content);
		if($this->config['new_lines'] != "\n")
			$file_content = str_replace("\n", $this->config['new_lines'], $file_content);
		
		if(!file_put_contents($file, $file_content))
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
			throw new transtable_exception("File {$this->config['root_dir']}/$file_path_relative doesn't exists");
	
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
	 * Renames the index in the translation array
	 * 
	 * @param unknown_type $old_index
	 * @param unknown_type $new_index
	 * @param unknown_type $folder
	 */
	public function rename_index($old_text_index, $new_text_index, $folder){
	
		$this->require_edit_index_permission();
		
		$folder = $this->check_path($folder);
		
		$old_text_index = trim($old_text_index);
		$new_text_index = trim($new_text_index);
		
		if(!$old_text_index or !$new_text_index)
			throw new transtable_exception("Error. Old or new index name not set.");
		
		$translations = $this->get_all_translations($folder);
		
		// for each translation file in folder
		foreach ($translations as $folder => $data) {
			
			if($folder == $folder){
				foreach ($data['translations'] as $file_name => $translations) {
						
					// full path to file with translations
					$file_path = $this->check_path($folder . $file_name, 'return_absolute_path');
					
					// replace index
					$translations = $this->replace_array_key($translations, $old_text_index, $new_text_index);
					
					// write file
					$this->write_translation_file($file_path, $translations);
				}
				
				break;
			}
		}
	}
	
	
	/**
	 */
	public function delete_index($text_index, $folder){
		
		$this->require_delete_translation_permission();
		
		$folder = $this->check_path($folder);
		
		$text_index = trim($text_index);
		
		if(!$text_index)
			throw new transtable_exception("Error. Old or new index name not set.");
		
		$translations = $this->get_all_translations($folder);
		
		// for each translation file in folder
		foreach ($translations as $folder => $data) {
				
			if($folder == $folder){
				foreach ($data['translations'] as $file_name => $translations) {
		
					// full path to file with translations
					$file_path = $this->check_path($folder . $file_name, 'return_absolute_path');
						
					// delete index
					eval('unset($translations' . $this->get_php_index($text_index) . ');');
						
					// write file
					$this->write_translation_file($file_path, $translations);
				}
				
				break;
			}
		}
		
		return 1;
	}
	
	
	/**
	 */
	public function delete_translation($text_index, $folder, $file){
	
	}
	
	
	protected function require_edit_index_permission(){
	
		if(!$this->config['enable_edit_index'])
			throw new transtable_exception("Editing indexes not enabled.");
	}
	
	protected function require_delete_translation_permission(){
	
		if(!$this->config['enable_delete_translation'])
			throw new transtable_exception("Deleting translations is not enabled.");
	}
	
	/**
	 * Changes a key in an array while maintaining the order if possible.
	 * 
	 * @param array $array
	 * @param string $old_text_index
	 * @param string $new_text_index
	 * @return array
	 */
	protected function replace_array_key($array, $old_text_index, $new_text_index){
		
		// if indexes are same
		if($old_text_index == $new_text_index)
			return $array;
		
		// if new index is subindex
		//if()
			
		// if new index already exists
		
		
		// if old key is array
		if(strpos($old_text_index, $this->config['array_delimiter']) !== false){
			$all_old_indexes = explode($this->config['array_delimiter'], $old_text_index);
			$count_all_old_indexes = count($all_old_indexes);
			$old_text_index_temp = $old_text_index;
			
			$old_php_index = $this->get_php_index($old_text_index);
			$new_php_index = $this->get_php_index($new_text_index);
			
			
			// translation text
			eval('$translation = $array' . $old_php_index . ';');
			// delete old value
			eval('unset($array' . $old_php_index . ');');
			// set value with new index
			eval('$array' . $new_php_index . ' = $translation;');
			
			for ($i = 1; $i < $count_all_old_indexes; $i++) {
				// bla|bla|bla => bla|bla
				$old_text_index_temp = substr($old_text_index_temp, 0, strrpos($old_text_index_temp, $this->config['array_delimiter']));
				// bla|bla => ['bla']['bla']
				$old_php_index_temp = $this->get_php_index($old_text_index_temp);
				
				eval('$tval = $array' . $old_php_index_temp . ';');
				
				// if $tval is empty array unset it
				if(!$tval && is_array($tval))
					eval('unset($array' . $old_php_index_temp . ');');
				else
					break;
			}
		}
		// if new key is array
		else if(strpos($new_text_index, $this->config['array_delimiter']) !== false){
			
			$new_php_index = $this->get_php_index($new_text_index);
			
			// translation text
			$translation = $array[$old_text_index];
			// delete old value
			unset($array[$old_text_index]);
			// set value with new index
			eval('$array' . $new_php_index . ' = $translation;');
		}
		else{
			$keys = array_keys($array);
			$index = array_search($old_text_index, $keys);
		
			if ($index !== false) {
				$keys[$index] = $new_text_index;
				$array = array_combine($keys, $array);
			}
		}
		
		return $array;
	}
}


/**
 * Exception transtable_exception
 */
class transtable_exception extends Exception{}


/**
 * Writes php code of translation files.
 * Used to write php translation files.
 */
function transtable_echo_translation_array($translations, $var_name, $arr_level = ''){

	foreach ($translations as $index => $translation){

		$arr_level1 = $arr_level . "['" . $index . "']";

		if(is_array($translation)){
			transtable_echo_translation_array($translation, $var_name, $arr_level1);
		}
		else
			echo '$' . $var_name . $arr_level1 . " = '" . transtable_addslashes($translation) . "';\n";
	}

}


/**
 * Adds slashes
 */
function transtable_addslashes($translation){
	return str_replace("'", '\\\'', $translation);
}


/**
 * Removes extension from file name.
 */
function transtable_strip_extension($file_name){
	return substr($file_name, 0, strrpos($file_name, '.'));
}
