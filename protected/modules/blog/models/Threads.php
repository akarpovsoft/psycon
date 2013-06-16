<?php

class Threads extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'articles':
	 * @var integer $id
	 * @var string $article_id
	 * @var string $title
	 * @var string $alias
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
		return 'blog_threads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('alias', 'length', 'max'=>30),
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
                    'blog_articles' => array(self::BELONGS_TO, 'Articles', 'article_id'),
                    'blog_archive' => array(self::HAS_MANY, 'Archive', 'id'),
                    'blog_domains' => array(self::BELONGS_TO, 'BlogDomains', 'domain_id')
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
			'title' => 'Title',
			'alias' => 'Alias',
		);
	}

        public static function _list(){
            return self::model()->with('blog_articles')->findAll();
        }

        public static function getThread($id){
            return self::model()->findByPk($id);
        }

        public static function getThreadByArticle($art_id){
            return self::model()->findAll(array(
                'condition' => 'article_id = :art_id',
                'params' => array(':art_id' => $art_id)
            ));
        }

        public static function getThreadByAlias($alias, $with = false){
            if($with != false)
                return self::model()->with($with)->find(array(
                    'condition' => 'alias = :alias',
                    'params' => array(':alias' => $alias)
                ));
            else
                return self::model()->find(array(
                    'condition' => 'alias = :alias',
                    'params' => array(':alias' => $alias)
                ));
        }

        public static function getThreadByDomain($domain_id, $with = false){
            if($with != false)
                return self::model()->with($with)->findAll(array(
                    'condition' => 'domain_id = :domain_id',
                    'params' => array(':domain_id' => $domain_id)
                ));
            else
                return self::model()->find(array(
                    'condition' => 'domain_id = :domain_id',
                    'params' => array(':domain_id' => $domain_id)
                ));
        }
}
