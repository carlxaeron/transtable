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
		$file_path_clean = realpath($this->config['root_dir'] . '/' . $file_path_relative);
		
		if(!$file_path_clean)
			throw new transtable_exception("File $file_path doesn't exists");
		
		// check if file is subdir
		$root_dir = realpath($this->config['root_dir']);
		if(strcmp(substr($file_path_clean, 0, strlen($root_dir)), $root_dir) !== 0)
			throw new transtable_exception("File $file_path_clean is not in subdirecory of $root_dir");
		
		
		${$this->config['var_name']} = array();
		
		// include the file
		include $file_path_clean;
	
		// set new value
		eval('${$this->config[\'var_name\']}' . $this->get_php_index($index) . ' = $translation;');
		
		// save new file
		$dully = new Psa_Dully(dirname(__FILE__) . '/templates');
		$dully->assign('t', ${$this->config['var_name']});
		$dully->assign('var_name', $this->config['var_name']);
		if(!file_put_contents($file_path_clean, $dully->fetch('lang_file.tpl')))
			throw new transtable_exception("Cannot write to file $file_path_clean");
		
		return 1;
	}
}


/**
 * Exception transtable_exception
 */
class transtable_exception extends Exception{}

