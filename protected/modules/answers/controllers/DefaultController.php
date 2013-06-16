<?php

class DefaultController extends Controller
{
    private $_model;

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');
        $successEnter = 0;

        // collect user input data
        if (isset($_POST['login']))
        {
            $userLogin = SiteQuestionUsers::model()->findByAttributes(array('username' => $_POST['login']));
            if ($userLogin)
            {
                if (isset($_POST['password']))
                {
                    if ($userLogin->password == md5(md5($_POST['password']) .
                        '3468fkxdlbfd326fbcflkbd'))
                    {
                        $successEnter = 1;
                    } else
                    {
                        $error[] = 'Username is incorrect.';
                    }
                } else
                {
                    $error[] = 'password cannot be blank.';
                }

            } else
            {
                $error[] = 'Username is incorrect.';
            }

            if ($successEnter)
            {
                $session_key = md5(time());
                $session = new SiteQuestionSessions;
                $session->user_id = $userLogin->id;
                $session->key = $session_key;
                $session->save();

                if (isset($_GET['xml']))
                {
                    //generate XML
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'ok');
                    $root->setAttribute('sessionId', $session_key);
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                }

                Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                    Adres() . '?session_key=' . $session_key);

            } else
            {
                if (isset($_GET['xml']))
                {
                    //generate XML
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'error');
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                }
            }
        }
        // display the login form
        $this->render('login', array('error' => $error));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        if (isset($_GET['session_key']))
        {
            SiteQuestionSessions::model()->deleteAllByAttributes(array('key' => $_GET['session_key']));
        }

        if (isset($_GET['xml']))
        {
            // go xml
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('result');
            $root->setAttribute('code', 'ok');
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
            die();
        }
        Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
            Adres());
    }

    public function actionRegister()
    {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');

        // start check
        if (isset($_POST['login']))
        {

            if (!answersFunc::checkLogin($_POST['login']))
            {
                $error[] = 'Sorry, but this login is taken by another user';
            }
            if ($_POST['password'])
            {
                if ($_POST['password'] <> $_POST['passwordConfirm'])
                {
                    $error[] = 'Password does not match';
                }
                if (strlen($_POST['password']) < 6)
                {
                    $error[] = 'Password must be more than 5 characters';
                }
            } else
            {
                $error[] = 'Password cannot be blank';
            }
            if ($_POST['email'])
            {
                if (!preg_match("%^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$%",
                    $_POST['email']))
                {
                    $error[] = 'Invalid email';
                }
            } else
            {
                $error[] = 'Email cannot be blank';
            }
            if (!($_POST['month'] || $_POST['day'] || $_POST['year']))
            {
                $error[] = 'Date of Birth cannot be blank';
            }

            if ($error)
            {
                if (isset($_GET['xml']))
                {
                    // go error xml
                    foreach ($error as $err)
                    {
                        $textError .= $err . '. ';
                    }
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'error');
                    $text = $dom->createElement('message');
                    $cont = $dom->createCDATASection($textError);
                    $text->appendChild($cont);
                    $root->appendChild($text);
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                }

            } else
            {
                // go ok xml
                $model = new SiteQuestionUsers;
                $model->username = $_POST['login'];
                $model->password = md5(md5($_POST['password']) . '3468fkxdlbfd326fbcflkbd');
                $model->email = $_POST['email'];
                $model->DOB = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
                if ($model->save())
                {
                    if (isset($_GET['xml']))
                    {
                        $dom = new DOMDocument('1.0', 'utf-8');
                        $root = $dom->createElement('result');
                        $root->setAttribute('code', 'ok');
                        $dom->appendChild($root);
                        $this->renderPartial('goXML', array('dom' => $dom));
                        die();
                    } else
                    {
                        $this->render('success');
                    }
                }
            }

        }
        $this->render('register', array('error' => $error));
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'createtime DESC';

        // this shit for users - criteria
        if (answersFunc::userType() == 'client')
        {
            $criteria->condition = 'author_id=' . answersFunc::userId();

            if (isset($_GET['xml']))
            {
                // lets go xml for client!
                $dom = new DOMDocument('1.0', 'utf-8');
                $root = $dom->createElement('questions');

                // lets load all questions
                $questionModel = SiteQuestion::model()->findAll($criteria);

                if ($questionModel)
                {
                    foreach ($questionModel as $que)
                    {

                        $question = $dom->createElement('question');
                        $root->appendChild($question);
                        $question->setAttribute('id', $que->id);
                        $question->setAttribute('answers', $que->answer_count);
                        $question->setAttribute('date', date('F j, Y \a\t h:i a', $que->createtime));
                        $text = $dom->createElement('text');
                        $cont = $dom->createCDATASection(strip_tags($que->content));
                        $text->appendChild($cont);
                        $question->appendChild($text);
                        if ($answers = SiteAnswers::model()->findAllByAttributes(array('question_id' =>
                            $que->id)))
                        {
                            foreach ($answers as $answer)
                            {
                                $ans = $dom->createElement('answer');
                                $question->appendChild($ans);
                                $ans->setAttribute('readerId', $answer->author_id);
                                $ans->setAttribute('readerName', answersFunc::readerLogin($answer->author_id));
                                $ans->setAttribute('date', date('F j, Y \a\t h:i a', $answer->createtime));
                            }
                        }
                    }
                }
                $dom->appendChild($root);
                $this->renderPartial('goXML', array('dom' => $dom));
                //end xml
                die();
            }

        } else
        {
            if (!(isset($_GET['view'])) || $_GET['view'] == 'active')
            {
                $criteria->condition = 'answer_count<' . Yii::app()->params['num_answers'];
            } else
                if ($_GET['view'] == 'all')
                {
                    // no criteria
                } else
                    if ($_GET['view'] == 'arhive')
                    {
                        $criteria->condition = 'answer_count>' . Yii::app()->params['num_answers'] .
                            ' OR answer_count=' . Yii::app()->params['num_answers'];
                    }
        }

        $dataProvider = new CActiveDataProvider('SiteQuestion', array('pagination' =>
            array('pageSize' => 10, ), 'criteria' => $criteria, ));
        $this->render('answers', array('dataProvider' => $dataProvider));
    }

    public function actionCreate()
    {
        if ((answersFunc::userType() == 'client') && ((count(SiteQuestion::model()->
            findAllByAttributes(array('author_id' => answersFunc::userId())))) < Yii::app()->
            params['num_question']))
        {
            $js = "tinyMCE.init({ mode : 'textareas', theme : 'simple' }); ";
            $cs = Yii::app()->clientScript;
            $baseUrl = Yii::app()->baseUrl;
            $cs->registerCssFile($baseUrl . '/css/form.css');
            $cs->registerScriptFile($baseUrl . '/js/tiny_mce/tiny_mce.js');
            $cs->registerScript(1, $js, CClientScript::POS_HEAD);

            $model = new SiteQuestion;

            if (isset($_POST['SiteQuestion']))
            {
                $model->attributes = $_POST['SiteQuestion'];
                if (isset($_POST['pub']))
                {
                    $model->pub = 1;
                } else
                {
                    $model->pub = 0;
                }
                if ($model->save())
                {
                    if (isset($_GET['xml']))
                    {
                        $dom = new DOMDocument('1.0', 'utf-8');
                        $root = $dom->createElement('result');
                        $root->setAttribute('code', 'ok');
                        $dom->appendChild($root);
                        $this->renderPartial('goXML', array('dom' => $dom));
                        die();
                    } else
                    {
                        Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                            Adres() . '/default/view?id=' . $model->id . '&' . answersFunc::addSessionToUrl
                            ());
                    }
                } else
                {
                    if (isset($_GET['xml']))
                    {
                        $textError = '';
                        if (!($_POST['SiteQuestion']['title']))
                        {
                            $textError .= 'Title cannot be blank. ';
                        }
                        if (!($_POST['SiteQuestion']['content']))
                        {
                            $textError .= 'Content cannot be blank. ';
                        }
                        $dom = new DOMDocument('1.0', 'utf-8');
                        $root = $dom->createElement('result');
                        $root->setAttribute('code', 'error');
                        $text = $dom->createElement('message');
                        $cont = $dom->createCDATASection($textError);
                        $text->appendChild($cont);
                        $root->appendChild($text);
                        $dom->appendChild($root);
                        $this->renderPartial('goXML', array('dom' => $dom));
                        die();
                    }
                }
            }

            $this->render('create', array('model' => $model));
        } else
        {
            Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                Adres() . '?' . answersFunc::addSessionToUrl());

        }
    }

    public function actionView()
    {
        $js = "tinyMCE.init({ mode : 'textareas', theme : 'simple' }); ";
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerCssFile($baseUrl . '/css/form.css');
        $cs->registerScriptFile($baseUrl . '/js/tiny_mce/tiny_mce.js');
        $cs->registerScript(1, $js, CClientScript::POS_HEAD);

        $model = $this->loadQuestion();

        if ((isset($_POST['content'])) && (answersFunc::userType() <> 'client') && (count
            (SiteAnswers::model()->findAllByAttributes(array('question_id' => $model->id))) <
            Yii::app()->params['num_answers']))
        {
            if (!count(SiteAnswers::model()->findAllByAttributes(array('question_id' => $model->
                id, 'author_id' => answersFunc::userId()))))
            {
                $youAnswer = new SiteAnswers;
                $youAnswer->content = $_POST['content'];
                $youAnswer->question_id = $model->id;
                if (isset($_POST['pub']))
                {
                    $youAnswer->pub = 1;
                } else
                {
                    $youAnswer->pub = 0;
                }

                if ($youAnswer->save())
                {
                    $model->answer_count += 1;
                    $model->update();
                }
            }
        }
        $answers = SiteAnswers::model()->findAllByAttributes(array('question_id' => $model->
            id));

        $type = answersFunc::userType();
        if ((answersFunc::userType() <> 'client') || ($model->author_id == answersFunc::
            userId()))
        {
            // reader can answer on this quest\
            if (answersFunc::userType() <> 'client')
            {
                $readerIsAnswered = SiteAnswers::model()->findByAttributes(array('question_id' =>
                    $model->id, 'author_id' => answersFunc::userId()))->id;
                if (count($answers) < Yii::app()->params['num_answers'] && (!$readerIsAnswered))
                {
                    $canAnswer = 1;
                } else
                    $canAnswer = 0;
            } else
                $canAnswer = 0;

            if ((answersFunc::userType() == 'client') && (isset($_GET['xml'])))
            {
                // generate xml
                $dom = new DOMDocument('1.0', 'utf-8');
                $root = $dom->createElement('questions');
                $question = $dom->createElement('question');
                $root->appendChild($question);
                $question->setAttribute('answers', $model->answer_count);
                $question->setAttribute('date', date('F j, Y \a\t h:i a', $model->createtime));
                $text = $dom->createElement('text');
                $cont = $dom->createCDATASection(strip_tags($model->content));
                $text->appendChild($cont);
                $question->appendChild($text);
                if ($answers)
                {
                    foreach ($answers as $answer)
                    {
                        $ans = $dom->createElement('answer');
                        $question->appendChild($ans);
                        $ans->setAttribute('readerId', $answer->author_id);
                        $ans->setAttribute('readerName', answersFunc::readerLogin($answer->author_id));
                        $ans->setAttribute('date', date('F j, Y \a\t h:i a', $answer->createtime));
                        $ansCon = $dom->createCDATASection(strip_tags($answer->content));
                        $ans->appendChild($ansCon);
                    }
                }
                $dom->appendChild($root);
                $this->renderPartial('goXML', array('dom' => $dom));
                //end xml
                die();
            }
            $this->render('view', array('model' => $model, 'answers' => $answers,
                'canAnswer' => $canAnswer));
        } else
        {
            if (isset($_GET['xml']))
            {
                //generate XML
                $dom = new DOMDocument('1.0', 'utf-8');
                $root = $dom->createElement('result');
                $root->setAttribute('code', 'error');
                $dom->appendChild($root);
                $this->renderPartial('goXML', array('dom' => $dom));
                die();
            } else
            {
                Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                    Adres() . '?' . answersFunc::addSessionToUrl());
            }
        }
    }


    public function actionQuestions()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'createtime DESC';
        // this shit for users - criteria
        $criteria->condition = 'pub=1';
        if (isset($_GET['xml']))
        {
            // lets go xml for client!
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('questions');

            // lets load all questions
            $questionModel = SiteQuestion::model()->findAll($criteria);
            if ($questionModel)
            {
                foreach ($questionModel as $que)
                {
                    $question = $dom->createElement('question');
                    $root->appendChild($question);
                    $question->setAttribute('id', $que->id);
                    $question->setAttribute('answers', $que->answer_count);
                    $question->setAttribute('date', date('F j, Y \a\t h:i a', $que->createtime));
                    $text = $dom->createElement('text');
                    $cont = $dom->createCDATASection(strip_tags($que->content));
                    $text->appendChild($cont);
                    $question->appendChild($text);
                    if ($answers = SiteAnswers::model()->findAllByAttributes(array('question_id' =>
                        $que->id)))
                    {
                        foreach ($answers as $answer)
                        {
                            $ans = $dom->createElement('answer');
                            $question->appendChild($ans);
                            $ans->setAttribute('readerId', $answer->author_id);
                            $ans->setAttribute('readerName', answersFunc::readerLogin($answer->author_id));
                            $ans->setAttribute('date', date('F j, Y \a\t h:i a', $answer->createtime));
                        }
                    }
                }
            }
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
            //end xml
            die();
        }
        $dataProvider = new CActiveDataProvider('SiteQuestion', array('pagination' =>
            array('pageSize' => 10, ), 'criteria' => $criteria, ));
        $this->render('questions', array('dataProvider' => $dataProvider));
    }


    public function actionQuestion()
    {
        $model = $this->loadQuestionPub();
        $answers = SiteAnswers::model()->findAllByAttributes(array('question_id' => $model->id, 'pub' => 1));

        if (isset($_GET['xml']))
        {
            // generate xml
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('questions');
            $question = $dom->createElement('question');
            $root->appendChild($question);
            $question->setAttribute('answers', $model->answer_count);
            $question->setAttribute('date', date('F j, Y \a\t h:i a', $model->createtime));
            $text = $dom->createElement('text');
            $cont = $dom->createCDATASection(strip_tags($model->content));
            $text->appendChild($cont);
            $question->appendChild($text);
            if ($answers)
            {
                foreach ($answers as $answer)
                {
                    $ans = $dom->createElement('answer');
                    $question->appendChild($ans);
                    $ans->setAttribute('readerId', $answer->author_id);
                    $ans->setAttribute('readerName', answersFunc::readerLogin($answer->author_id));
                    $ans->setAttribute('date', date('F j, Y \a\t h:i a', $answer->createtime));
                    $ansCon = $dom->createCDATASection(strip_tags($answer->content));
                    $ans->appendChild($ansCon);
                }
            }
            $dom->appendChild($root);
            $this->renderPartial('goXML', array('dom' => $dom));
            //end xml
            die();
        } else
        {
            $this->render('question', array('model' => $model, 'answers' => $answers));
        }
    }


    public function loadQuestion()
    {
        if ($this->_model === null)
        {
            if (isset($_GET['id']))
            {
                $this->_model = SiteQuestion::model()->findByPk($_GET['id']);
            }
            if ($this->_model === null)
            {
                if ((answersFunc::userType() == 'client') && (isset($_GET['xml'])))
                {
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'error');
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                } else
                {

                    Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                        Adres() . '?' . answersFunc::addSessionToUrl());
                }
            }
        }
        return $this->_model;
    }
    
    public function loadQuestionPub()
    {
        if ($this->_model === null)
        {
            if (isset($_GET['id']))
            {
                $this->_model = SiteQuestion::model()->findByPk($_GET['id']);
            }
            if (($this->_model === null) || (!$this->_model->pub))
            {
                if (isset($_GET['xml']))
                {
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'error');
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                } else
                {
                    Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                        Adres() . '/default/questions');
                }
            }
        }
        return $this->_model;
    }
    
}
