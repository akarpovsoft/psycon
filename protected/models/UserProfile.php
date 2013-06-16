<?php

/**
 * SignupForm class.
 * SignupForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserProfile extends CActiveRecord
{
	public $first_name;
	public $last_name;
	public $confirmemail;
	public $cc_number;
	public $cc_exp_month;
	public $cc_exp_year;
	public $chat_amount;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 
    public function tableName()
    {
        return PsyStaticDataProvider::getTableName("user");
    }

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login, password, first_name, last_name, emailaddress, confirmemail', 'required'),
			array('emailaddress, confirmemail', 'email'),
			// password needs to be authenticated
//			array('password', 'checkNewUser'),
			array('emailaddress', 'confirmEmail'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'first_name' => 'First Name (Credit Card Billing First Name):',
			'last_name' => 'Last Name (Credit Card Billing Last Name):',
			'hear' => 'How did you hear about us?',
			'login' => 'Login:',
			'password' => 'Password:',
			'website' => 'Name of website that brought you to this form:',
			'emailaddress' => 'Email Address:',
			'confirmemail' => 'Confirm Email:',
			'dob' => 'Date of Birth:',
			'gender' => 'Gender:',
			'address' => 'Address:(MUST MATCH BILLING ADDRESS ON CREDIT CARD)',
			'city' => 'City:',
			'state' => 'State:',
			'zip' => 'Zipcode:',
			'country' => 'Country:',
			'amount' => 'Amount:',
			'cc_number' => 'Credit Card:',
			'cc_exp_date' => 'Exp. Date:',
			'cvv_code' => 'Security Code: CVV2/CVC2'
		);
	}
	
	/**
	 * Declares primary key.
	 */
	public function primaryKey()
	{
		return 'rr_record_id';
	}

	/**
	 * Checks new user.
	 * This is the 'checkNewUser' validator as declared in rules().
	 */
	public function checkNewUser($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
			$this->checkUserUnique();
		if(!$this->hasErrors())
			$this->checkCreditCard();
		if(!$this->hasErrors())
			$this->checkIfBanned();
	}

	/**
	 * ConfirmEmail.
	 * This is the 'checkNewUser' validator as declared in rules().
	 */
	
	public function confirmEmail($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			if($this->emailaddress!=$this->confirmemail) 
				$this->addError("confirmemail", "Emails are not equal");
		}
	}
	
	
	/**
	 * Register user.
	 * 
	 */
	public function registerUser()
	{
		$connection=Yii::app()->db;
		
		// Insert into user profile 
		$sql="INSERT INTO ".PsyStaticDataProvider::getTableName("user")." (login, password, name, hear, type) VALUES(:login, :password, :name, :hear, 'client')";
		$command=$connection->createCommand($sql);
		$command->bindValue(":login", $this->login, PDO::PARAM_STR);
		$command->bindValue(":password", $this->password, PDO::PARAM_STR);
		$command->bindValue(":name", $this->first_name." ".$this->last_name, PDO::PARAM_STR);
		$command->bindValue(":hear", $this->hear, PDO::PARAM_STR);
		
		$rowCount=$command->execute();

		// Insert into credit card 
		// Insert into ban list 
		// Insert into cc transactions 

	}


}
