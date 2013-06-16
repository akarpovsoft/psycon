<?php

if (!defined('CLASS_SETTER_INCLUDED')) {
define ('CLASS_SETTER_INCLUDED', true);

/**
 * Setter class
 *
 * @name         setter class
 * @author       Vitaliy Demidov  <recipe(at)ukr.net>   <http://sup4.com>
 * @copyright    (c) Dmitry Oleinikov   http://dooxcms.com
 * @since        2006
 * @version      $Id$
 */
class setter {

	function setter(){}
	
	function set($var, $value) {
		$this->$var = $value;
	}
	
	function get($var){
		return isset($this->$var) ? $this->$var : null;
	}

	function unset_var($var){
		if (isset($this->$var)) unset($this->$var);
	}
	
	function isset_var($var) {
		return isset($this->$var);
	}
}

}
?>