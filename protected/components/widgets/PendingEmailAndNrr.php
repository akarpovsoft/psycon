<?php
/**
 * Render info about pending email reading and NRR requests for current reader
 */
    class PendingEmailAndNrr extends CWidget
    {
        public $user;
        
        public function init(){
            if(!isset($this->user))
                    $this->user = Yii::app()->user->id;
        }
        
        public function run(){
            $emailPendings = count(EmailQuestions::getByReaderId($this->user));
            $nrrQuests = count(NrrRequests::getByReaderId($this->user));

            $this->render('pendingEmailAndNrr', array('emails' => $emailPendings, 'nrr' => $nrrQuests));
        }
    }

?>
