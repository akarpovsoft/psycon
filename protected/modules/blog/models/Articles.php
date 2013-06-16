<?php

class Articles extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'articles':
	 * @var integer $id
	 * @var string $link
	 * @var string $title
	 * @var string $author
	 * @var string $date
	 * @var integer $meta_page
	 * @var integer $archive
	 */

        public $attach; // Uploaded file name
        
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
		return 'blog_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('meta_page, archive', 'numerical', 'integerOnly'=>true),
			array('link, author', 'length', 'max'=>100),
			array('title', 'length', 'max'=>150),
			array('date', 'safe'),
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
                    'blog_threads' => array(self::HAS_MANY, 'Threads', 'id'),
                    'blog_archive' => array(self::HAS_MANY, 'Archive', 'id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'link' => 'Link',
			'title' => 'Title',
			'author' => 'Author',
			'date' => 'Date',
			'meta_page' => 'Meta Page',
			'archive' => 'Archive',
		);
	}

        public static function _list(){
            return self::model()->findAll();
        }

        public static function mainPageList(){
            return self::model()->findAll(array(
                'condition' => 'meta_page = 1'
            ));
        }

        public static function getArticle($id){
            return self::model()->findByPk($id);
        }

        public function addArticle(){
            $this->date = date('Y-m-d');
            if(!is_null($this->attach)){
                $this->attach->saveAs(Yii::app()->params['project_root'].'/articles/'.$this->link.'.txt');
            }
            $this->save();
        }
}