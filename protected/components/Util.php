<?php
class Util
{
	public static function dbNow() {
//		return date('Y-m-d H:i:s');
		return new CDbExpression("NOW()");
	}

	public static function time() {
    	$command= Yii::app()->db->createCommand("SELECT UNIX_TIMESTAMP( NOW( ) ) AS `time` ");
    	$rs = $command->query();
    	$data = $rs->read();
    	return $data['time'];
	}


	public static function createCriteriaForChatHistory($year, $month, $day){
		$opt = array();
		$opt['year'] = $year;
		if(!empty($month)){
			if($month < 10) $month = '0'.$month;
			$opt['month'] = $month;
		}
		if(!empty($day))
		$opt['day'] = $day;
		return $opt;
	}     
	
	public static function getBrowserData($userAgent) {
		$data = array();
		if ( stristr($userAgent, 'Firefox') ) { 
			$data['browser'] = 'ff';
			$search ="Firefox\/";
		}
		elseif ( stristr($userAgent, 'Chrome') ) $data['browser'] = 'chrome';
		elseif ( stristr($userAgent, 'Safari') ) $data['browser'] = 'safari';
		elseif ( stristr($userAgent, 'Opera') ) { 
			$data['browser'] = 'opera';
			$search ="Opera\/";
		}
		elseif ( stristr($userAgent, 'MSIE') ) { 
			$data['browser'] = 'ie';
			$search ="MSIE ";
		}
                else
                    $data['browser'] = 'other';
                
		$data['version'] = null;
		if(isset($search)) {
			preg_match("/".$search."([\d]{1,}\.[\d]{1,})/", $userAgent, $ll);
			$data['version'] = $ll[1];
		}		
		return $data;
	}
        /**
         * Function to resize and save images to avatar
         * Value of standart_avatar_width is from config
         * 
         * @param object $image instance of CUploadedFile
         * @param string $savePath path to save resized img
         */
	public static function resizeImage($image, $savePath)
	{
		$file = $image->getTempName();

		if($image->getType() == 'image/jpeg'){
			$src = imagecreatefromjpeg($file);
		}
		if($image->getType() == 'image/png'){
			$src = imagecreatefrompng($file);
		}
		if($image->getType() == 'image/gif'){
			$src = imagecreatefromgif($file);
		}

		$w_src = imagesx($src);
		$h_src = imagesy($src);

		if($w_src != $h_src)
		{
			if($w_src > $h_src)
			{
				$new_w = $h_src;
				$new_h = $h_src;
			}
			else
			{
				$new_h = $w_src;
				$new_w = $w_src;
			}
			$new_src = imagecreatetruecolor($new_w, $new_h);
			imagecopy($new_src, $src, 0, 0, 0, 0, $new_w, $new_h);
			$src = $new_src;
			$w_src = $new_w;
			$h_src = $new_h;
		}

		if($w_src < Yii::app()->params['standart_avatar_width']){
			$h_dest = $h_src;
			$w_dest = $w_src;
		} else {
			$h_dest = Yii::app()->params['standart_avatar_width'];
			$w_dest = Yii::app()->params['standart_avatar_width'];
		}

		$dest = imagecreatetruecolor($w_dest, $h_dest);

		$source = ($new_src) ? $new_src : $src;
		imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

		if($image->getType() == 'image/jpeg'){
			imagejpeg($dest, $savePath);
		}
		if($image->getType() == 'image/png'){
			imagepng($dest, $savePath);
		}
		if($image->getType() == 'image/gif'){
			imagegif($dest, $savePath);
		}

		imagedestroy($source);
		imagedestroy($dest);
	}
        
        public static function getMonthName($date)
        {
            switch($date)
            {
                case 1: 
                    $month = 'Jan';
                    break;
                case 2: 
                    $month = 'Feb';
                    break;
                case 3: 
                    $month = 'Mar';
                    break;
                case 4: 
                    $month = 'Apr';
                    break;
                case 5: 
                    $month = 'May';
                    break;
                case 6: 
                    $month = 'Jun';
                    break;
                case 7: 
                    $month = 'Jul';
                    break;
                case 8: 
                    $month = 'Aug';
                    break;
                case 9: 
                    $month = 'Sep';
                    break;
                case 10: 
                    $month = 'Oct';
                    break;
                case 11: 
                    $month = 'Nov';
                    break;
                case 12: 
                    $month = 'Dec';
                    break;
            }
            return $month;
        }
        
