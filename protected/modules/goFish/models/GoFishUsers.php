<?php
class GoFishUsers extends CActiveRecord
{
    const PER_PAGE = 20;

    const CONFIG_FILE = '/advanced/data/gofish_conf.txt';
    /**
     * Bonus value in minutes
     */
    const BONUS_VALUE = 30;
	/**
	 * Returns the static model of the specified AR class.
	 * @return GoFishUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gofish_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fname, lname, email', 'required'),
                        array('email', 'emailCheck'),
			array('fname, lname', 'length', 'max'=>50),
            array('email', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fname, lname, email, DOB, ts', 'safe', 'on'=>'search'),
		);
	}

        public function emailCheck(){
            $existsEmail = self::model()->exists(array(
                'condition' => 'email = :email',
                'params' => array(':email' => $this->email)
            ));
            if($existsEmail == true)
                $this->addError('email', 'User with this email is already registered');
        }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'email' => 'Email',
            'DOB' => 'DOB',
            'ts' => 'ts',
		);
	}
 
        public static function __list()
        {
            return new CActiveDataProvider('GoFishUsers', array(
                'criteria' => new CDbCriteria,
                'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
            ));
        }
        
        
    protected function beforeSave()
	{
	    if(parent::beforeSave())
	    {
	        if($this->isNewRecord)
		        $this->ts=gmdate("Y-m-d H:i:s", time());
	        return true;
	    }
	    else
	        return false;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('fname',$this->username,true);
		$criteria->compare('lname',$this->password,true);
		$criteria->compare('email',$this->email,true);
        $criteria->compare('DOB',$this->DOB,true);
        $criteria->compare('ts',$this->ts,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

        public static function getMonthClientsCount(){
            $row = self::model()->find(array(
                'select' => 'COUNT(`id`) as `id`',
                'condition' => 'MONTH(`ts`) = MONTH(CURDATE()) AND YEAR(`ts`) = YEAR(CURDATE())',
            ));
            return $row->id;
        }

        public static function getBonusClients(){
            $criteria = new CDbCriteria();
            $criteria->condition = '`code` <> ""';

            return new CActiveDataProvider('GoFishUsers', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
                ));
        }

        public static function getBonusCount(){
            $file = file(Yii::app()->params['project_root'].self::CONFIG_FILE);
            return $file[0];
        }

        public static function setBonusCount($cnt){
            $file = fopen(Yii::app()->params['project_root'].self::CONFIG_FILE, 'w');
            fwrite($file, $cnt);
            fclose($file);
        }

        public function emailToAdmin(){
            $text = 'NEW REGISTERED USER IN GO FISH<br>
                DATE: '.$this->ts.'<br>
                USERNAME: '.$this->fname.' '.$this->lname.'<br>
                EMAIL: '.$this->email.'<br>
                DOB: '.$this->DOB.'<br>';
            PsyMailer::send(Yii::app()->params['adminEmail'], 'New User in Psychic "Go Fish!"', $text);
            PsyMailer::send('karpovsoft@yandex.ru', 'New User in Psychic "Go Fish!"', $text);
        }

        public function emailToClient(){
            $text = 'Greetings '.$this->fname.' !<br>
                Thank you for registering for "Psychic "Go Fish!"<br><br>
                Please go to the "Features section:" of the game to learn about how it all works.<br><br>
                If you opted to have your "Readings" sent to your email - please make sure you have entered your correct email - otherwise you won\'t receive copies of your "Fish Messages"<br><br>
                Remember! The more you use the game - the better the chance of winning a free 30mins Reading every week for 1 month!!<br><br>
                Any Qs or comments: jlynndfs@yahoo.com<br><br>
                "Good Fishing!"<br>
                Jayson';
            PsyMailer::send($this->email, 'Registering in Psychic "Go Fish!"', $text);
            PsyMailer::send('karpovsoft@yandex.ru', 'Registering in Psychic "Go Fish!"', $text);
        }

}