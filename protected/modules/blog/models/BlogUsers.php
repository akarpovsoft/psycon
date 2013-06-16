<?php
class BlogUsers extends CActiveRecord
{
    const PER_PAGE = 20;
	/**
	 * The followings are the available columns in table 'blog_users':
	 * @var integer $id
	 * @var string $first_name
	 * @var string $last_name
	 * @var integer $approved
	 * @var string $register_date
         * @var integer $type   // 0 - Default user, 1 - Reader, 2 - Admin
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getDbConnection(){
            return Yii::app()->blog_db;
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blog_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('approved, register_date', 'required'),
			array('approved', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>150),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'blog_comments' => array(self::HAS_MANY, 'BlogComments', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'approved' => 'Approved',
			'register_date' => 'Register Date',
		);
	}

        /**
         * Register new user
         */
        public function userRegister(){
            $this->register_date = date('Y-m-d h:m:s');
            $this->approved = 0;
            $this->save();
        }

        /**
         * Validates user password (for login)
         *
         * @param <string> $password
         * @return <boolean>
         */
        public function validatePassword($password){
            return ($password===$this->password);
	}

        /**
         * Checking user existence in DB
         *
         * @param <string> $login
         * @return <boolean>
         */
        public static function uniqueCheck($login){
            $model = self::model()->find(array(
                'condition' => 'login = :login',
                'params' => array(':login' => $login)
            ));
            if(!empty($model))
                return false;
            else
                return true;
        }

        /**
         * Return current user by id
         *
         * @param <int> $id
         * @return <object>
         */
        public static function getBlogUser($id){
            return self::model()->findByPk($id);
        }

        /**
         * Return list of unapproved users
         *
         * @return <CActiveDataProvider>
         */
        public static function getUnapprovedUsers(){
            $criteria = new CDbCriteria;
            $criteria->condition = 'approved = 0';
            $dataProvider = new CActiveDataProvider('BlogUsers', array(
                        'criteria' => $criteria
            ));
            return $dataProvider;
        }

        public static function getReaders(){
            $criteria = new CDbCriteria;
            $criteria->condition = 'type = 1';
            $dataProvider = new CActiveDataProvider('BlogUsers', array(
                        'criteria' => $criteria
            ));
            return $dataProvider;
        }
}

?>
