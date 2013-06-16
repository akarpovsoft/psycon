<?php

class ReadersOnline
{
    public function availableReaders($online_only, $category, $main_only = false, $group_id = false, $keyword = false)
    {
        $connect = Yii::app()->db;
        $busy = array();
        $available = array();
        $break = array();
        $offline = array();

        $aux_where = "";
        if ($category == 'tarot')
            $aux_where = "AND T1_1.tarotreaders='on'";
        elseif ($category == 'clairvoyance')
            $aux_where = "AND T1_1.clairvoyants='on'";
        elseif ($category == 'atrology')
            $aux_where = "AND T1_1.astrologers ='on'";
        elseif (isset($category)&&$category != "-1")
            $aux_where = "AND T1_1.area LIKE '%" . $category . "%'";
        if(isset($keyword))
            $aux_where .= " AND T1_1.area LIKE '%" . $keyword . "%'";
        
        if (isset($group_id))
        {
           if($main_only)
               $sql = "SELECT *
                    FROM `T1_1`
                    LEFT JOIN `site_groups` ON ( `main_reader_id` = `T1_1`.`rr_record_id` AND `group_id` = ".$group_id." )
                    WHERE `type` = 'reader'
                    AND `site_groups`.`main_reader_id` IS NOT NULL ".$aux_where;
           else
               $sql = "SELECT *
                   FROM T1_1
                   LEFT JOIN week_online_time ON reader_id = T1_1.rr_record_id
                   LEFT OUTER JOIN
                   (
                    SELECT *
                    FROM site_forbidden_readers
                    WHERE group_id = ".$group_id."
                   )
                   AS forbidden_readers ON forbidden_readers.reader_id = T1_1.rr_record_id
                   LEFT JOIN `site_groups` ON ( `rr_record_id` = `main_reader_id` AND `site_groups`.`group_id` = ".$group_id." )
                   WHERE TYPE = 'reader'
                   AND
                   (
                    forbidden_readers.reader_id IS NULL
                    OR `site_groups`.`main_reader_id` IS NOT NULL
                   )
                   ".$aux_where."
                   GROUP BY `rr_record_id` 
                   ORDER BY time_online DESC";
        }
        else
        {
            $sql = "SELECT * from T1_1 left join week_online_time on reader_id = T1_1.rr_record_id where type = 'reader' " .
                $aux_where . " order by time_online desc";
        }
        
        $command = $connect->createCommand($sql);
        $rs = $command->query();
        foreach ($rs as $row)
        {
            $reader = Readers::getReader($row['rr_record_id']);
            $statusOfReader = $reader->getStatus();
            $row['status'] = $statusOfReader;
            if ('offline' == $statusOfReader)
            {
                if ($online_only)
                    continue;

                $offline[] = $row;
            }

            if ($statusOfReader == 'busy')
            {
                $busy[] = $row;
            }

            if ($statusOfReader == 'online')
            {
                $available[] = $row;
            }

            if (preg_match('/break[0-9]*/', $statusOfReader))
            {
                $break[] = $row;
            }

        }

        if($online_only == 'for_chat')
            $readers = $available;
        else
            $readers = array_merge($busy, $available, $break, $offline);

        return $readers;
    }


}
