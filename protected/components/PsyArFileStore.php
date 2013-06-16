<?php
/* 
 * File Store for articles
 */
class PsyArFileStore
{
    private $basePath;
    public function __construct ($basePath)
    {
        $this->basePath = $basePath;
    }
    public function save ($filename, $var, $varName)
    {
        $str = self::convertToPHP($var, $varName);
        $f = fopen($this->basePath . DIRECTORY_SEPARATOR . $filename, "w");
        fwrite($f, $str);
        fclose($f);
    }
    public function load ($filename, $varName)
    {
	
        if(file_exists($this->basePath .DIRECTORY_SEPARATOR . $filename)) {
            include $this->basePath . DIRECTORY_SEPARATOR . $filename;
            return $$varName;
        }
        return false;
    }
    public function delete ($filename)
    {
        unlink($this->basePath . DIRECTORY_SEPARATOR . $filename);
    }
    protected function convertToPHP ($data, $var_name)
    {
        define(CR_LF, "\r\n");
        $text = "<?php" . CR_LF;
        if (is_array($data)) {
            $text .= "\$" . $var_name . "= array();" . CR_LF;
            $keys = array_keys($data);
            foreach ($keys as $key) {
                $skey = (is_int($key)) ? $key : "'" . $key . "'";
                if (is_array($data[$key])) {
                    $text .= "\$" . $var_name . "[" . $skey . "] = array();" . CR_LF;
                    $keys2 = array_keys($data[$key]);
                    foreach ($keys2 as $key2) {
                        $skey2 = (is_int($key2)) ? $key2 : "'" . $key2 . "'";
                        if (! isset($data[$key][$key2])) {
                            $data[$key][$key2] = '';
                        }
                        $text .= "\$" . $var_name . "[" . $skey . "][" . $skey2 . "] = " . ((is_string($data[$key][$key2])) ? "\"" . addcslashes($data[$key][$key2], "\"\\\$") . "\"" : $data[$key][$key2]) . ";" . CR_LF;
                    }
                } else {
                    if (! isset($data[$key])) {
                        $data[$key] = '';
                    }
                    $text .= "\$" . $var_name . "[" . $skey . "] = " . ((is_string($data[$key])) ? "\"" . addcslashes($data[$key], "\"\\\$") . "\"" : $data[$key]) . ";" . CR_LF;
                }
            }
        } else {
            $text .= "\$" . $var_name . "=" . ((is_int($data)) ? $data : "'" . $data . "'") . ";" . CR_LF;
        }
        $text .= "?>";
        return $text;
    }
}
?>
