<?php

/**
 * This is the model class for table "gofish_answers".
 *
 * The followings are the available columns in table 'gofish_answers':
 * @property integer $id
 * @property string $content
 * @property integer $author_id
 *
 * The followings are the available model relations:
 */
class GoFishAnswers extends CActiveRecord
{
    const FILE_PREFIX = 'prediction_';

    public $sound_mp3;
    public $sound_ogg;
    public $author_from_name;

    /**
     * Returns the static model of the specified AR class.
     * @return GoFishAnswers the static model class
     */
    public static function model($className = __class__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'gofish_answers';
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
        array('author_id, approve', 'numerical', 'integerOnly' => true), 
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
        array('sound_mp3', 'file' , 'types'=>'mp3'),
        array('sound_ogg', 'file' , 'types'=>'ogg'),
        array('id, content, author_id, approve, author_from_name, sound_mp3, sound_ogg', 'safe', 'on' =>
            'search'), );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array('author' => array(self::BELONGS_TO, 'Readers', 'author_id'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array('id' => 'ID Answer', 'content' => 'Content', 'author_id' =>
            'Author ID', 'approve' => 'Approve', 'author_from_name' => 'Author','sound_mp3' => 'MP3 Sound', 'sound_ogg' => 'OGG Sound');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('author_id', $this->author_id);
        $criteria->compare('approve', $this->approve);
        $criteria->compare('sound_mp3', $this->sound_mp3);
        $criteria->compare('sound_ogg', $this->sound_ogg);
        $criteria->with = array('author');
        $criteria->addSearchCondition('author.name', $this->author_from_name, true);
        return new CActiveDataProvider(get_class($this), array('criteria' => $criteria));


    }
    
    public function getRandomAnswer ($id) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'author_id=' . $id . ' AND approve=1';
            $criteria->order = 'RAND()';
            $answer = self::model()->find($criteria);
            if ($answer) {
                return $answer;
            } else {
                $criteria->condition = ' approve=1';
                $criteria->order = 'RAND()';
                return self::model()->find($criteria);
            }
    }
    
    public function getByPk ($id) {
        return  self::model()->findByPk($id);
    }
    
    public function delByPk ($id) {
        return  self::model()->deleteByPk($id);
    }

    public function updateSoundFile($fileName, $type){
        if($type == 'mp3')
            $this->sound_mp3 = GoFishAnswers::FILE_PREFIX.$fileName.'.mp3';
        if($type == 'ogg')
            $this->sound_ogg = GoFishAnswers::FILE_PREFIX.$fileName.'.ogg';
        $this->save();
    }
    
}
