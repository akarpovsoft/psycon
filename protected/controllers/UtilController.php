<?php
class UtilController extends PsyController
{
    public function actionOnline()
    {
    	$startDate = Yii::app()->request->getParam("startDate");
    	$readerId = Yii::app()->request->getParam("readerId");
    	
    	$dates = array();
    	for($i=0; $i<30; $i++) {
    		$date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
    		$dates[$date] = $date;
    	}
    		
		$list = array();
    	if($startDate && $readerId) {
    		$list = ReaderOnlineStatistics::getListByReaderAndDate($readerId, $startDate);
    	}
    	$readers = array();
    	$tmp = Readers::getReadersList();
    	foreach($tmp as $reader) {
    		$readers[$reader->getId()] = $reader->getScreenName();
    	}
        $this->render('online', array(
            'startDate' => $startDate,
            'dates' => $dates,
            'readerId' => $readerId,
            'readers' => $readers,
            'list' => $list
        ));
    	
    }
    public function actionClearonline()
    {
    	ReaderOnlineStatistics::deleteOld();
    	$this->redirect("/advanced/util/online");
    }

}