        public static function getStatesList()
        {
            return array(
                "International",
                "Alabama",
                "Alaska",
                "Arizona",
                "Arkansas",
                "California",
                "Colorado",
                "Connecticut",
                "Delaware",
                "District of Columbia",
                "Florida",
                "Georgia",
                "Hawaii",
                "Idaho",
                "Illinois",
                "Indiana",
                "Iowa",
                "Kansas",
                "Kentucky",
                "Louisiana",
                "Maine",
                "Maryland",
                "Massachusetts",
                "Michigan",
                "Minnesota",
                "Mississippi",
                "Missouri",
                "Montana",
                "Nebraska",
                "Nevada",
                "New Hampshire",
                "New Jersey",
                "New Mexico",
                "New York",
                "North Carolina",
                "North Dakota",
                "Ohio",
                "Oklahoma",
                "Oregon",
                "Pennsylvania",
                "Rhode Island",
                "South Carolina",
                "South Dakota",
                "Tennessee",
                "Texas",
                "Utah",
                "Vermont",
                "Virginia",
                "Washington",
                "West Virginia",
                "Wisconsin",
                "Wyoming",
                "Alberta",
                "British Columbia",
                "Manitoba",
                "New Brunswick",
                "Newfoundland and Labrador",
                "Nova Scotia",
                "Ontario",
                "Prince Edward Island",
                "Quebec",
                "Saskatchewan",
            );
        }

