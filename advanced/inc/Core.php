<?php
/**
 * The Class Core contains the main methods for the system
 */

class Core
{
    public $useTemplate, $mainTemplate, $leftMenuTemplate, $leftSubMenuTemplate;

    public function __construct()
           {

           }

    public function __destruct()
           {
               @mysql_close($GLOBALS['conn']);
           }
    /**
     * Implement smarty
     */
    public function includeTemplateEngine()
           {
               $this->useTemplate = true;
               require(SITE_PATH.'/includes/smarty/Smarty.class.php');
               $GLOBALS['smarty'] = new Smarty;
               $GLOBALS['smarty']->template_dir = SITE_PATH.'/includes/layout/templates/';
               $GLOBALS['smarty']->compile_dir  = SITE_PATH.'/includes/layout/templates_c/';
               $GLOBALS['smarty']->config_dir   = SITE_PATH.'/includes/layout/configs/';
               $GLOBALS['smarty']->cashe_dir    = SITE_PATH.'/includes/layout/cache/';
           }

    /**
     * Make MySQL connection
     */
    public function dbConnect()
           {
               if(empty($GLOBALS['conn']))
               {
                   $GLOBALS['conn'] = mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
                   mysql_select_db(MYSQL_DB);
               }
           }

    /**
     * Build the page including all the tempates
     */
    public function buidPage()
           {
               $this->includeMenu();
               $GLOBALS['smarty']->assign('http_url', SITE_URL);
               $GLOBALS['smarty']->assign('ssl_url', SSL_URL);
               $GLOBALS['smarty']->assign('site_title', SITE_TITLE);

               if($GLOBALS['sessions']->status > 0) //logged in
               {
                   $GLOBALS['smarty']->assign('status', $GLOBALS['sessions']->status);
                   $GLOBALS['smarty']->assign('userinfo', $GLOBALS['sessions']->userinfo);
               }
               else
               {
                   $GLOBALS['smarty']->assign('status', $GLOBALS['sessions']->status);
               }
               $GLOBALS['smarty']->assign('leftMenuTemplate', $this->leftMenuTemplate);
               $GLOBALS['smarty']->assign('leftSubMenuTemplate', $this->leftSubMenuTemplate);
               $GLOBALS['smarty']->assign('mainTemplate', $this->mainTemplate);
               $GLOBALS['smarty']->display('layout.tpl');
           }

     /**
      * Include the menu into the template
      */
    public function includeMenu()
           {
               /**
                * Menu for users
                */
               if((true == $GLOBALS['sessions']->status) and 'User' == $GLOBALS['sessions']->userinfo['access_level'])
               {
                   $this->leftMenuTemplate = 'menu_user.tpl';
               }
               /**
                * Menu for the Admin
                */
               else if((true == $GLOBALS['sessions']->status) and 'Admin' == $GLOBALS['sessions']->userinfo['access_level'])
               {
                   $this->leftMenuTemplate = 'menu_admin.tpl';
               }
               else
               {
                   $this->leftMenuTemplate = 'menu.tpl';
               }
           }
    public function sendMail($recepient, $subj, $text, $type='txt')
           {
               require_once(SITE_PATH.'/includes/classes/mail/htmlMimeMail.php');
               $mail = new htmlMimeMail;
               if('html' == $type)
               {
                   $mail->setHtml($text);
               }
               else
               {
                   $mail->setText($text);
               }
               $mail->setFrom(SYSTEM_EMAIL_NAME.'<'.SYSTEM_EMAIL_ADDRESS.'>');
               $mail->setSubject($subj);
               $mail->setReturnPath(SYSTEM_EMAIL_ADDRESS);
               $mail->setCRLF("\n");
               $mail->setSMTPParams(SMTP_HOST, SMTP_PORT, SMTP_HELO, SMTP_AUTH, SMTP_USER, SMTP_PASSWORD);
               //$result = $mail->send(array($recepient), 'smtp');
               $result = $mail->send(array($recepient), MAIL_SEND_TYPE);
           }

