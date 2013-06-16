<?php
/**
 * Extended HTML helper.
 * Contain methods which help to create additional 
 * and specific HTML elements in views.
 * 
 * @version 1.0
 * @author Sushko Dmitriy
 * 
 */


class CExHtml extends CHtml {
    
    
    /**
     * Generate a drop down list 
     * with name of monthes as text
     * and number of month as value
     *
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @param string $first_standart if --Please Select-- element shown.Default to true.
     * @return string the generated drop down list
     */
    public static function selectStringMonthBox($name,$selected,$htmlOptions,$default='',$first_standart=true) {
        if($first_standart) {
           $data[] =  "-".Yii::t('lang','Please_Select')."-";
        }
        
        $data[1]  =Yii::t('lang','Jan');
        $data[2]  =Yii::t('lang','Feb');
        $data[3]  =Yii::t('lang','Mar');
        $data[4]  =Yii::t('lang','Apr');
        $data[5]  =Yii::t('lang','May');
        $data[6]  =Yii::t('lang','Jun');
        $data[7]  =Yii::t('lang','Jul');
        $data[8]  =Yii::t('lang','Aug');
        $data[9]  =Yii::t('lang','Sep');
        $data[10] =Yii::t('lang','Oct');
        $data[11] =Yii::t('lang','Nov');
        $data[12] =Yii::t('lang','Dec');

        // If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
        $select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;
    }
    
    
    /**
     * Generate a drop down list 
     * with number of month as text
     * and number of month as value
     * start with empty value element.
     * 
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @return string the generated drop down list
     */
    public static function selectCCMonthBox($name,$selected,$htmlOptions,$default='') {
        $data = array();
        $data['']='';
		for($i = 1; $i <13; $i++) {
			$data[$i] = $i;
		}
		// If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
		$select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;		
    }
    
    
    /**
     * Generate a drop down list 
     * with number of years as text
     * and number of years as value
     * start with empty value element.
     * 
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @return string the generated drop down list
     */
    public static function selectCCYearsBox($name,$selected,$htmlOptions,$default='') {
        $data = array();
        $data['']='';
		$year_current = date("Y", time());
		for($i = $year_current; $i <($year_current+15); $i++) {
			$data[substr($i,2,2)] = $i;
		}	
		// If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
		$select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;		
    }
    
    /**
     * Generate a drop down list 
     * with number of day as text
     * and number of day as value
     *
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @param string $first_standart if --Please Select-- element shown.Default to true.
     * @return string the generated drop down list
     */
    public static function selectDaysBox($name,$selected,$htmlOptions,$default='',$first_standart=true) {
        $data = array();
        if($first_standart) {
    	   $data[0]="-".Yii::t('lang','Please_Select')."-";
        }   
		for($i=0; $i<31; $i++) {
			$data[$i+1]=str_pad($i+1, 2, "0", STR_PAD_LEFT);
		}
        // If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
        $select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;
    }
    
    /**
     * Generate a drop down list 
     * with number of year as text
     * and number of year as value
     *
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @return string the generated drop down list
     */
    public static function selectYearsBox($name,$selected,$htmlOptions,$default='') {
        $start_year = date("Y")-18;
        $end_year = $start_year - 60;
		$data = array();
    	$data[0]="-".Yii::t('lang','Please_Select')."-";
		for($i=$start_year; $i>$end_year; $i--) {
			$data[$i]=$i;
		}
        // If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
        $select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;
    }
    
    /**
     * Generate a drop down list 
     * with number of year as text
     * and number of year as value
     *
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @return string the generated drop down list
     */
    public static function selectCYearsBox($name,$start_year,$end_year,$selected,$htmlOptions,$default='',$first_standart=true) {
        $data = array();
		if($first_standart) {
    	   $data[0]="-".Yii::t('lang','Please_Select')."-";
		}   
		for($i=$start_year; $i<=$end_year; $i++) {
			$data[$i]=$i;
		}
        // If no value, set as default
        if (empty($selected)) {
    		$selected = $default;
    	}
        $select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;
    }
    
    /**
     * Generate a drop down list 
     * which takes its elements from file
     *
     * @param string the input name
	 * @param string the selected value
     * @param array $htmlOptions Please refer to {@link CHtml::dropDownList}
     * @param string $default default value
     * @return string the generated drop down list
     */
    public static function selectBoxFromFile($file_path, $name, $selected, $htmlOptions, $default='') {
    	$data = array();
        $file = @fopen($file_path, "r");
    	if (!$file) {
    		return 0;
    	}
    	// If no value, set as default
    	if (empty($selected)) {
    		$selected = $default;
    	}
    	while (!feof($file)) {
    		$line = trim(fgets($file, 2500));
    		if (!empty($line)) {
    			$data[$line] = $line;
    		}
    	}
    	@fclose($file);
    	$select = self::dropDownList($name,$selected,$data,$htmlOptions);
        return $select;
    }
    
    
    public static function drawXPButton($text, $name, $action="", $align="left")
    { 
        $button = '<table border=0 cellspacing=0 cellpadding=0>
        <tr height=6>
        <td nowrap width=5 height=6 background="'.Yii::app()->params['http_addr'].'images/button_topleft.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5 height=6></td>
        <td nowrap height=6 background="'.Yii::app()->params['http_addr'].'images/button_top.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" height=6></td>
        <td nowrap width=5 height=6 background="'.Yii::app()->params['http_addr'].'images/button_topright.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5 height=6></td>
        </tr>
        <tr>
        <td nowrap width=5  background="'.Yii::app()->params['http_addr'].'images/button_left.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5></td>
        <td nowrap background="'.Yii::app()->params['http_addr'].'images/button_bg.gif">';
       
        if (!empty($action)) {
        	$button.= '<a class="TextButton" href="'.$action.'" ';
        	$button.= "onMouseOver=\"window.status='".$text."'; return true;\" onClick=\"window.status=''; return true;\" onMouseOut=\"window.status=''; return true;\" >";
        }
        else {
        	$button.= '<span class="TextButtonGray">';
        }
        $button.= "&nbsp;&nbsp;".$text."&nbsp;&nbsp;";
        
        if (!empty($action)) $button.= "</a>";
        else $button.= "</span>";
        
        $button.= '</td>
        <td nowrap width=5 background="'.Yii::app()->params['http_addr'].'images/button_right.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5></td>
        </tr>
        
        <tr height=5>
        <td nowrap width=5 height=5 background="'.Yii::app()->params['http_addr'].'images/button_bottomleft.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5 height=5></td>
        <td nowrap background="'.Yii::app()->params['http_addr'].'images/button_bottom.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif"></td>
        <td nowrap width=5 height=5 background="'.Yii::app()->params['http_addr'].'images/button_bottomright.gif"><img src="'.Yii::app()->params['http_addr'].'images/transp.gif" width=5 height=5></td>
        </tr>
        </table></td>';
        
        return $button;
    }
    
    
    
}

?>