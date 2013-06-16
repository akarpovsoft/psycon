<?php

/**
 * class ReadersVisibility
 *
 * Special class to work with readers visibility filters
 * Using tables `site_groups` and `site_forbidden_readers`
 *
 * @author Den Kazka den.smart[at]gmail.com
 * @since 2010
 * @version $Id
 */
class ReadersVisibility
{
    /**
     * Check reader's forbidden condition for current group
     *
     * @param <int> $reader_id
     * @param <int> $group_id
     * @return <int> if ( >= 1) reader is forbidden for current group
     */
    public static function forbiddenReaders($reader_id, $group_id){
        $connect = Yii::app()->db;
        $sql = "SELECT *
                FROM `site_forbidden_readers`
                WHERE `reader_id` = ".$reader_id."
                    AND `group_id` = ".$group_id;
        $command=$connect->createCommand($sql);
        return $command->execute();
    }

    /**
     * Returns main reader for current group
     *
     * @param <int> $group_id
     * @return <int> reader id
     */
    public static function getMainGroupReader($group_id){
        $connect = Yii::app()->db;
        $sql = "SELECT `main_reader_id`
                FROM `site_groups`
                WHERE `group_id` = ".$group_id;
            $command=$connect->createCommand($sql);
            $main_reader = $command->query();
            foreach($main_reader as $main)
                return $main['main_reader_id'];
    }
    /**
     * Return array with readers, who avaliable for current site group
     *
     * @param <integer> $group_id
     * @return <array>
     */
    public static function getAvaliableReaders($group_id){
        $connect = Yii::app()->db;
        $sql = "SELECT `rr_record_id` AS `reader_id`, `name`
                FROM `T1_1`
                LEFT OUTER JOIN `site_forbidden_readers`
                    ON `rr_record_id` = `reader_id`
                    AND `site_forbidden_readers`.`group_id` = ".$group_id."
                WHERE `type` = 'reader'
                AND `site_forbidden_readers`.`group_id` IS NULL";
        $command=$connect->createCommand($sql);
        $stmt = $command->query();
        $ret = array();
        foreach($stmt as $el){
            $ret[$el['reader_id']] = $el['name'];
        }
        return $ret;
    }
}

?>
