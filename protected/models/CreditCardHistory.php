<?php

class CreditCardHistory extends CActiveRecord {

    const PER_PAGE = 20;
    /**
     * The followings are the available columns in table 'user_cc':
     * @var integer $record_id
     * @var integer $user_id
     * @var string $user_name
     * @var string $cc_numb
     * @var string $date
     */

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return PsyStaticDataProvider::getTableName('cc_history');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('user_id', 'numerical', 'integerOnly'=>true),
                array('user_name', 'length', 'max'=>50),
                array('date', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
                'record_id' => 'Record',
                'user_id' => 'User',
                'user_name' => 'User Name',
                'date' => 'Date',
        );
    }
    
    /**
     * CreditCard number searching in user credit card history.
     * 
     * @param string $cc Credit card number
     * @return object CActiveDataProvider
     */
    public function searchCreditCard($cc) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'cc_numb LIKE :cc';
        $criteria->params = array(':cc' => '%'.$cc.'%');

        $dataProvider=new CActiveDataProvider(__CLASS__, array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $cc,
                                    's_type' => 'credit_card_history'
                                ),
                        )
        ));
        return $dataProvider;
    }

    public function addCardToHistory(){
        $this->date = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function cardExisting($hash, $user_id){
        return self::model()->exists(array(
            'condition' => 'hash1 = :hash AND user_id <> :user_id',
            'params' => array(':hash' => $hash, ':user_id' => $user_id)
        ));
    }

    public static function checkNumber($hash){
        return self::model()->exists(array(
            'condition' => 'hash1 = :hash1',
            'params' => array(':hash1' => $hash)
        ));
    }
}