<?php
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: ".$filetype);
$header='Content-Disposition: attachment; filename="'.$filename.'";';
header($header);
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$filesize);
readfile($localname);
?>
