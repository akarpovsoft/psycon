<?php

class ReaderInvoice extends CActiveRecord
{
    const PER_PAGE = 10;

    const ACCEPT_STATUS = 1;
    const DECLINED_STATUS = 2;
    
        /**
	 * The followings are the available columns in table 'reader_invoice':
	 * @var integer $Invoice_id
	 * @var integer $Reader_id
	 * @var string $Date_submited
	 * @var string $Earning_type
	 * @var string $Total
	 * @var string $Month
	 * @var integer $Period
	 * @var string $Year
	 * @var integer $stat_id
	 * @var integer $reader_status
	 * @var string $reader_supposed_amount
	 * @var integer $admin_status
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reader_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Reader_id, Period, stat_id, reader_status, admin_status', 'numerical', 'integerOnly'=>true),
			array('Earning_type', 'length', 'max'=>255),
			array('Total', 'length', 'max'=>10),
			array('Month, Year', 'length', 'max'=>50),
			array('reader_supposed_amount', 'length', 'max'=>11),
			array('Date_submited', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Invoice_id' => 'Invoice',
			'Reader_id' => 'Reader',
			'Date_submited' => 'Date Submited',
			'Earning_type' => 'Earning Type',
			'Total' => 'Total',
			'Month' => 'Month',
			'Period' => 'Period',
			'Year' => 'Year',
			'stat_id' => 'Stat',
			'reader_status' => 'Reader Status',
			'reader_supposed_amount' => 'Reader Supposed Amount',
			'admin_status' => 'Admin Status',
		);
	}
        /**
         * Get reader's invoices group by Earning type with total amounts
         *
         * @param <integer> $reader_id
         * @return <object>
         */
        public static function getPendingInvoices($reader_id){
            $sql = 'SELECT t1.*,SUM(`Total`) as amount
                   FROM
                   (
                    SELECT inv.*
                    FROM  `reader_invoice` as inv
                    WHERE `reader_status` = 0
                    and `Reader_id`= '.$reader_id.'
                        ORDER BY `Year` DESC, `Month` DESC, `Period` DESC
                   )
                   t1
                   GROUP BY `Earning_type`';
            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            $invoices = $command->query();

            return $invoices;
        }
        /**
         * Return all invoices for current reader and earning type
         * Use limiting for pagination
         *
         * @param <integer> $invoice_id
         * @param <integer> $offset
         * @param <integer> $limit
         * @return <object>
         */
        public static function getInvoiceById($invoice_id, $offset, $limit){
            $criteria = new CDbCriteria;
            $sql = 'SELECT * FROM `reader_invoice`
                    WHERE `Reader_id` = (
                        SELECT `Reader_id` FROM `reader_invoice`
                        WHERE `Invoice_id` = '.$invoice_id.'
                    )
                    AND `Earning_type` = (
                        SELECT `Earning_type` FROM `reader_invoice`
                        WHERE `Invoice_id` = '.$invoice_id.'
                    )
                    AND `reader_status` = 0 LIMIT :offset, :limit';
            $connection=Yii::app()->db;
            $command = $connection->createCommand($sql);
            $command->bindValue(':offset', $offset);
            $command->bindValue(':limit', $limit);

            return $command->query();

        }
        /**
         * Return number of records with current reader and earning type (for pagination)
         * @param <integer> $invoice_id
         * @return <integer>
         */
        public static function getInvoiceCountById($invoice_id){
            $sql = 'SELECT * FROM `reader_invoice`
                    WHERE `Reader_id` = (
                        SELECT `Reader_id` FROM `reader_invoice`
                        WHERE `Invoice_id` = '.$invoice_id.'
                    )
                    AND `Earning_type` = (
                        SELECT `Earning_type` FROM `reader_invoice`
                        WHERE `Invoice_id` = '.$invoice_id.'
                    )
                    AND `reader_status` = 0';
            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            return $command->execute();
        }
        /**
         * Update reader invoices by different status:
         * 1 - Accepted
         * 2 - Declined
         *
         * @param <integer> $id Invoice id
         * @param <string> $status
         * @param <float> $rsa reader suupposed amount
         */
        public static function updateInvoiceById($id, $status, $rsa){
            $model = self::model()->findByPk($id);
            $model->reader_status = $status;
            $model->reader_supposed_amount = $rsa;
            $model->save();
        }
}