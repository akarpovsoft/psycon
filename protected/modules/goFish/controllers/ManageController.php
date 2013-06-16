<?php

class ManageController extends PsyController
{
    public function actionModels()
    {
        $readersAll = Readers::getGoFishReadersList();
        
        if(isset($_POST['fish_type'])) {
            foreach ($_POST['fish_type'] as $key => $val)
            {
                if ($readerT = GoFishFishes::getReaderModel($key))
                {
                    $readerT->model = $val;
                    $readerT->name = $_POST['fish_name'][$key];
                    $readerT->update();
                } else
                {
                    $reader = new GoFishFishes;
                    $reader->model = $val;
                    $reader->name = $_POST['fish_name'][$key];
                    $reader->reader = $key;
                    $reader->save();
                }
            }
        }
        $this->render('models', array('readers' => $readersAll));
    }

    public function actionCreateAnswer()
    {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');
        $model = new GoFishAnswers;

        if (isset($_POST['GoFishAnswers']))
        {
            $model->attributes = $_POST['GoFishAnswers'];
            $model->author_id = GoFishFunc::userId();
            $model->sound = CUploadedFile::getInstance($model, 'sound');
            $model->approve = 1;
            if ($model->save())
            {
                if ($model->sound)
                {
                    $model->sound->saveAs(Yii::app()->basePath . '/../public_html/advanced/sound/' .
                        $model->sound);
                }
                $this->redirect(array('viewAnswer', 'id' => $model->id));
            }
        }
        $this->render('createAnswer', array('model' => $model, ));
    }

    public function actionCreate()
    {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');
        $model = new GoFishAnswers;

        if (isset($_POST['GoFishAnswers']))
        {
            $model->attributes = $_POST['GoFishAnswers'];
            $model->sound_mp3 = CUploadedFile::getInstance($model, 'sound_mp3');
            $model->sound_ogg = CUploadedFile::getInstance($model, 'sound_ogg');
            $model->author_id = $_POST['reader_id'];
            $model->approve = 1;
            if ($model->save())
            {
                if ($model->sound_mp3)
                {
                    $model->sound_mp3->saveAs(Yii::app()->basePath . '/../public_html/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$model->id.'.mp3');
                    $model->updateSoundFile($model->id, 'mp3');
                }
                if ($model->sound_ogg)
                {
                    $model->sound_ogg->saveAs(Yii::app()->basePath . '/../public_html/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$model->id.'.ogg');
                    $model->updateSoundFile($model->id, 'ogg');
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('create', array('model' => $model, ));
    }


    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    public function actionViewAnswer($id)
    {
        if (GoFishFunc::canModify())
        {
            $this->render('viewAnswer', array('model' => $this->loadModel($id)));
        }
    }

    public function actionIndex()
    {
        if (GoFishFunc::userType() == 'reader')
        {
            $criteria = new CDbCriteria;
            $criteria->condition = 'author_id=' . GoFishFunc::userId();
            $dataProvider = new CActiveDataProvider('GoFishAnswers', array('pagination' =>
                array('pageSize' => 10, ), 'criteria' => $criteria, ));
            $this->render('index', array('dataProvider' => $dataProvider, ));
        } else
            if (GoFishFunc::userType() == 'Administrator')
            {
                $cs = Yii::app()->clientScript;
                $baseUrl = Yii::app()->baseUrl;
                $cs->registerCssFile($baseUrl . '/css/form.css');
                
                $model = new GoFishAnswers('search');
                $model->unsetAttributes(); // clear any default values
                if (isset($_GET['GoFishAnswers']))
                    $model->attributes = $_GET['GoFishAnswers'];
                $this->render('admin', array('model' => $model, ));
            }
    }

    /**
     * DefaultController::actionDeleteArticle() Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        $answer = GoFishAnswers::getByPk($_GET['id']);
        if(!empty($answer->sound_mp3) && preg_match('/prediction.*/', $answer->sound_mp3))
            unlink(Yii::app()->params['project_root'].'/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$answer->id.'.mp3');
        if(!empty($answer->sound_ogg) && preg_match('/prediction.*/', $answer->sound_ogg))
            unlink(Yii::app()->params['project_root'].'/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$answer->id.'.ogg');
        
        GoFishAnswers::delByPk($answer->id);
        $this->redirect(Yii::app()->createUrl('goFish/manage/index'));
    }

    public function actionUpdate()
    {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');
        
        $model = $this->loadModel($id);
        if (isset($_POST['GoFishAnswers']))
        {
            $model->content = $_POST['GoFishAnswers']['content'];
            $model->author_id = $_POST['reader_id'];

            if (CUploadedFile::getInstance($model, 'sound_mp3'))
            {
                $model->sound_mp3 = CUploadedFile::getInstance($model, 'sound_mp3');
                $model->sound_mp3->saveAs(Yii::app()->basePath . '/../public_html/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$model->id.'.mp3');
                $model->sound_mp3 = GoFishAnswers::FILE_PREFIX.$model->id.'.mp3';
            }
            if (CUploadedFile::getInstance($model, 'sound_ogg'))
            {
                $model->sound_ogg = CUploadedFile::getInstance($model, 'sound_ogg');
                $model->sound_ogg->saveAs(Yii::app()->basePath . '/../public_html/advanced/sound/'.GoFishAnswers::FILE_PREFIX.$model->id.'.ogg');
                $model->sound_ogg = GoFishAnswers::FILE_PREFIX.$model->id.'.ogg';
            }
            if ($model->update())
            {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('update', array('model' => $model, ));
    }

    public function actionBonusClients(){
        if($_POST['bonus_cnt']){
            GoFishUsers::setBonusCount($_POST['bonus_cnt']);
        }
        $bonusCount = GoFishUsers::getBonusCount();
        $bonus = GoFishUsers::getBonusClients();
        $this->render('bonus', array('clients' => $bonus, 'bonus' => $bonusCount));
    }
    
    public function actionClients()
    {
        $users = GoFishUsers::__list();
        
        $this->render('clients', array('users' => $users));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = GoFishAnswers::model()->findByPk($_GET['id']);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'go-fish-answers-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