    public function getProxyInfo()
           {
               $proxyInfo .= $_SERVER['HTTP_X_FORWARDED_FOR']."\n";
               $proxyInfo .= $_SERVER['REMOTE_ADDR']."\n";
               $proxyInfo .= $_SERVER['HTTP_VIA']."\n";
               $proxyInfo .= $_SERVER['HTTP_CLIENT_IP']."\n";;
               $proxyInfo .= $_SERVER['HTTP_FROM']."\n";
               $proxyInfo .= $_SERVER['HTTP_PRAGMA']."\n";
               $proxyInfo .= $_SERVER['HTTP_CACHE_CONTROL']."\n";
               $proxyInfo .= $_SERVER['HTTP_CACHE_INFO']."\n";
               $proxyInfo .= $_SERVER['HTTP_USER_AGENT']."\n";
               return $proxyInfo;
           }

           /**
            * Load settings from the table settings
            */

    public function loadSettings($category = 0)
           {
                $strsql = 'SELECT settings_var, settings_value from '.$GLOBALS['table']['settings'].'';
                $rs = mysql_query($strsql, $GLOBALS['conn']) or die(mysql_error());
                while ($row = mysql_fetch_assoc($rs))
                {
                    $GLOBALS['settings'][$row['settings_var']] = $row['settings_value'];
                }
                mysql_free_result($rs);
            }

     public function listLanguages()
            {
                $result = array();
                $strsql = 'SELECT lang_id, lang_name from '.$GLOBALS['table']['languages'].'';
                $rs = mysql_query($strsql, $GLOBALS['conn']) or die(mysql_error());
                while ($row = mysql_fetch_assoc($rs))
                {
                    $result[] = $row;
                }
                mysql_free_result($rs);
                return $result;
            }

     /**
      * Apply this method to split records into pages
      */
     public function splitToPages($amountOfRecords, $currentPage = 1, $perPage = 20)
            {
                $amountOfRecords = (int)$amountOfRecords;
                $currentPage     = (int)$currentPage;
                $perPage         = (int)$perPage;

                $sqlFilter = '';
                $pages     = '';
                $totalPages = ceil($amountOfRecords/$perPage);
                if($currentPage < 1)
                {
                    $currentPage = 1;
                }

                $locationAdds = '?';
                if(!empty($_SERVER['QUERY_STRING']))
                {
                    $query = preg_replace("/&page=\d+/", "", $_SERVER['QUERY_STRING']);
                    $locationAdds = '?'.$query.'&';
                }

                if($currentPage > 1)
                {
                    $prevPage = $currentPage-1;
                    $pages .= ' <a href="'.$_SERVER['SCRIPT_NAME'].$locationAdds.'page='.$prevPage.'" class=""><< Previous</a> ';
                }
                for($i=1; $i<=$totalPages; $i++)
                {
                    if($currentPage == $i)
                    {
                        $pages .=' <b>'.$i.'</b> ';
                    }
                    else
                    {
                        $pages .=' <a href="'.$_SERVER['SCRIPT_NAME'].$locationAdds.'page='.$i.'">'.$i.'</a> ';
                    }
                }
                if($totalPages > $currentPage)
                {
                    $nextPage = $currentPage+1;
                    $pages .= ' <a href="'.$_SERVER['SCRIPT_NAME'].$locationAdds.'page='.$nextPage.'">'.' Next >></a> ';
                }

                $sqlStart  = ($currentPage * $perPage)-$perPage;
                $sqlFilter = ' LIMIT '.$sqlStart.', '.$perPage;
                if(empty($pages))
                {
                    $pages = 1;
                }

                return array(
                             'pages'=>$pages,
                             'total'=>$totalPages,
                             'limit'=>$sqlFilter);
            }

     public function loadCountryList($selectedCountry)
            {                 $selectedCountry = (int)$selectedCountry;
                 $result   = array();
                 $selected = array();
                 $strsql = 'SELECT countries_id, countries_name, countries_iso_code_2 from countries';
                 $rs = mysql_query($strsql, $GLOBALS['conn']) or die(mysql_error());
                 while ($row = mysql_fetch_assoc($rs))
                 {
                     $result[] = $row;
                     if($selectedCountry == $row['countries_id'])
                     {                         $selected = $row;
                     }
                 }
                 mysql_free_result($rs);

                 return array(
                               'records'=>$result,
                               'selected'=>$selected
                                );
            }
}
?>