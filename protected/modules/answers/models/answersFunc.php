<?php

class answersFunc
{
    /**
     * answersFunc::Adres()
     * 
     * @return adress of a module
     */
    static function Adres()
    {
        return 'answers';
    }

    /**
     * answersFunc::userType()
     * 
     * @return type user 
     */
    static function userType()
    {
        if (!Yii::app()->user->isGuest)
        {
            if (Yii::app()->user->type == 'reader' || Yii::app()->user->type ==
                'Administrator')
            {
                return 'reader';
            }
        } else
        {
            return 'client';
        }
    }

    /**
     * answersFunc::userId()
     * 
     * @return user id 
     */
    static function userId()
    {
        if (!Yii::app()->user->isGuest)
        {
            if (Yii::app()->user->type == 'reader' || Yii::app()->user->type ==
                'Administrator')
            {
                return Yii::app()->user->id;
            }
        } else
        {
            return SiteQuestionSessions::model()->findByAttributes(array ('key' => $_GET['session_key']))->user_id;
        }
    }

    /**
     * answersFunc::userLogin()
     * 
     * @param mixed $id
     * @return CLIENT! login
     */
    static function userLogin($id)
    {
        return SiteQuestionUsers::model()->findByPk($id)->username;
    }

    /**
     * answersFunc::readerLogin()
     * 
     * @param mixed $id
     * @return reader login
     */
    static function readerLogin($id)
    {
        return users::model()->findByPk($id)->name;
    }

    /**
     * answersFunc::checkLogin()
     * 
     * @param mixed $login
     * @return check login  - must be not exist
     */
    static function checkLogin($login)
    {
        if (!SiteQuestionUsers::model()->findByAttributes(array('username' => $login)))
        {
            if (users::model()->findByAttributes(array('login' => $login, 'type' => 'reader')))
            {
                return false;
            } else
            {
                return true;
            }
        } else
            return false;
    }
    
   /**
    * answersFunc::addSessionToUrl()
    * 
    * @return add session key to user
    */
   static function addSessionToUrl()
    {
        if (!Yii::app()->user->isGuest)
        {
            if (Yii::app()->user->type == 'reader' || Yii::app()->user->type ==
                'Administrator')
            {
                return '';
            }
        } else return 'session_key='. $_GET['session_key']; 
    
    }


}
