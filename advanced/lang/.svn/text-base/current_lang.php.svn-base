<?php 
//echo $_SESSION['lang'].'_'.$param['lang'];

if (!empty($param['lang'])){
	
	$lang_suf=$param['lang'];
	
}
elseif(!empty($_SESSION['lang'])){
	
	$lang_suf=$_SESSION['lang'];
	
}
else $lang_suf='en';


require_once("lang_".$lang_suf.".php");
//require_once("lang_ru.php");	

?>