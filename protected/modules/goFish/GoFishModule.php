<?php

class GoFishModule extends CWebModule
{
    const GROUP_ID = 18;

    public function init()
    {
        $this->setImport(array('goFish.models.*', 'goFish.components.*', ));
    }


    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action))
        {
            $route = $controller->id . '/' . $action->id;
            $publicPages = array('xml/register', 'xml/getFishList', 'xml/getAllFishList',
                'xml/getFish', 'xml/getPredictionText','xml/getPredictionSound','xml/getPrediction', 'manage/clients');
            $readerPages = array('manage/createAnswer', 'manage/viewAnswer', 'manage/index');
            $adminPages = array('manage/models', 'manage/create', 'manage/view',
                'manage/index', 'manage/update', 'manage/delete', 'manage/bonusClients');

            if (in_array($route, $publicPages))
            {
                return true;
            } else
                if (!Yii::app()->user->isGuest)
                {
                    if ((Yii::app()->user->type == 'reader' && in_array($route, $readerPages)) || (Yii::
                        app()->user->type == 'Administrator' && in_array($route, $adminPages)))
                    {
                        return true;
                    } else
                    {
                        return false;
                    }
                } else
                {
                    return false;
                }
        } else
            return false;
    }
}
