<?php

/**
 * OnlineTime
 * 
 * @package   
 * @author http://www.psychic-contact.com/
 * @copyright Shumilo Andrey
 * @version 2010
 * @access public
 */

class OnlineTime
{
    public $reader_id;
    protected $curr_time;

    function __construct()
    {
        $this->curr_time = time();
    }

    protected function chekReaderTime()
    {
        if (!count(TotalOnlineTime::model()->findByAttributes(array('reader_id' => $this->
            reader_id))))
        {
            $totalTime = new TotalOnlineTime;
            $totalTime->reader_id = $this->reader_id;
            $totalTime->time_online = 0;
            $totalTime->last_update = time();
            $totalTime->last_reset = date("Y-m-d H:i:00");
            $totalTime->save();
        }
        if (!count(TodayOnlineTime::model()->findByAttributes(array('reader_id' => $this->
            reader_id))))
        {
            $todayTime = new TodayOnlineTime;
            $todayTime->reader_id = $this->reader_id;
            $todayTime->time_online = 0;
            $todayTime->last_update = time();
            $todayTime->last_reset = date("Y-m-d H:i:00");
            $todayTime->save();
        }
    }

    protected function SaveTimeToday()
    {
        $add_time = 0;
        $todayTime = TodayOnlineTime::model()->findByAttributes(array('reader_id' => $this->
            reader_id));
        $add_time = $this->curr_time - $todayTime->last_update;
        if ($add_time < 90)
        {
            $todayTime->time_online += $add_time;
            $todayTime->last_update = $this->curr_time;
        } else
        {
            $todayTime->last_update = $this->curr_time;
        }
        $todayTime->update();
    }
    protected function SaveTimeTotal()
    {
        $add_time = 0;
        $totalTime = TotalOnlineTime::model()->findByAttributes(array('reader_id' => $this->
            reader_id));
        $add_time = $this->curr_time - $totalTime->last_update;
        if ($add_time < 90)
        {
            $totalTime->time_online += $add_time;
            $totalTime->last_update = $this->curr_time;
        } else
        {
            $totalTime->last_update = $this->curr_time;
        }
        $totalTime->update();
    }

    public function SaveTimeAll()
    {
        $this->chekReaderTime();
        $this->SaveTimeToday();
        $this->SaveTimeTotal();
    }
    
    public function getTotalOnlineTime()
    {
        $ret = array();
        $totalTime = TotalOnlineTime::model()->findByAttributes(array('reader_id' => $this->reader_id));
        $ret['minutes_online'] = $totalTime->time_online/60;
        $ret['hours_online'] = ($ret['minutes_online'] > 59) ? floor($ret['minutes_online']/60): 0;
        $ret['minutes_online'] =  $ret['minutes_online'] - ($ret['hours_online'] * 60);
        $ret['minutes_online'] = round($ret['minutes_online']);

        $ret['since_time'] = date("M j, g:i a (l)", strtotime($totalTime->last_reset));
        
        return $ret;
    }
}

?>