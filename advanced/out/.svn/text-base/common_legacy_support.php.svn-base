<?php

// Determine if gd2 is installed
$GD2INSTALLED = true;//IsGD2Installed();

// Old versions don't support 'ImageCreateTrueColor'
if (!function_exists('ImageCreateTrueColor'))
{
	function ImageCreateTrueColor($width, $height)
	{
		return ImageCreate($width, $height);
	}
}

function IsGD2Installed()
{
	// Load phpinfo
	ob_start();
	phpinfo(INFO_MODULES);
	$PHPINFO_CONTENTS = ob_get_contents();
	ob_end_clean();

	// Search for GD Version
	$PosGDVersion = strpos($PHPINFO_CONTENTS, "GD Version");
	if ($PosGDVersion)
	{
		$Str = Substr($PHPINFO_CONTENTS, $PosGDVersion);
	
		// Strip html tags
		$Str = strip_tags($Str);
	
		// Is GD 2?
		if ($Str[10] == '2')
		{
			return true;
		}
	}

	return false;
}

?>