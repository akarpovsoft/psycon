<?php

class ChatBilling
{
    private $chatContext;
    private $history;
    
	function __construct($chatContext)
	{
            $this->chatContext	= $chatContext; 
            $this->history		= ChatHistory::getSession($chatContext->session_id);
	}
	
	
	/**
	 * Function calculate balance after reading
	 *
	 * @param mixed $sessionData session data
	 * @param mixed $addLost addLost flag
	 * @param mixed $cronFinished cron flag
	 * @return mixed if the session is free for client or not
	 */
	public function endSession( $addLost, $cronFinished)
	{
		$timeToReturn = 0;
		
		if ($this->history->getFinishInitiator() == 'timeout') 		
			$timeToReturn = 2;

		// Nopayment flag
		$nopayment = ($addLost || !$this->history->isSuccessful()); 
		
		if($nopayment)		
			$timeToReturn = 'all';
			
		if (!empty($timeToReturn))
		{
			$dueToReader = $this->returnSessionTime($timeToReturn);					
		} else {
			$this->history->setRate(self::getPaymentRate($this->chatContext->reader->seniority, $this->history->getPaidTimeInMinutes()));
			$dueToReader = $this->history->dueToReader();
			$this->history->setDueToExpert($dueToReader);
			$this->history->save();
		}
		ReaderPayment::finalize($this->history->getSessionId(), $dueToReader);
		
		$extraData = array(
			'add_lost_time' => ($addLost ? 1 : 0),
			'payment' => ($nopayment ? 0 : 1),
			'free_time_remaining' => $this->chatContext->freeTimeRemaining(),
			'balance' => $this->chatContext->client->getBalance()
		);
		
		if (!$nopayment && $this->chatContext->paidChatDuration()>0)
		{
			$extraData['deposited_payments'] = $this->countTimeMarkTransDeps();
		}
		if(!$addLost) {
			$totalFreeTime		= round($this->chatContext->freeTimeOnStart() /60, 2);
			if($totalFreeTime > 0)
				$this->chatContext->fixFreeTime($this->chatContext->client_id);
			if($this->chatContext->hpTimeOnStart() > 0)
				$this->chatContext->fixHPTime();
		}
		
        $this->history->saveFinalStatistics($extraData);

		return $nopayment;
	}
	
