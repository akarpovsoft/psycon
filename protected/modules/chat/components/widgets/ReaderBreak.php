<?php
 /**
  * Render part of reader monitor with break changing links
  */
    class ReaderBreak extends CWidget
    {
        public $status;
        public $minutes;

        public function run(){
            $this->render('readerBreak', array('status' => $this->status, 'minutes' => $this->minutes));
        }
    }

?>
