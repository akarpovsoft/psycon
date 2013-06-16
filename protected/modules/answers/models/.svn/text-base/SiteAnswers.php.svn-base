<?php

/**
 * This is the model class for table "site_answers".
 *
 * The followings are the available columns in table 'site_answers':
 * @property integer $id
 * @property string $content
 * @property integer $createtime
 * @property integer $author_id
 * @property integer $question_id
 *
 * The followings are the available model relations:
 */
class SiteAnswers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SiteAnswers the static model class
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
		return 'site_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('createtime, author_id, question_id, pub', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content, createtime, author_id, question_id, pub', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'createtime' => 'Createtime',
			'author_id' => 'Author',
			'question_id' => 'Question',
            'pub' => 'Public',
		);
	}
    
    protected function beforeSave()
	{
	    if(parent::beforeSave())
	    {
	        if($this->isNewRecord)
	            $this->createtime=time();
		        $this->author_id=answersFunc::userId();
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('question_id',$this->question_id);
        $criteria->compare('pub',$this->pub);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}