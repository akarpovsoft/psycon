<?php
/**
 * PsyStaticDataProvider class.
 * PsyStaticDataProvider is the helper class
 * to load static data (like countries)
 */
class PsyStaticDataProvider
{
	public static function getHearSources() 
	{
		return array(
        'google'=>'Google',
        'ask'=>'Ask',
 	   );
	}

	public static function getCountries() 
	{
		return array(
        'google'=>'Google',
        'ask'=>'Ask',
 	   );
	}

	/**
	 *  Return table names for models
	 */
	public static function getTableName($tableAlias) 
	{
		$data=array(
			'user' => 'T1_1',
			'credit_card' => 'T1_4',
			'chat_session' => 'T1_12',
			'chat_requests' => 'T1_7',
			'chat_history' => 'History',
			'chat_transcripts' => 'chattranscripts',
			'ban' => 'band_list',
			'email_questions' => 'email_questions',
                        'deposits' => 'deposits',
                        'transactions' => 'transactions',
                        'cc_history' => 'user_cc',
                        'client_limit' => 'band_list',
                        'personal_limit' => 'personal_limits',
                        'messages' => 'messages',
                        'black_words' => 'black_words',
                        'chat_history' => 'History',
                        'preference' => 'preference',
		);
		if(!isset($data[$tableAlias]))
			throw new CException("Table alias [$tableAlias] is incorrect!");
			
		return $data[$tableAlias];
	}

	public static function getPrimaryKey($tableAlias) 
	{
		$data=array(
			'user' => 'rr_record_id',
			'credit_card' => 'T1_4',
			'chat_session' => 'T1_12',
			'chat_requests' => 'T1_7',
			'chat_history' => 'History',
			'chat_transcripts' => 'chattranscripts',
			'ban' => 'band_list',

		);
	}	

	public static function getClientIDKey($tableAlias) 
	{
	}
}
?>