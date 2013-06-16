<?php

class FirstInQueue
{
    public static function getActiveClient()
    {
        $req = ChatRequests::getReadersRequestNew(Yii::app()->user->id);               
        
        foreach($req as $first_client)
            return $first_client;
    }
}

?>
