<?php

class GoFishFunc
{
    /**
     * GoFishFunc::Adres()
     * 
     * @return adress of a module
     */
    static function Adres()
    {
        return 'goFish';
    }

    static function writeSelect($name)
    {
        $fishes = array('fish1' => 'Clown Fish', 'fish2' => 'Angel Fish', 'fish3' => 'Mailed Catfish', 'fish4' => 'Lionfish', 'fish5' => 'Uaru', 'fish6' => 'Blueberry tetras', 'fish7' => 'Blue Tang',
            'fish8' => 'Fairy Cichlid', 'fish9' => 'Firemouth', 'fish10' => 'Fire gobi', 'fish11' => 'Blue Jack', 'fish12' => 'Oscar Fish', 'fish13' => 'Japanese Pgymy', 'fish14' => 'Silver Molly', 'fish15' => 'Sunshine Peacock');
            
        $data = '<select name="fish_type[' . $name . ']">';
        $data .= '<option value="0">No model</option>';
        $CurrentFish = GoFishFishes::model()->findByAttributes(array('reader' => $name));
        foreach ($fishes as $fish => $name)
        {
            if ($CurrentFish->model == $fish)
            {
                $data .= '<option value="' . $fish . '" selected>' . $name . "</option>";
            } else
            {
                $data .= '<option value="' . $fish . '">' . $name . "</option>";
            }
        }
        $data .= '</select>';
        return $data;
    }

    static function getFishModel($name)
    {
        return GoFishFishes::model()->findByAttributes(array('reader' => $name))->model;
    }

    /**
     * Return reader's fish name by reader ID
     *
     * @param integer $reader_id
     * @return object
     */
    static function getFishName($reader_id){
        return GoFishFishes::model()->findByAttributes(array('reader' => $reader_id))->name;
    }

    /**
     * GoFishFunc::userType()
     * 
     * @return type user 
     */
    static function userType()
    {
        if (!Yii::app()->user->isGuest)
        {
            return Yii::app()->user->type;
        } else
        {
            return 0;
        }
    }

    /**
     * GoFishFunc::userId()
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
            return 0;
        }
    }

    /**
     * GoFishFunc::userLogin()
     * 
     * @param mixed $id
     * @return CLIENT! login
     */
    static function userLogin($id)
    {
        return GoFishUsers::model()->findByPk($id)->username;
    }

    /**
     * GoFishFunc::readerLogin()
     * 
     * @param mixed $id
     * @return reader login
     */
    static function readerLogin($id)
    {
        return users::model()->findByPk($id)->name;
    }

    static function canModify()
    {
        if ($_GET['id'])
        {
            if ((self::userId() == GoFishAnswers::model()->findByPk($_GET['id'])->author_id) &&
                self::userType() == 'reader')
            {
                return true;
            } else
                return false;
        } else
            return false;
    }

}
