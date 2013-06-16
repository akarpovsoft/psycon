<?php

if (!defined('CLASS_AJAXRESPONSEJSON_INCLUDED')) {
define ('CLASS_AJAXRESPONSEJSON_INCLUDED', true);

require_once  dirname(__FILE__) . '/setter.php';
require_once  dirname(__FILE__) . '/JSON.php';

/**
 * Ajax Response JSON class (Deprecated)
 *
 * @name         Ajax Response JSON class
 * @author       Vitaliy Demidov  <recipe(at)ukr.net>   <http://sup4.com>
 * @copyright    (c) Dmitry Oleinikov   http://dooxcms.com
 * @since        2007
 * @version      $Id: AjaxResponseJSON.php 1137 2009-05-10 00:05:27Z demidoff $
 * @deprecated
 */
class AjaxResponseJSON {
	/**
	 * JSON encoder
	 *
	 * @var      object
	 * @access   private
	 */
	var $JSON;

	/**
	 * Output ajax object
	 *
	 * @var      object
	 * @access   private
	 */
	var $setter;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function AjaxResponseJSON(){
		$this->JSON    = new Services_JSON();
		$this->clear();
		$obStatus = ob_get_status();
    	if ( $obStatus===false || (is_array($obStatus) && (count($obStatus)==0)) ) ob_start();
	}

	function set($n , $v) {
		$this->setter->set($n, $v);
	}

	function get($n) {
		return $this->setter->get($n);
	}

	function unset_var($n){
		$this->setter->unset_var($n);
	}

	function isset_var($n){
		return $this->setter->isset_var($n);
	}

	function clear() {
		$this->setter = new setter();
	}

	/**
	 * Send JSON responce
	 *
	 * @param     string     $status
	 * @param     string     $error   (OPTIONAL)   Error message
	 * @access    public
	 */
	function send($status, $error=null){
		$json  =& $this->JSON;
		$debug = '';
    	$this->setter->set('status', $status);
		if (defined('AJAXDEBUG') && AJAXDEBUG == true)
		{
			if (($ob = ob_get_contents()) === false) $ob = '';
			$debug =
			"AJAXDEBUG:defined\r\n".
			"OB_CONTENTS:\r\n".$ob."\r\n".
			"AJAX_OBJECT:\r\n".$json->encode($this->setter)."\r\n".
			"ERROR: \r\n";
		}
		if (isset($error) or $ob!='')
		{
			$this->setter->set('error', $debug . (isset($error) ? $error : "" ) );
		}
		ob_clean();
		echo $json->encode($this->setter);
		die();
	}

	/**
	 * Converts specified string to UTF8
	 *
	 * @var     string     $str
	 * @var     string     $charset
	 * @return  string
	 */
	function convert($str, $charset='WINDOWS-1251') {
		return iconv($charset, 'utf-8', $str);
	}
}



}
?>