<?php
/**
 * EmailQuestions class
 *
 * Add user's email reading request
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */
class EmailQuestions extends CActiveRecord {
    const PER_PAGE = 20;

    public $pages;
    public $price;
    
    public $full_user_name;
    public $reader_name;
    public $reader_email;
    
    /**
     * Payment billing vars
     *
     * @var string
     */
    public $amount_to_pay;
    public $id;
    public $billingfirstname;
    public $billinglastname;
    public $billingaddress;
    public $billingcity;
    public $billingstate;
    public $billingzip;
    public $billingcountry;
    public $z_contact_email_address;
    public $cardnumber;
    public $month;
    public $year;
    public $cvv;
    
    public $order_number;
    public $ps_type;

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return PsyStaticDataProvider::getTableName('email_questions');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('reader_id, client_id, d_date_of_birth_Month1, e_date_of_birth_Date2, f_date_of_birth_YR3', 'numerical', 'integerOnly'=>true),
                array('first_name, last_name, z_contact_email_address, d_date_of_birth_Month1, e_date_of_birth_Date2, f_date_of_birth_YR3, b_reading_type, reader_id, payment_method', 'required'),
                array('b_reading_type, reader_id', 'isSpecial'),
                array('payment_method', 'paymentValidate'),
                array('creditcard', 'validateCardExists'),
                array('b_reading_type', 'validatePaymentLimit'),
                array('first_name, last_name, w_contact_phone_daytime, y_contact_fax_number, j_numerology_name, k_numerology_date', 'length', 'max'=>100),
                array('r_contact_street_address_1, s_contact_street_address_2, t_contact_city, u_contact_state_or_province, v_contact_country, z_contact_email_address, za_reading_deliv, transaction', 'length', 'max'=>150),
                array('c_sex', 'length', 'max'=>6),
                array('d_date_of_birth_Month1, e_date_of_birth_Date2, f_date_of_birth_YR3, g_birth_time, affiliate', 'length', 'max'=>20),
                array('h_time_of', 'length', 'max'=>2),
                array('i_place_of_birth', 'length', 'max'=>250),
                array('l_topic', 'length', 'max'=>180),
                array('b_reading_type, zza_othersrvcs, zzb_satisf_srvcs, spec', 'length', 'max'=>10),
                array('o_payment_choice, zg_SE, zf_disc', 'length', 'max'=>120),
                array('status', 'length', 'max'=>8),
                array('chatlocation', 'length', 'max'=>13),
                array('m_special_instructions, n_additional_info, date, reading', 'safe'),
        );
    }
    /**
     * Validates reader's special offer
     */
    public function isSpecial(){
        if($this->price == '0.00')
            $this->addError('b_reading_type', Yii::t('lang', 'email_readings_error_1'));
    }
    /**
     * Validates payment fields
     */
    public function paymentValidate(){
        if($this->payment_method == 'CreditCard'){
            if(empty($this->billingfirstname))
                    $this->addError('billingfirstname', Yii::t('lang', 'email_readings_error_2'));
            if(empty($this->billinglastname))
                    $this->addError('billinglastname', Yii::t('lang', 'email_readings_error_3'));
            if(empty($this->cardnumber))
                    $this->addError('cardnumber', Yii::t('lang', 'email_readings_error_4'));
            if((empty($this->month))||(empty($this->year)))
                    $this->addError('month', Yii::t('lang', 'email_readings_error_5'));
        }
        if($this->payment_method == 'Package')
        {
            $packages = Packages::getPackageByClientId(Yii::app()->user->id);
            if(!$packages)
                $this->addError('payment_method', 'You didn\'t purchase any membership package');
            $client = Clients::getClient(Yii::app()->user->id);
            if($client->emailreadings <= 0)
                $this->addError('payment_method', 'You have no more package readings');
            if(Tariff::getReadingTypeByPrice($this->b_reading_type) > $client->emailreadings && Tariff::getReadingTypeByPrice($this->b_reading_type) != 'SPECIAL')
                $this->addError('payment_method', 'Too many questions. You can purchase only '.$client->emailreadings.' questions');    
        }
    }
    /**
     * Validates credit card registered to another user
     */
    public function validateCardExists() {
        if(!empty($this->cardnumber)) {
            $card_check = CreditCard::check($this->cardnumber, Yii::app()->user->id);
            if($card_check == false)
                $this->addError('cardnumber', 'This card is already registered to another user');
            $card_check = CreditCard::transactionCheck($this->cardnumber, Yii::app()->user->id);
            if($card_check == false)
                $this->addError('cardnumber', 'Current transaction registered to another card number');
        }
    }
    /**
     * Validates user payments limits
     */
    public function validatePaymentLimit() {
            $amount = $this->b_reading_type;
            $limit_check = ClientLimit::testLimit(Yii::app()->user->id, $amount);
            if($limit_check == false)
                $this->addError('amount', 'Transaction Declined: You\'ve reached your Psychic Contact limit!');
    }    
    
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'readers' => array(self::BELONGS_TO, 'users', 'reader_id'),
            'reader_payment' => array(self::HAS_ONE, 'ReaderPayment', 'qs_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
                'qs_id' => 'Qs',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'r_contact_street_address_1' => 'R Contact Street Address 1',
                's_contact_street_address_2' => 'S Contact Street Address 2',
                't_contact_city' => 'T Contact City',
                'u_contact_state_or_province' => 'U Contact State Or Province',
                'v_contact_country' => 'V Contact Country',
                'w_contact_phone_daytime' => 'W Contact Phone Daytime',
                'y_contact_fax_number' => 'Y Contact Fax Number',
                'z_contact_email_address' => 'Contact Email Address',
                'za_reading_deliv' => 'Za Reading Deliv',
                'c_sex' => 'Sex',
                'd_date_of_birth_Month1' => 'DOB Month',
                'e_date_of_birth_Date2' => 'DOB Date',
                'f_date_of_birth_YR3' => 'DOB Year',
                'g_birth_time' => 'G Birth Time',
                'h_time_of' => 'H Time Of',
                'i_place_of_birth' => 'I Place Of Birth',
                'j_numerology_name' => 'J Numerology Name',
                'k_numerology_date' => 'K Numerology Date',
                'l_topic' => 'L Topic',
                'b_reading_type' => 'Reading Type',
                'reader_id' => 'Reader',
                'm_special_instructions' => 'M Special Instructions',
                'n_additional_info' => 'N Additional Info',
                'o_payment_choice' => 'O Payment Choice',
                'zg_SE' => 'Zg Se',
                'zf_disc' => 'Zf Disc',
                'zza_othersrvcs' => 'Zza Othersrvcs',
                'zzb_satisf_srvcs' => 'Zzb Satisf Srvcs',
                'status' => 'Status',
                'transaction' => 'Transaction',
                'date' => 'Date',
                'affiliate' => 'Affiliate',
                'chatlocation' => 'Chatlocation',
                'spec' => 'Spec',
                'reading' => 'Reading',
                'client_id' => 'Client',
                'payment_method' => 'Payment Method',
        );
    }

    /**
     *
     * @return string returns a primary key for table
     */
    public function primaryKey() {
        return 'qs_id';
    }

    /**
     * Load a list of emailreadings all clients for reader
     *
     * @param integer $reader_id
     * @return object CActiveDataProvider
     */
    public function loadlistForReader($reader_id) {
        $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria'=>array(
                                'condition'=>'reader_id  = :reader',
                                'params'=>array(':reader'=>$reader_id),
                                'select' => 'first_name, u_contact_state_or_province, v_contact_country,
                        c_sex, d_date_of_birth_Month1, e_date_of_birth_Date2, f_date_of_birth_YR3,
                        l_topic, date, qs_id',
                        ),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));

        return $dataProvider;
    }
    /**
     * Load a list of emailreadings all clients for admin
     *
     * @return object CActiveDataProvider
     */
    public function loadListForAdmin() {
        $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria'=>array(
                                'select' => 'first_name, u_contact_state_or_province, v_contact_country,
                        c_sex, d_date_of_birth_Month1, e_date_of_birth_Date2, f_date_of_birth_YR3,
                        l_topic, date, qs_id',
                        ),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));
        return $dataProvider;
    }
    /**
     * Load a page for 1 client
     *
     * @param integer $qs_id EmailQuestion record id
     * @return object CActiveDataProvider
     */
    public function load($qs_id) {
        $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria'=>array(
                                'condition'=>'qs_id  = :gs_id',
                                'params'=>array(':qs_id'=>$qs_id)
                        ),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));

        return $dataProvider;
    }

    /**
     * Load a list of emailreadings all clients for reader
     *
     * @param string $status Payment status (pending, active)
     * @return integer Last insert into db id
     */
    public function registerQuestion($status) {

        $this->client_id = Yii::app()->user->id;
        $this->date = date('y-m-d H:i:s');
        $this->status = $status;
        
        switch($this->payment_method)
        {
            case 'PayPal':
                $this->payment_method = 2;
                break;
            case 'CreditCard':
                $this->payment_method = 1;
                break;
        }
        
        $connection=Yii::app()->db;
        $sql="INSERT INTO " .
                    PsyStaticDataProvider::getTableName("email_questions") .
                    " (first_name, last_name, r_contact_street_address_1, s_contact_street_address_2,
                    t_contact_city, u_contact_state_or_province, v_contact_country,
                    w_contact_phone_daytime, y_contact_fax_number, z_contact_email_address,
                    za_reading_deliv, c_sex, d_date_of_birth_Month1, e_date_of_birth_Date2,
                    f_date_of_birth_YR3, g_birth_time, h_time_of, i_place_of_birth,
                    j_numerology_name, k_numerology_date, l_topic, b_reading_type,
                    reader_id, m_special_instructions, n_additional_info, o_payment_choice,
                    zg_SE, zf_disc, zza_othersrvcs, zzb_satisf_srvcs, status, transaction,
                    date, affiliate, chatlocation, spec, reading, answer, client_id, payment_method)
                    VALUES
                   (:first_name, :last_name, :r_contact_street_address_1, :s_contact_street_address_2,
                    :t_contact_city, :u_contact_state_or_province, :v_contact_country,
                    :w_contact_phone_daytime, :y_contact_fax_number, :z_contact_email_address,
                    :za_reading_deliv, :c_sex, :d_date_of_birth_Month1, :e_date_of_birth_Date2,
                    :f_date_of_birth_YR3, :g_birth_time, :h_time_of, :i_place_of_birth,
                    :j_numerology_name, :k_numerology_date, :l_topic, :b_reading_type,
                    :reader_id, :m_special_instructions, :n_additional_info, :o_payment_choice,
                    :zg_SE, :zf_disc, :zza_othersrvcs, :zzb_satisf_srvcs, :status, :transaction,
                    :date, :affiliate, :chatlocation, :spec, :reading, :answer, :client_id, :payment_method)";

            $command=$connection->createCommand($sql);

            $command->bindValue(":first_name", $this->first_name, PDO::PARAM_STR);
            $command->bindValue(":last_name", $this->last_name, PDO::PARAM_STR);
            $command->bindValue(":r_contact_street_address_1", $this->r_contact_street_address_1, PDO::PARAM_STR);
            $command->bindValue(":s_contact_street_address_2", $this->s_contact_street_address_2, PDO::PARAM_STR);
            $command->bindValue(":t_contact_city", $this->t_contact_city, PDO::PARAM_STR);
            $command->bindValue(":u_contact_state_or_province", $this->u_contact_state_or_province, PDO::PARAM_STR);
            $command->bindValue(":v_contact_country", $this->v_contact_country, PDO::PARAM_STR);
            $command->bindValue(":w_contact_phone_daytime", $this->w_contact_phone_daytime, PDO::PARAM_STR);
            $command->bindValue(":y_contact_fax_number", $this->y_contact_fax_number, PDO::PARAM_STR);
            $command->bindValue(":z_contact_email_address", $this->z_contact_email_address, PDO::PARAM_STR);
            $command->bindValue(":za_reading_deliv", $this->za_reading_deliv, PDO::PARAM_STR);
            $command->bindValue(":c_sex", $this->c_sex, PDO::PARAM_STR);
            $command->bindValue(":d_date_of_birth_Month1", $this->d_date_of_birth_Month1, PDO::PARAM_STR);
            $command->bindValue(":e_date_of_birth_Date2", $this->e_date_of_birth_Date2, PDO::PARAM_STR);
            $command->bindValue(":f_date_of_birth_YR3", $this->f_date_of_birth_YR3, PDO::PARAM_STR);
            $command->bindValue(":g_birth_time", $this->g_birth_time, PDO::PARAM_STR);
            $command->bindValue(":h_time_of", $this->h_time_of, PDO::PARAM_STR);
            $command->bindValue(":i_place_of_birth", $this->i_place_of_birth, PDO::PARAM_STR);
            $command->bindValue(":j_numerology_name", $this->j_numerology_name, PDO::PARAM_STR);
            $command->bindValue(":k_numerology_date", $this->k_numerology_date, PDO::PARAM_STR);
            $command->bindValue(":l_topic", $this->l_topic, PDO::PARAM_STR);
            $command->bindValue(":b_reading_type", $this->b_reading_type, PDO::PARAM_STR);
            $command->bindValue(":reader_id", $this->reader_id, PDO::PARAM_STR);
            $command->bindValue(":m_special_instructions", $this->m_special_instructions, PDO::PARAM_STR);
            $command->bindValue(":n_additional_info", $this->n_additional_info, PDO::PARAM_STR);
            $command->bindValue(":o_payment_choice", $this->o_payment_choice, PDO::PARAM_STR);
            $command->bindValue(":zg_SE", $this->zg_SE, PDO::PARAM_STR);
            $command->bindValue(":zf_disc", $this->zf_disc, PDO::PARAM_STR);
            $command->bindValue(":zza_othersrvcs", $this->zza_othersrvcs, PDO::PARAM_STR);
            $command->bindValue(":zzb_satisf_srvcs", $this->zzb_satisf_srvcs, PDO::PARAM_STR);
            $command->bindValue(":status", $this->status, PDO::PARAM_STR);
            $command->bindValue(":transaction", $this->order_number, PDO::PARAM_STR);
            $command->bindValue(":date", $this->date, PDO::PARAM_STR);
            $command->bindValue(":affiliate", $this->affiliate, PDO::PARAM_STR);
            $command->bindValue(":chatlocation", $this->chatlocation, PDO::PARAM_STR);
            $command->bindValue(":spec", $this->spec, PDO::PARAM_STR);
            $command->bindValue(":reading", $this->reading, PDO::PARAM_STR);
            $command->bindValue(":answer", '', PDO::PARAM_STR);
            $command->bindValue(":client_id", $this->client_id, PDO::PARAM_STR);
            $command->bindValue(":payment_method", $this->payment_method, PDO::PARAM_STR);

            $rowCount=$command->execute();
            return mysql_insert_id();
    }
    
    public function sendReadingRequest()
    {        
        $PSystem = PayAccount::getDefaultPS();
        $this->ps_type = $PSystem->getType();

        $limit = ClientLimit::getUserInfo($client_id);
        
        $pay_data = array("first_name" => $this->billingfirstname, "last_name" => $this->billinglastname,
            "billing_address" => $this->billingaddress, "billing_city" => $this->billingcity,
            "billing_state" => $this->billingstate, "billing_zip" => $this->billingzip,
            "billing_country" => $this->billingcountry, "billing_email" => $this->z_contact_email_address,
            "cc_number" => $this->cardnumber, "grand_total" => $this->price, 
            //// Payment amount
            "ccexp_month" => $this->month, "ccexp_year" => $this->year, "cnp_security" =>
            $this->cvv);
        
        $resp_info = $PSystem->authorize($pay_data);                    

        // If error
        if (!$PSystem->success)
        {
            // add payment to limits
            ClientLimit::registerPayment($this->client_id, $this->price);

            // Sending failed email
            $subject = "PSYCHAT - Email Reading Order (FAILED transaction)";
            $body = "First Name: " . $this->first_name . "<br>
                     Last Name: " . $this->last_name . "<br>
                     Email" . $this->z_contact_email_address .
                "<br>
                     Topic: " . $this->l_topic . "<br>
                     Price: \$" . $this->price . "<br>
                     Reader: " . $this->reader_name . "<br>
                     Reason: " . strip_tags($resp_info) . "<br>";
            // To admin
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            
            return $resp_info['ERROR_MESSAGE'];
        } else
        {
            $this->order_number = $resp_info['ORDER_NUMBER'];
            $this->chatlocation = 'International'; // @TODO: check this value
            //Register question with status ACTIVE
            $this->registerQuestion('active');
            
            //Add new transaction
            $trans_model = new Transactions();
            $trans_model->UserID = Yii::app()->user->id;
            $trans_model->ORDER_NUMBER = $this->order_number;
            $trans_model->AUTH_CODE = $resp_info['AUTH_CODE'];
            $trans_model->TRAN_AMOUNT = $resp_info['TRAN_AMOUNT'];
            $trans_model->merchant_trace_nbr = $resp_info['MERCHANT_TRACE_NBR'];
            $trans_model->transaction_type = 1;
            $trans_model->ps_type = $this->ps_type;

            $trans_model->addTransaction();

            $this->sendSuccessEmails();
            return true;
        }
    }
    
    public function registerPackageReading()
    {
        $this->registerQuestion('active');
        $this->sendSuccessEmails();
    }
    
    private function sendSuccessEmails()
    {
        // Sending succesfull email
        $subject = "PSYCHAT - Email Reading Order";

        //To user
        $body = "Dear " . $this->first_name ." ".$this->last_name.", <br>
                Thank you for your email reading order! Your reader will begin working on your reading and have it back to you within 24-48 hours.<br><br>
                Topic: " . $this->l_topic . "<br>
                Price: " . $this->price . "<br>
                Reader: " . $this->reader_name . "<br><br>";
        PsyMailer::send($this->z_contact_email_address, $subject, $body);
        
        //To reader
        $subject = 'PSYCHAT - Email Reading Order (Successfull transaction)';
        $body = "You have received an Email Reading.<br>
                First name: " . $this->first_name . "<br>
                Topic: " . $this->l_topic . "<br>
                Price: " . $this->price . "<br>
                Reader: " . $this->reader_name . "<br>
                Please, log on to your Control Panel and click on \"Pending Email Readings\" to see the details. Click on view to submit reading to the client.<br><br>Regards,<br>
                Psychic Contact<br>" . Yii::app()->params['http_addr'];
        PsyMailer::send($this->reader_email, $subject, $body);

        //To admin
        $body = "First name: " . $this->first_name ." ".$this->last_name. "<br>
                Email: " . $this->z_contact_email_address .
            "<br>
                Topic: " . $this->l_topic . "<br>
                Price: " . $this->price . "<br>
                Reader: " . $this->reader_name . "<br>
                " . $this->order_number . "<br>
                ".$this->ps_type;
        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
    }
    
    public static function getByReaderId($reader_id){
        return self::model()->findAll(array(
            'condition' => 'reader_id = :reader_id AND status = "active"',
            'params' => array(':reader_id' => $reader_id)
        ));
    }

    public static function getByClientId($client_id){
        return self::model()->findAll(array(
            'condition' => 'client_id = :client_id',
            'params' => array(':client_id' => $client_id)
        ));
    }
    
    public static function getReading($id)
    {
        return self::model()->findByPk($id);
    }
    
    public static function getPendingEmails($reader_id){
        $criteria = new CDbCriteria;
        $criteria->select = 'qs_id, first_name, u_contact_state_or_province, v_contact_country, c_sex, d_date_of_birth_Month1,
            e_date_of_birth_Date2, f_date_of_birth_YR3, l_topic, date';
        $criteria->condition = 'reader_id = :reader_id AND (status = "active" OR status = "pending")';
        $criteria->params = array(':reader_id' => $reader_id);
        
        $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria' => $criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));

        return $dataProvider;
    }

    public static function searchPendingEmails($reader_id, $query){
        $criteria = new CDbCriteria;
        $criteria->condition = 'reader_id = :reader_id AND status = "pending"
            AND ( first_name LIKE "%'.$query.'%" OR u_contact_state_or_province LIKE "%'.$query.'%" OR v_contact_country LIKE "%'.$query.'%"
            OR c_sex LIKE "%'.$query.'%" OR d_date_of_birth_Month1 LIKE "%'.$query.'%" OR e_date_of_birth_Date2 LIKE "%'.$query.'%" OR f_date_of_birth_YR3 LIKE "%'.$query.'%"
            OR l_topic LIKE "%'.$query.'%" OR date LIKE "%'.$query.'%" )';
        $criteria->params = array(':reader_id' => $reader_id);

        $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria' => $criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $query
                                )
                        )
        ));

        return $dataProvider;
    }
    
    public static function getCompleteReadings($client_id, $condition = null)
    {
        $criteria = new CDbCriteria;
            if(is_null($condition))
                $criteria->condition = '`client_id` = :client_id AND `status` = "finished"';
            else {
                $con_str = '`date` REGEXP "^'.$condition['year'];
                if(isset($condition['month']))
                    $con_str .= '-'.$condition['month'].'.*"';
                else
                    $con_str .= '.*"';
                if($condition['day'] == '1-15')
                    $con_str .= ' AND DAYOFMONTH(`date`) >= 1 AND DAYOFMONTH(`date`) < 15';
                else if($condition['day'] == '16-31')
                    $con_str .= ' AND DAYOFMONTH(`date`) >= 15 AND DAYOFMONTH(`date`) < 32';

                $criteria->condition = '`client_id` = :client_id AND `status` = "finished" AND '.$con_str;
            }                
            $criteria->params = array(':client_id' => $client_id);
            $criteria->order = '`date` DESC';
            
            $dataProvider=new CActiveDataProvider('EmailQuestions', array(
                        'criteria'=>$criteria,
                        'sort' => array('attributes' =>  array('date', 'l_topic','reader_id')),
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'ereadings_page',
                                'params' => array(
                                    'paging' => 1,
                                    'year' => $condition['year'],
                                    'month' => $condition['month'],
                                    'day' => $condition['day']
                                )
                        )
            ));
            return $dataProvider;
    }
    
    public function askForExtraInfo($text)
    {
        $reader = Readers::getReader($this->reader_id);                
        $subject = "Extra information request from ".$reader->name;
        $body = $text."\n\n".date('M d, Y h:i', $this->date)."\n\n".Yii::app()->params['site_domain'];
        PsyMailer::send($this->z_contact_email_address, $subject, $body);
        
        $message = new Messages();
        $message->user_type = Yii::app()->user->type;
        $message->From_user = $this->reader_id;
        $message->From_name = $reader->name;
        $message->To_user = $this->client_id;
        $message->Subject = $subject;
        $message->Body = $body;        
        $message->send();
    }
    
    public function sendAnswer($text, $extra = null)
    {
        $reader = Readers::getReader($this->reader_id);
        $time = strtotime($this->date);
        $body = $text."<br><br>\n\n".date('M d, Y h:i', $time);        
        $subject = ($extra) ? "Extra information request from ".$reader->name : "Your Reading by ".$reader->name;        
    	//To the Client
        PsyMailer::send($this->z_contact_email_address, $subject, $body);       
        $message = new Messages();
        $message->user_type = Yii::app()->user->type;
        $message->From_user = $this->reader_id;
        $message->From_name = $reader->name;
        $message->To_user = $this->client_id;
        $message->Subject = $subject;
        $message->Body = $body;     
        $message->Status = 'ok';
        $message->send();
        if(!$extra)
        {
            //To the Admin
            $subject = "PSYCHAT - Email Reading by ".$reader->name;
            PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
            PsyMailer::send('akarpovsoft@yahoo.com', $subject, $body);
            //To the Reader
            PsyMailer::send($reader->emailaddress, $subject, $body);
            // Answer to client
            $this->reading = $body;
            $this->status = 'finished';            
            $this->save();
        }
    }
}