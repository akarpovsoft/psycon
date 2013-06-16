<?php

class Archive extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'articles':
	 * @var integer $id
	 * @var integer $article_id
	 * @var integer $thread_id
         * @var date $date
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
		return 'blog_archive';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('article_id', 'thread_id', 'integerOnly'=>true),
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
                    'blog_threads' => array(self::BELONGS_TO, 'Threads', 'thread_id'),
                    'blog_articles' => array(self::BELONGS_TO, 'Articles', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'article_id' => 'Article id',
			'thread_id' => 'Thread id',
                        'date' => 'Date'
		);
	}

        public static function getArticle($id){
            return self::model()->find(array(
                'condition' => 'article_id = :article_id',
                'params' => array(':article_id' => $id)
            ));
        }

        public static function getThread($id, $with = false){
            if($with)
                return self::model()->with($with)->findAll(array(
                    'condition' => 'thread_id = :thread_id',
                    'params' => array(':thread_id' => $thread->id)
                ));
            else
                return self::model()->findAll(array(
                    'condition' => 'thread_id = :thread_id',
                    'params' => array(':thread_id' => $id)
                ));
        }

        public function addToArchive(){
            $this->date = date('Y-m-d');
            $this->save();
        }
}
