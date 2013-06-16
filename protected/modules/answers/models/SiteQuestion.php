<?php

/**
 * This is the model class for table "site_question".
 *
 * The followings are the available columns in table 'site_question':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $createtime
 * @property integer $author_id
 * @property integer $vilidate_answer_id
 * @property integer $answer_count
 * The followings are the available model relations:
 */
class SiteQuestion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SiteQuestion the static model class
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
		return 'site_question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required'),
			array('createtime, author_id, vilidate_answer_id, answer_count, pub', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, createtime, author_id, vilidate_answer_id, answer_count, pub', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'content' => 'Content',
			'createtime' => 'Createtime',
			'author_id' => 'Author',
			'vilidate_answer_id' => 'Vilidate Answer',
            'answer_count' => 'Answer count',
            'pub' => 'Public',
		);
	}
    
    protected function beforeSave()
		{
		    if(parent::beforeSave())
		    {
		        if($this->isNewRecord)
		        {
		            $this->createtime=time();
		            $this->author_id=answersFunc::userId();
                    $this->vilidate_answer_id = 0;
                    $this->answer_count = 0;
		        }
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('vilidate_answer_id',$this->vilidate_answer_id);
        $criteria->compare('answer_count',$this->answer_count);
        $criteria->compare('pub',$this->pub);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}