	/**
	 * Returns all used time due last reading
	 *
	 * @param mixed $timeBack time to return back
	 * 
	 */
	private function returnSessionTime($timeBack = false)
	{
		$returnAllTime = ($timeBack == 'all') || (!isset($timeBack) );
		if($returnAllTime)
			$timeBack = $this->history->getDurationMins(); /// timeBack is in mins now
                
		$this->chatContext->client->addToBalance($timeBack);
		
		if($this->chatContext->freeTimeOnStart() > 5 && $returnAllTime) // free time returned in case of full add lost time
		{
			$this->chatContext->returnFreeTime();
		}

		if($this->chatContext->hpTimeOnStart() > 5 && $returnAllTime) // half paid time returned in case of full add lost time
		{
			$this->chatContext->returnHPTime();
		}

		if($returnAllTime) {
			$this->history->setPaidTime(0);
			$this->history->setDueToExpert(0);
		} else {
			$this->history->setPaidTime($this->history->getPaidTimeInMinutes()>$timeBack ? $this->history->getPaidTimeInMinutes()-$timeBack : 0);
			$this->history->setDueToExpert($this->history->dueToReader());
			
		}
		$this->history->setReturnedTime($timeBack*60); /// convert to seconds
		$this->history->setFreeTime($timeBack*60);
		$this->history->save();
		
		return $this->history->getDueToExpert();
	}
	
	
	/**
	 * Define readers status and gets from meta file with readers rates
	 *
	 * @param mixed $affiliateCode affiliate code
	 * @param mixed $readerSeniority Reader Seniority
	 * @param mixed $paidChatDuration Paid Chat Duration
	 * @return mixed reader rate
	 *
	 */
	public static function getPaymentRate($readerSeniority, $paidChatDuration)
	{
		$affiliateCode = "";
		require_once Yii::app()->params['project_root'].'/advanced/data/setup.meta.php';
		$affiliateRate = '';
		
		if( 'canada' == $affiliateCode)
		{
			if($paidChatDuration  <= 25){
				$rate = 0.46;
			}
			elseif($paidChatDuration  <= 40){
				$rate = 0.45;
			}
			elseif($paidChatDuration  <= 55){
				$rate = 0.45;
			}
			elseif($paidChatDuration  > 55){
				$rate = 0.49;
			}
		}
		//Old Readers
		//$Duration_chat
		elseif($readerSeniority == "Old"){
			if($affiliateRate == "Zero" or strlen($affiliateRate) < 1){
				
				
				if($paidChatDuration  < 70){
					$rate = $price_old_0_1;
				}
				else{
					$rate = $price_old_0_2;
				}
			}
			elseif($affiliateRate == "A-1"){
				if($paidChatDuration  <= 25){
					$rate = $price_old_a1_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_old_a1_2;
				}
				elseif($paidChatDuration  <= 55){
					$rate = $price_old_a1_3;
				}
				elseif($paidChatDuration  > 55){
					$rate = $price_old_a1_4;
				}
			}
			elseif($affiliateRate == "A-2"){
				if($paidChatDuration  <= 25){
					$rate = $price_old_a2_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_old_a2_2;
				}
				elseif($paidChatDuration  <= 70){
					$rate = $price_old_a2_3;
				}
				elseif($paidChatDuration  > 70){
					$rate = $price_old_a2_4;
				}
			}
		}
		//---
		//New Readers
		
		else //if($readerSeniority == "New")
		{
			if($affiliateRate == "Zero" or strlen($affiliateRate) < 1){
				if($paidChatDuration  < 70){
					$rate = $price_new_0_1;
				}
				else{
					$rate = $price_new_0_2;
				}
			}
			elseif($affiliateRate == "A-1"){
				if($paidChatDuration  <= 25){
					$rate = $price_new_a1_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_new_a1_2;
				}
				elseif($paidChatDuration  <= 55){
					$rate = $price_new_a1_3;
				}
				elseif($paidChatDuration  > 55){
					$rate = $price_new_a1_4;
				}
			}
			elseif($affiliateRate == "A-2"){
				if($paidChatDuration  <= 25){
					$rate = $price_new_a2_1;
				}
				elseif($paidChatDuration  <= 40){
					$rate = $price_new_a2_2;
				}
				elseif($paidChatDuration  <= 70){
					$rate = $price_new_a2_3;
				}
				elseif($paidChatDuration  > 70){
					$rate = $price_new_a2_4;
				}
			}
		}
		return $rate;
	}

	/**
	 * Function mark transactions to deposit, if need it
	 *
	 */
	public function countTimeMarkTransDeps()
	{
		$timeRemains = $this->markChatTransactions();
/*
		$sessionDuration= $this->history->Paid_time;
		$sessionId		= $this->history->getSessionId();
		$timeRemains	= $this->markDeposits($sessionId, $sessionDuration);
		if($timeRemains > 0)
		$timeRemains = $this->markTransactions($sessionId, $timeRemains);
*/		
		return "";
	}
	private function markChatTransactions() {

    	$command= Yii::app()->db->createCommand("UPDATE transactions SET Amount_used = TRAN_AMOUNT WHERE Amount_used = 0 AND UserID=:client_id AND transaction_type = 0");
		$command->bindParam(":client_id", $this->chatContext->client_id, PDO::PARAM_INT);    	
    	$rs = $command->query();
	}
	

/*
	private function markDeposits($sessionId, $sessionDuration)
	{
		$deposits = Deposits::searchUnmarkedByClientId($this->chatContext->client_id);

		$timeRemains = $sessionDuration;

		for($i = 0; $i < count($deposits); $i++) {
			if($timeRemains <= 0) {
				break;
			}

			$timeToDeduct = $deposits[$i]->getTime() > $timeRemains ? $timeRemains : $deposits[$i]->getTime() ;
			if($timeToDeduct>0) {
				$timeRemains -= $timeToDeduct;
				$deposits[$i]->acceptSessionTime($sessionId, $timeToDeduct);
			}
		}
		
		return $timeRemains;
	}

	private function markTransactions($sessionId, $sessionDuration) {

		$transactions = Transactions::searchUnmarkedByClientId($this->chatContext->client_id);
		
		$timeRemains = $sessionDuration;

		for($i=0; $i < count($transactions); $i++)
		{
			if($timeRemains<=0) 
				break;

			$timeToDeduct = ($transactions[$i]->getTime() > $timeRemains ? $timeRemains : $transactions[$i]->getTime());

			if($timeToDeduct>0) {
				$timeRemains -= $timeToDeduct;
				$transactions[$i]->acceptSessionTime($sessionId, $timeToDeduct);
			}
		}
	}
*/	
	
}// end class

?>