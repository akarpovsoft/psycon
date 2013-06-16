<?php

class CronController extends PsyController
{
    public function actionVisitorsReport()
    {
        $dateStart = Yii::app()->request->getParam('dateStart');
        $dateEnd = Yii::app()->request->getParam('dateEnd');
        
        $report = VisitorsCounter::report($dateStart, $dateEnd);
        $total = 0;
        $grand_total = 0;

        $subject = 'Visitor Count TEST';
        $body = 'Visitors report:<br/><br/>
        Home page<br/>';

        foreach($report['home'] as $aff => $count)
        {
            $body .= $aff.' = '.$count.'<br/>';
            $total += $count;
        }
        $grand_total += $total;
        $body .= 'Total: '.$total.' hosts<br/><br/>';

        $body .= 'Signup page<br/>';
        $total = 0;
        foreach($report['signup'] as $aff => $count)
        {
            $body .= $aff.' = '.$count.'<br/>';
            $total += $count;
        }
        $grand_total += $total;
        $body .= 'Total: '.$total.' hosts<br/><br/>';
        $body .= 'Grand total: '.$grand_total.' hosts';

        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);
    }
    
    public function actionRaspTestSignup()
    {
        $config = Yii::app()->params['rasp_domains'];
        
        $url = Yii::app()->params['http_addr'].'site/raspSignup'; 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
        curl_setopt($ch, CURLOPT_POST, 1);
        
        foreach($config as $type => $data)
        {
            $fname = $this->generateNameString(5);
            $lname = $this->generateNameString(8);
            $login = $this->generateNameString(5);
            $password = $this->generateNameString(8);        
            $email = $this->generateNameString(4).'@'.$this->generateNameString(6).'.'.$this->generateNameString(2);
            $adress = $this->generateNameString(10);
            $dob = $this->generateDob();
            $gender_array = array('Male', 'Femail');
            $rand_keys = array_rand($gender_array);
            $gender = $gender_array[$rand_keys];
            $state_array = Util::getStatesList();
            $state = $state_array[rand(1, count($state_array))];
            $city = $this->generateNameString(8);
            $country = $this->generateNameString(8);
            $zip = $this->generateNumString(5);
            $amount = 1;
            $creditcard = $this->generateNumString(9);
            $exp_month = rand(1, 12);
            $exp_year = rand(2012, 2030);
            $cvv = $this->generateNumString(3);        

            $post_string = 'Signup[debug]=1&Signup[firstname]='.$fname.'&Signup[lastname]='.$lname.'&Signup[login]='.$login.'&Signup[password]='.$password
                            .'&Signup[email]='.$email.'&Signup[email_confirm]='.$email.'&Signup[dob_month]='.$dob['month'].'&Signup[dob_day]='.$dob['day']
                            .'&Signup[dob_year]='.$dob['year'].'&Signup[gender]='.$gender.'&Signup[address]='.$adress.'&Signup[city]='.$city
                            .'&Signup[state]='.$state.'&Signup[zip]='.$zip.'&Signup[country]='.$country.'&Signup[amount]='.$amount
                            .'&Signup[cardnumber]='.$creditcard.'&Signup[exp_month]='.$exp_month.'&Signup[exp_year]='.$exp_year.'&Signup[cvv]='.$cvv
                            .'&Signup[affiliate]='.$data['affiliate'].'&Signup[site_type]='.$type.'&Signup[admin_email]='.urlencode($data['admin_email'])
                            .'&Signup[site_url]='.urlencode($data['site_url']).'&Signup[site_name]='.$data['site_name'];
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); 
            curl_exec($ch);            
        }   
        curl_close($ch);
        die();
    }
    
    private function generateNameString($length)
    {
      $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ';
      $numChars = strlen($chars);
      $string = '';
      for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
      }
      return $string;
    }
    
    private function generateNumString($length)
    {
        $chars = '0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
    
    private function generateDob()
    {
       $dob = array();
       $month_list = Util::getMonthList();
       $dob['month'] = $month_list[rand(1, 12)];
       $dob['day'] = rand(1, 31);
       $dob['year'] = rand(1939, 1993);
       
       return $dob;
    }
}
?>
