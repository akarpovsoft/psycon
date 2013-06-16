<?php

class Pages extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'pages':
	 * @var integer $id
	 * @var string $name
	 * @var string $content
	 * @var string $fullname
	 * @var string $alias
	 * @var string $title
	 * @var string $keywords
	 * @var string $description
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, fullname, alias', 'required'),
			array('fullname', 'length', 'max'=>255),
			array('alias, title, description', 'length', 'max'=>100),
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
			'id' => 'Id',
			'name' => 'Name',
			'content' => 'Content',
			'fullname' => 'Fullname',
			'alias' => 'Alias',
			'title' => 'Title',
			'keywords' => 'Keywords',
			'description' => 'Description',
		);
	}
        
        public static function _list()
        {
            return self::model()->findAll();
        }
        
        public static function getPage($id)
        {
            return self::model()->findByPk($id);
        }
        
        public static function getPageByAlias($alias)
        {
            $page = Yii::app()->cache->get('Page_'.$alias);            
            if(!$page)
            {
                $page = self::model()->find(array(
                    'condition' => 'alias = :alias',
                    'params' => array(':alias' => $alias)
                ));
                Yii::app()->cache->set('Page_'.$alias, $page);
            }
            
            return $page;
        }
        
        
}