        public static function getCountryList()
        {
            return array(            
                "Albania"=>"Albania",
                "Algeria"=>"Algeria",
                "Angola"=>"Angola",
                "Anguilla"=>"Anguilla",
                "Antigua"=>"Antigua",
                "Argentina"=>"Argentina",
                "Armenia"=>"Armenia",
                "Aruba"=>"Aruba",
                "Australia"=>"Australia",
                "Austria"=>"Austria",
                "Azerbejan"=>"Azerbejan",
                "Bahamas"=>"Bahamas",
                "Bahrain"=>"Bahrain",
                "Bangladesh"=>"Bangladesh",
                "Barbados"=>"Barbados",
                "Barbuda"=>"Barbuda",
                "Belarus"=>"Belarus",
                "Belgium"=>"Belgium",
                "Belize"=>"Belize",
                "Bermuda"=>"Bermuda",
                "Bolivia"=>"Bolivia",
                "Bonaire"=>"Bonaire",
                "Bosnia"=>"Bosnia",
                "Botswana"=>"Botswana",
                "Brazil"=>"Brazil",
                "British Virgin Islands"=>"British Virgin Islands",
                "Brunei"=>"Brunei",
                "Bulgaria"=>"Bulgaria",
                "Burkina Faso"=>"Burkina Faso",
                "Cambodia"=>"Cambodia",
                "Cameroon"=>"Cameroon",
                "Canada"=>"Canada",
                "Cape Verde"=>"Cape Verde",
                "Cayman Islands"=>"Cayman Islands",
                "Central African Republic"=>"Central African Republic",
                "Central America"=>"Central America",
                "Chad"=>"Chad",
                "Chile"=>"Chile",
                "China"=>"China",
                "China (Beijing)"=>"China (Beijing)",
                "China (Shanghai)"=>"China (Shanghai)",
                "China(GuangZhou)"=>"China(GuangZhou)",
                "Colombia"=>"Colombia",
                "Comoros"=>"Comoros",
                "Congo Brazzaville"=>"Congo Brazzaville",
                "Costa Rica"=>"Costa Rica",
                "Croatia"=>"Croatia",
                "Curacao"=>"Curacao",
                "Cyprus"=>"Cyprus",
                "Czech Republic"=>"Czech Republic",
                "Denmark"=>"Denmark",
                "Djibouti"=>"Djibouti",
                "Dominican Republic"=>"Dominican Republic",
                "Dubai"=>"Dubai",
                "Ecuador"=>"Ecuador",
                "Egypt"=>"Egypt",
                "El Salvador"=>"El Salvador",
                "Equatorial Guinea"=>"Equatorial Guinea",
                "Eritrea"=>"Eritrea",
                "Estonia"=>"Estonia",
                "Ethiopia"=>"Ethiopia",
                "Finland"=>"Finland",
                "France"=>"France",
                "Gabon"=>"Gabon",
                "Gambia"=>"Gambia",
                "Germany"=>"Germany",
                "Ghana"=>"Ghana",
                "Greece"=>"Greece",
                "Grenada"=>"Grenada",
                "Guadeloupe"=>"Guadeloupe",
                "Guatemala"=>"Guatemala",
                "Haiti"=>"Haiti",
                "Holland &amp;amp; Luxembourg"=>"Holland &amp;amp; Luxembourg",
                "Honduras"=>"Honduras",
                "Hong Kong"=>"Hong Kong",
                "Hungary"=>"Hungary",
                "Iceland"=>"Iceland",
                "India"=>"India",
                "Indonesia"=>"Indonesia",
                "Iran"=>"Iran",
                "Iraq"=>"Iraq",
                "Ireland"=>"Ireland",
                "Israel"=>"Israel",
                "Italy"=>"Italy",
                "Ivory Coast"=>"Ivory Coast",
                "Jamaica"=>"Jamaica",
                "Japan"=>"Japan",
                "Jordan"=>"Jordan",
                "Kenya"=>"Kenya",
                "Kirgizstan"=>"Kirgizstan",
                "Korea"=>"Korea",
                "Kuwait"=>"Kuwait",
                "Laos"=>"Laos",
                "Latvia"=>"Latvia",
                "Lebanon"=>"Lebanon",
                "Lesotho"=>"Lesotho",
                "Liberia"=>"Liberia",
                "Libya"=>"Libya",
                "Lithuani"=>"Lithuani",
                "Lithuania"=>"Lithuania",
                "Luxembourg"=>"Luxembourg",
                "Macau"=>"Macau",
                "Macedonia"=>"Macedonia",
                "Madagascar"=>"Madagascar",
                "Malawi"=>"Malawi",
                "Malaysia"=>"Malaysia",
                "Malta"=>"Malta",
                "Martinique"=>"Martinique",
                "Mauritania"=>"Mauritania",
                "Mauritius"=>"Mauritius",
                "Mexico"=>"Mexico",
                "Moldova"=>"Moldova",
                "Monserrat"=>"Monserrat",
                "Morocco"=>"Morocco",
                "Mozambique"=>"Mozambique",
                "Myanmar"=>"Myanmar",
                "Namibia"=>"Namibia",
                "Nepal"=>"Nepal",
                "Netherlands"=>"Netherlands",
                "New Zealand"=>"New Zealand",
                "Nicaragua"=>"Nicaragua",
                "Niger"=>"Niger",
                "Nigeria"=>"Nigeria",
                "Norway"=>"Norway",
                "Oman"=>"Oman",
                "Pakistan"=>"Pakistan",
                "Panama"=>"Panama",
                "Paraguay"=>"Paraguay",
                "Peru"=>"Peru",
                "Philippines"=>"Philippines",
                "Poland"=>"Poland",
                "Portugal"=>"Portugal",
                "Puerto Rico"=>"Puerto Rico",
                "Quatar"=>"Quatar",
                "Romania"=>"Romania",
                "Russia"=>"Russia",
                "Rwanda"=>"Rwanda",
                "Sao Tome &amp;amp; Principe"=>"Sao Tome &amp;amp; Principe",
                "Saudi Arabia"=>"Saudi Arabia",
                "Senegal"=>"Senegal",
                "Seychelles"=>"Seychelles",
                "Sierra Leone"=>"Sierra Leone",
                "Singapore"=>"Singapore",
                "Slovakia"=>"Slovakia",
                "Slovenia"=>"Slovenia",
                "Somalia"=>"Somalia",
                "South Africa"=>"South Africa",
                "Spain"=>"Spain",
                "Sri Lanka"=>"Sri Lanka",
                "St Kitts and Nevis"=>"St Kitts and Nevis",
                "St Lucia"=>"St Lucia",
                "St Vincent and the Grenadines"=>"St Vincent and the Grenadines",
                "St. Barts"=>"St. Barts",
                "St. Eustatius"=>"St. Eustatius",
                "St. Martin and St. Maarten"=>"St. Martin and St. Maarten",
                "Sudan"=>"Sudan",
                "Swaziland"=>"Swaziland",
                "Sweden"=>"Sweden",
                "Switzerland"=>"Switzerland",
                "Syria"=>"Syria",
                "Taiwan"=>"Taiwan",
                "Tajikistan"=>"Tajikistan",
                "Tanzania"=>"Tanzania",
                "Thailand"=>"Thailand",
                "Togo"=>"Togo",
                "Trinidad and Tobago"=>"Trinidad and Tobago",
                "Tunisia"=>"Tunisia",
                "Turkey"=>"Turkey",
                "Turkmenistan"=>"Turkmenistan",
                "Turks and Caicos Islands"=>"Turks and Caicos Islands",
                "UAE (United Arab Emirates)"=>"UAE (United Arab Emirates)",
                "Uganda"=>"Uganda",
                "Ukraine"=>"Ukraine",
                "United Kingdom"=>"United Kingdom",
                "United States"=>"United States",
                "Upper Volta"=>"Upper Volta",
                "Uruguay"=>"Uruguay",
                "Uzbekistan"=>"Uzbekistan",
                "Venezuela"=>"Venezuela",
                "Vietnam"=>"Vietnam",
                "Western Sahara"=>"Western Sahara",
                "Yugoslavia"=>"Yugoslavia",
                "Zaire"=>"Zaire",
                "Zambia"=>"Zambia",
                "Zimbabwe"=>"Zimbabwe",
            );
        }

        public static function getMonthList()
        {
            return array(
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            );
        }
        
        public static function getWPStatus($wp_id)
        {
            $url = "http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=".$wp_id;
            
            $curl = curl_init($url);
            
            curl_setopt ($curl, CURLOPT_HEADER, 0);
            curl_setopt ($curl, CURLOPT_POST, 0);            
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec ($curl);
            curl_close ($curl);
                        
            if(preg_match('/.*pic\/webCallavailable.jpg\".*/', $result))
                return 'online';
            else
                return 'offline';
        }
}