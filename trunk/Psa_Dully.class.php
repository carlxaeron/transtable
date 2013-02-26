<?php
/**
 * Simple template engine class.
 * 
 * LICENSE:
 * 
 * This library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with This library. If not, see <{@link http://www.gnu.org/licenses/}>.
 *
 * @link http://code.google.com/p/phpstartapp/
 * @author Bojan Mauser <bmauser@gmail.com>
 * @copyright Bojan Mauser 2009
 * @package psa
 * @version $Id$
 */


/**
 * Simple template engine class.
 * I called this class Dully to be opposite to {@link http://www.smarty.net Smarty}.
 * I needed some simple name and something like '<i>simple_template</i>' seemed too long.
 * It has few methods that are similar to those in Smarty template engine.
 * 
 * All that Dully does is to put values from associative array into local namespace and 
 * includes .php (template) file. There are no special template tags, just use PHP code 
 * blocks to echo values. Templates are ordinary PHP files and you can write them 
 * as any other .php script, but keep in mind that the point of template engine
 * should be to separate business logic from presentation. 
 * So you should not put any logic (calculations, getting data from a database ...)
 * into templates. With {@link http://www.smarty.net Smarty} that is easier to achieve 
 * and it has many advanced features so I suggest that you use Smarty as template engine. 
 * But if you know what you are doing or need simple and fast template
 * engine or don't want to learn Smarty's tags you can use Dully.
 * Dully class doesn't include anything from PSA so you can use it as template engine in any PHP application.
 * 
 * This class is inspired from {@link http://www.massassi.com/php/articles/template_engines/} and there are
 * some interesting thoughts about template engines.
 * 
 * <b>Examples</b>
 * 
 * <b>1)</b> Simple example with one template file:
 * 
 * Template file <samp>template.tpl</samp>:
 * {@example documentation_examples/templates/template.tpl}
 * 
 * .php file:
 * {@example documentation_examples/dully1.php}
 * 
 * The printout of the above .php script will be:
 * <pre>
 * This is text from template file template.php
 * My car is Black.
 * Something else: bla bla bla
 * </pre>
 *
 * <b>Note:</b> you can write <kbd><?= $some_name ?></kbd> instead of <kbd><?php echo $some_name ?></kbd>. 
 * Use of this shortcut requires <var>short_open_tag</var> PHP ini option to be on.
 * 
 * <b>2)</b> You can put one rendered template into another:
 * 
 * template file <samp>template1.tpl</samp>: 
 * {@example documentation_examples/templates/template1.tpl}
 * 
 * template file <samp>template2.tpl</samp>: 
 * {@example documentation_examples/templates/template2.tpl}
 * 
 * .php file: 
 * {@example documentation_examples/dully2.php}
 * 
 * The above example will output:
 * <pre>
 * This is text from template1.php
 * This is text from template2.php
 * bla bla bla
 * </pre>
 * 
 * <b>3)</b> In this kind of templates it's nice to use alternative PHP syntax for control structures.
 * See details in PHP {@link http://www.php.net/manual/en/control-structures.alternative-syntax.php manual}.
 * <code>
 * <? if ($variable = 'abcd'): ?> 
 * 	<h1>this is something</h1> 
 * 	<p> 
 * 		bla bla
 * 	</p>  
 * <? endif ?>
 * 
 * <? foreach ($array as $value): ?>
 * 	<div><?= $value['email']) ?></div>
 * 	<div><?= $value['phone_number']) ?></div>
 * <? endforeach ?>
 * </code>
 * 
 * 
 * @see Psa_Dully_View
 * @see Psa_Smarty
 */
class Psa_Dully{
	
	/**
	 * Array that holds template values
	 * 
	 * @var array
	 * @ignore
	 */
	protected $template_values = array();

	
	/**
	 * Folder with templates. 
	 * Without '/' at the end.
	 * 
	 * @var string
	 */
	public $template_dir;
	
	
	/**
	 * Sets {@link $template_dir} member value if passed as argument
	 * 
	 * @param string $template_dir the directory with templates
	 */
	function __construct($template_dir = null){
		if($template_dir)
			$this->template_dir = $template_dir;
	}

	
	/**
	 * Assigns values to the templates.
	 * See examples in {@link Psa_Dully} class description.
	 * 
	 * @param string $name variable name in template
	 * @param mixed $value variable value in template
	 * @see display()
	 * @see fetch()
	 */
	function assign($name, $value){
		$this->template_values[$name] = $value;
	}

	
	/**
	 * Returns the fetched template as string.
	 * See examples in {@link Psa_Dully} class description.
	 * 
	 * @param string $template_filE template file name (path)
	 * @return string fetched (rendered) template
	 * @see display()
	 */
	function fetch($template_filE){
		
		// extract the template_values to local namespace
		extract($this->template_values);
		
		// start output buffering
		ob_start();
		
		// include the file
		include $this->template_dir . '/' . $template_filE;
		
		// get the contents and clean the buffer
		return ob_get_clean();
	}
        
	
	/**
	 * Outputs the fetched template.
	 * See examples in {@link Psa_Dully} class description.
	 * 
	 * @param string $template_file template file name (path)
	 * @see fetch()
	 */
	function display($template_file){
		echo $this->fetch($template_file);
	}
}

