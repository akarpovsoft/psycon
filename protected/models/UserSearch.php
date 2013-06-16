<?php
/**
 * UserSearch class
 *
 * Class for admin searching module.
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */

class UserSearch extends users {
    /**
     * Returns single user found by PK or another condition
     *
     * @param string $query PK, login or email
     * @return users object
     */
    public static function loadOneUser($query)
    {
        if(!empty($query))
        {
            $criteria = new CDbCriteria;
            $criteria->condition = '( t.rr_record_id = :query OR login = :login OR emailaddress = :email ) and ( type = :type )';
            $criteria->params = array(':query' => $query, ':login' => $query, ':email' => $query, ':type' => 'client');
            return users::model()->with('credit_cards')->find($criteria);
        }
        return false;
    }
    /**
     * Returns the list of all users
     *
     * @return object CActiveDataProvider
     */
    public function loadUserList() {       
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type';
        $criteria->params = array(':type' => 'client');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by login
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByLogin($login) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND login LIKE :login';
        $criteria->params = array(':type' => 'client', ':login' => '%'.$login.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $login,
                                    's_type' => 'login'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by name
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByName($name) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND name LIKE :name';
        $criteria->params = array(':type' => 'client', ':name' => '%'.$name.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $name,
                                    's_type' => 'name'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by phone
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByPhone($phone) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND phone LIKE :phone';
        $criteria->params = array(':type' => 'client', ':phone' => '%'.$phone.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $phone,
                                    's_type' => 'phone'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by email
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByEmail($email) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND emailaddress LIKE :email';
        $criteria->params = array(':type' => 'client', ':email' => '%'.$email.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $email,
                                    's_type' => 'email'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by address
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByAddress($addr) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND address LIKE :addr';
        $criteria->params = array(':type' => 'client', ':addr' => '%'.$addr.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $addr,
                                    's_type' => 'address'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by signup date
     *
     * @return object CActiveDataProvider
     */
    public function searchUserBySignupDate($signup) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND t.rr_createdate LIKE :signup';
        $criteria->params = array(':type' => 'client', ':signup' => '%'.$signup.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $signup,
                                    's_type' => 'signup_date'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by credit card number
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByCreditCard($cc) {
        $criteria = new CDbCriteria;
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->condition = 'type = :type AND credit_cards.view2 LIKE :cc';
        $criteria->params = array(':type' => 'client', ':cc' => '%'.$cc.'%');
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $cc,
                                    's_type' => 'credit_card'
                                ),
                        )
        ));
        return $dataProvider;
    }
    /**
     * Search users by DOB
     *
     * @return object CActiveDataProvider
     */
    public function searchUserByDOBAll($dob) {
        $date = explode('/', $dob);        
        $criteria = new CDbCriteria;

        if(isset($date[1])){
            $mon = $date[0];
            $day = $date[1];
            $criteria->condition = 'type = :type AND t.month = :mon AND t.day = :day';
            $criteria->params = array(':type' => 'client', ':mon' => $mon, ':day' => $day);
            if(isset($date[2])){
                $year = $date[2];
                $criteria->condition = 'type = :type AND t.month = :mon AND t.day = :day AND t.year = :year';
                $criteria->params = array(':type' => 'client', ':mon' => $mon, ':day' => $day, ':year' => $year);
            }
        } else {
            $criteria->condition = 'type = :type AND CONCAT(t.month, "/", t.day, "/", t.year) LIKE :dob';
            $criteria->params = array(':type' => 'client', ':dob' => '%'.$date[0].'%');
        }
        $criteria->select = 'rr_record_id, login, emailaddress, name';
        $criteria->with = 'credit_cards';

        $dataProvider=new CActiveDataProvider('users', array(
                        'criteria'=>$criteria,
                        'pagination'=> array(
                                'pageSize'=> self::PER_PAGE,
                                'pageVar' => 'search_page',
                                'params' => array(
                                    'paging' => 1,
                                    'query' => $dob,
                                    's_type' => 'dob_all'
                                ),
                        )
        ));
        return $dataProvider;
    }
}

?>