<?php
/**
 * Save user preferences (favorite reader,default page,sort by field,order) in COOKIES
 * @version 1.0
 * @author Sushko Dmitriy
 * 
 */

class UserPreference extends CApplicationComponent {
	
	//Class constants
	const PREF_FAVORITE 		   = "Favorite"; //Favorite Reader for ex.

	//Default page constants
	const PREF_DEFPAGE             = "DefaultPage"; 
	const PREF_DEFPAGE_HOME        = "DefPageHome";
	const PREF_DEFPAGE_JOIN        = "DefPageJoin";
	const PREF_DEFPAGE_CHAT        = "DefPageChat";
	const PREF_DEFPAGE_CHATMAIN    = "DefPageMain";
	const PREF_DEFPAGE_DOWNLINE    = "DefDownline";
	const PREF_DEFPAGE_LEADS       = "DefLeads";
	const PREF_DEFPAGE_MYWEBSITE   = "DefPageMain";
	const PREF_DEFPAGE_SPONSOR     = "DefSponsor";
	const PREF_DEFPAGE_RESOURCES   = "DefResources";
	const PREF_DEFPAGE_COMMUNICATE = "DefCommunicate";
	const PREF_DEFPAGE_ADMIN       = "DefPageAdmin";
	const PREF_DEFPAGE_REPOSITORY  = "DefPageRepository";
	const PREF_DEFPAGE_DELIVERY    = "DefPageDelivery";
	const PREF_DEFPAGE_REPORTS     = "DefPageReports";
	const PREF_DEFPAGE_MESSAGES    = "DefPageMessages";
	const PREF_DEFPAGE_READERS     = "DefPageReaders";
	
	
	const PREF_GLOBAL              = "Global";
	const PREF_CHATHISTORY         = "ChatHistoryChart";
	const PREF_CHAT                = "ChatChart";
	const PREF_ORDERS              = "OrdersChart";
	const PREF_OPERATORS           = "OperatorsChart";
	const PREF_MESSAGES            = "MessageChart";
	const PREF_HISTORY             = "HistoryChart";
	const PREF_PAYMENTS            = "PaymentsChart";
	const PREF_START_FROM_PAGE     = "start_from_page";
	const PREF_SORT_ASCENDING      = "sort_ascending";
	const PREF_SORT_BY_FIELD       = "sort_by_field";
	const PREF_FILTER              = "filter";
	const PREF_FRAMES              = "Frames";
	const PREF_LASTPAGE            = "LastPage";
	
	private $model;
	
	/**
	 * Initialize method used to create component
	 * model instance to save the creation time 
	 *
	 */
	public function init() {
		parent::init();
		$this->model = new preference();
	}
	
	
	/**
	 * Looking for cookie with name of preference
	 * and save value to it. If no cookie with this name
	 * attempt to create it. 
	 *
	 * @param Preference type string $title
	 * @param Name of preference string $name
	 * @param Preference value string $value
	 * @return true if User is Logged In 
	 * 		   false otherwise
	 */
	public function preferenceSet($title, $name, $value) {
		
		// Get user id
		$user_id	=  $this->GetUserID();
		
		if (empty($user_id))
			return false;
		
		$cookie=Yii::app()->request->cookies[$title.'.'.$name];
		$cookie_value = $cookie->value;
		
		if(empty($cookie_value)) {
			$cookie=new CHttpCookie($title.'.'.$name,$value);
			$cookie->expire = time() + 216000;
			Yii::app()->request->cookies[$title.'.'.$name] = $cookie;
		}	
		return true;
	}

	/**
	 * Looking for cookie with name of preference
	 * if it exists return cookie value else
	 * return default value
	 *
	 * @param string $title
	 * @param string $name
	 * @param string $default_value
	 * @return string
	 */
	public function preferenceGet($title, $name, $default_value = "") {
		
		// Get user id
		$user_id	=  $this->GetUserID();
		
		if (empty($user_id))
			return false;
	
		$cookie=Yii::app()->request->cookies[$title.'.'.$name];
		$cookie_value = $cookie->value;
	
		if(empty($cookie_value))
			return $default_value;
		else 
			return $cookie_value;
	}
	
	/**
	 * Declare preference
	 *
	 * @param string $title
	 * @param string $name
	 * @param string $default_value
	 * @return string (Cookie value)
	 */
	public function preferenceDeclare($title, $name, $default_value="") {
		
		if (isset($_GET[$name])) {
			preferenceSet($title, $name, CHtml::encode($_GET[$name]));
		}
		elseif (isset($_POST[$name])) {
			preferenceSet($title, $name, CHtml::encode($_POST[$name]));
		}
	
		return $this->preferenceGet($title, $name, $default_value);
	}
	
	/**
	 * See if UserID registered in session 
	 * if registered return 0 else return 
	 * UserID
	 *
	 * @param unknown_type $user_id
	 * @return integer 0 - if non-registered
	 * UserID - if registered
	 */
	private function getUserID() {
		
		$login_operator_id = Yii::app()->session['login_operator_id'];
		
		if(!isset($login_operator_id))
			return 0;
		else 
			return $login_operator_id;
			
	}

}
?>