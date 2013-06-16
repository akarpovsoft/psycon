<?php

class SiteController extends PsyController
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array( // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
        'page' => array('class' => 'CViewAction', ), );
    }

    //    public function filters() {
    //        $a = array(
    //                    'pageLayoutControl + contact', // perform access control for CRUD operations
    //        );
    //        return parent::filters($a);
    //    }

    public function filters($params = null)
    {
        $a = array( //'application.components.PsyNetController + index',
            'pageOldLayoutControl + chataddfunds, emailreadings',
            'pageSignupLayoutControl + signup, signupCert, raspSignup',
            array('application.components.PsyNetRestrictor + index'),
            );
        return parent::filters($a);
    }

    /**
     * Checking user type and redirecting into different pages
     *
     */
    public function filterPageControlAccess($filterChain)
    {
        if (Yii::app()->user->isGuest)
        {
            $site = Yii::app()->session['site_type'];
            $this->redirect(Yii::app()->params['http_addr'] . 'site/login');
        }
        if (!isset(Yii::app()->user->type))
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        if (Yii::app()->user->type != 'client')
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        $filterChain->run();
    }
    /**
     * Checking user's parent page and sets layout with some differences (british flag)
     */
    public function filterPageOldLayoutControl($filterChain)
    {
        $site = Yii::app()->session['site_type'];
        if ($site)
        {
            if ($site == PsyConstants::SITE_TYPE_CO_UK)
                $this->layout = 'application.views.layouts.old_british';
        } else
            $this->layout = 'ssl_main';
        $filterChain->run();
    }

    public function filterPageSignupLayoutControl($filterChain)
    {
        if ($_GET['site_type'] == PsyConstants::SITE_TYPE_CO_UK)
        {
            $this->layout = 'application.views.layouts.signup_british';
        } else
        {            
            $this->layout = $this->ssl_without_left_menu;
        }
        $filterChain->run();
    }
    /**
     * Checking user's parent page and sets layout with some differences (british flag)
     */
    public function filterPageLayoutControl($filterChain)
    {
        $site = Yii::app()->session['site_type'];
        if ($site)
        {
            if ($site == PsyConstants::SITE_TYPE_CO_UK)
                $this->layout = 'application.views.layouts.old_british';
        }
        $filterChain->run();
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // default layout choosing by setLayout filter in PsyController
        $this->layout = $this->homePage;
        
        
        
        // Update information about visitors count
        $visitor = new VisitorsCounter();
        $visitor->ip = $_SERVER['REMOTE_ADDR'];
        $visitor->page_type = 'home';
        $visitor->affiliate = ($_COOKIE['Affiliate']) ? $_COOKIE['Affiliate'] : 0;
        $visitor->register();
        
        //if(preg_match('/.*iPhone.*/', $_SERVER['HTTP_USER_AGENT']))
        //        $this->redirect(Yii::app()->params['http_addr'].'mobile');
        
        $content = Pages::getPageByAlias('index')->content;
        
        $cs = Yii::app()->clientScript;
        $cs->reset();
        $this->pageTitle = 'Psychic Contact - Online Live Psychic Chat';
        $cs->registerMetaTag("Psychic Contact offers Online Psychic Chat and Email Readings - free reading for first time clients", "Description");
        $cs->registerMetaTag("Free Psychic Chat, Online Psychic Chat Readings, Free Psychic Readings, chat live online, psychic readers online, free pschic readings, psychics, psychic reading, psychic readings, psychic chat reading, Email Readings, tarot reading, free psychic online chat", "Keywords");
        
        $this->render('index', array('content' => $content));
    }

    public function actionIndexiphone()
    {
        $this->layout = $this->iPhone_layout;
        $this->render('iPhone');
    }


    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
            {
                $body = '<h3>Description</h3><p class="message">' . nl2br(CHtml::encode($error['message'])) .
                    '</p>';
                $body .= "<h3>Source File</h3><p>" . CHtml::encode($error['file']) . "({$error['line']})</p>";
                if (empty($error['source']))
                    $body .= 'No source code available.';
                else
                {
                    foreach ($error['source'] as $line => $code)
                    {
                        if ($line !== $error['line'])
                            $body .= nl2br(CHtml::encode(sprintf("%05d: %s", $line, str_replace("\t", '    ',
                                $code))));
                        else
                        {
                            $body .= "<div bgcolor='gray'>";
                            $body .= nl2br(CHtml::encode(sprintf("%05d: %s", $line, str_replace("\t", '    ',
                                $code))));
                            $body .= "</div>";
                        }
                    }
                }
                $body .= "<h3>Stack Trace</h3>";
                $body .= nl2br(CHtml::encode($error['trace']));
                $body .= "<div>" . date('Y-m-d H:i:s', $error['time']) . ' ' . $error['version'] .
                    "</div>";
                if ($error['type'] == 'CHttpException')
                {
                    if (md5(nl2br(CHtml::encode($error['trace']))) !=
                        '7c1694e3ebe2d1f6a6c4bf994e0c1650')
                    {
                        $err_file = fopen(Yii::app()->params['project_root'] .
                            '/advanced/data/error_log/log.txt', 'a');
                        fwrite($err_file, $body);
                        fclose($err_file);
                        echo $body;
                        die();
                        $this->render('error');
                    }
                    return;
                }
                //$emails = array('den.smart@gmail.com');
                //$subject = 'Psychic-contact error ' . $error['type'];
                //foreach ($emails as $email)
                    //PsyMailer::send('den.smart@gmail.com', $subject, $body);
                $this->render('error');
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (Yii::app()->user->isGuest)
        {
            if (isset($_POST['send']))
            {
                $model->verifycode = $_POST['ver'];
                $model->attributes = $_REQUEST['ContactForm'];
                if (!$model->validate())
                {
                    $err = $model->getErrors();
                    $this->render('contact_us', array('model' => $model, 'guest' => 1, 'errors' => $err));
                    return;
                } else
                {
                    $subj = "PSYCHAT - Contact us: " . $model->subject . " (" . $model->name . ")";
                    $mess_2_send = "Subject: " . $model->subject . "<br>
                                Full Name: " . $model->name . "<br>
                                Username(client wrote it himself/herself): " . $model->
                        login . "<br><br>" . $model->body . "<br>----------------<br>" . date("M j, Y h:i a",
                        time()) . "<br>
                                E-mail address: " . $model->email . "<br>
                                IP:" . $_SERVER['REMOTE_ADDR'] . "<br>
                                System: " . $_SERVER['HTTP_USER_AGENT'];
                    PsyMailer::send(Yii::app()->params['adminEmail'], $subj, $mess_2_send);
                    $this->render('contact_us', array('model' => $model, 'guest' => 1, 'success' =>
                        1));
                    return;
                }
            }
            $this->render('contact_us', array('model' => $model, 'guest' => 1));
        } else
        {
            $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
            if (isset($_POST['send']))
            {
                $model->attributes = $_POST['ContactForm'];
                if (!$model->validate())
                {
                    $err = $model->getErrors();
                    $this->render('contact_us', array('model' => $model, 'errors' => $err));
                    return;
                } else
                {
                    $full_address = $client->credit_cards->billingaddress . ", " . $client->
                        credit_cards->billingcity . ", ZIP: " . $client->credit_cards->billingzip . ", " .
                        $client->credit_cards->billingstate . ", " . $client->credit_cards->
                        billingcountry;

                    $subj = "PSYCHAT - Contact us: " . $model->subject . " (" . $client->login . ")";
                    $mess_2_send = "Subject: " . $model->subject . "<br>
                                Full Name: " . $model->name . "<br>
                                Username(client wrote it himself/herself): " . $model->
                        login . "<br><br>" . $model->body . "<br>----------------<br>" . date("M j, Y h:i a",
                        time()) . "<br>
                                Client (Id: " . $client->rr_record_id .
                        ", Username: " . $client->login . ")<br>" . $client->name . "<br>" . $client->
                        gender . "<br><br>
                                Address: " . $full_address . "<br>
                                E-mail address: " . $client->emailaddress . " (" .
                        $model->email . ")<br>
                                Phone #: " . $client->phone . "<br>
                                Sign up date: " . $client->rr_createdate . "<br>
                                IP:" . $_SERVER['REMOTE_ADDR'] . "<br>
                                System: " . $_SERVER['HTTP_USER_AGENT'];
                    PsyMailer::send(Yii::app()->params['adminEmail'], $subj, $mess_2_send);
                    $this->render('contact_us', array('model' => $model, 'success' => 1));
                    return;
                }
            } else
            {
                $model->name = $client->name;
                $model->email = $client->emailaddress;
                $model->login = $client->login;
                $this->render('contact_us', array('model' => $model));
            }
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
    	$this->layout = 'application.views.layouts.ssl.new_ssl_main';
        $model = new LoginForm;

        // collect user input data
        if (isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];
            $model->LayoutType = $_POST['layout_type'];
            if($model->LoginName == 'SteveL')
                    $model->LayoutType = 1;
            $model->lang = $_POST['lang'];
            $model->site_type = $_POST['site_type'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate())
            {
                if (isset($_POST['redirect']))
                {
                    $session_key = Yii::app()->session->getSessionId();
                    $_POST['redirect'] = $_POST['redirect'] . '&PHPSESSID=' . $session_key;
                    $this->redirect('http://' . $_POST['redirect']);
                } else
                {
                    if (Yii::app()->user->type == 'client')
                    {
                        $band_list = ClientLimit::getUserInfo(Yii::app()->user->id);
                        if (!preg_match('/' . $_SERVER['REMOTE_ADDR'] . '/', $band_list->IP))
                        {
                            $band_list->IP .= '\n' . $_SERVER['REMOTE_ADDR'];
                            $band_list->save();
                        }
                        $subject = "PSYCHAT - Readers OYR Client " . Yii::app()->user->name . " ATA";
                        $body = "<b>" . date("M j, Y h:i a", time()) . "</b><br><br>
                             <b>User</b>:<b><font color='#FF0000'>" . Yii::app()->
                            user->name . "</font></b> Has just logged into their account. READERS Please keep your room open!!<br>&nbsp;";
                        PsyMailer::sendToAllReaders($subject, $body);
                    }
                    $this->redirect(Yii::app()->params['http_addr'].'users/mainmenu');
                }
            } else
            {
                if (isset($_POST['redirect']))
                {
                    $_POST['redirect'] = $_POST['redirect'] . '&fail=1';
                    $this->redirect('http://' . $_POST['redirect']);
                } else
                {
                    $err = $model->getErrors();
                    if (isset($err['Account'][0]))
                        $acc_err = $err['Account'][0];
                    $this->redirect('forgotLogin');
                }
            }
        }
        if ((isset($_GET['layout_type'])) && (isset($_GET['lang'])))
            $this->render('login', array('model' => $model, 'lang' => $_GET['lang'],
                'layout' => $_GET['layout_type'], 'acc_err' => $acc_err));
        if (isset($_GET['layout_type']))
            $this->render('login', array('model' => $model, 'layout' => $_GET['layout_type'],
                'acc_err' => $acc_err));
        else
            if (isset($_GET['lang']))
                $this->render('login', array('model' => $model, 'lang' => $_GET['lang'],
                    'acc_err' => $acc_err));
            else
                $this->render('login', array('model' => $model, 'acc_err' => $acc_err));
    }

    public function actionForgotLogin()
    {
        if (isset($_POST['forgot_password']))
        {
            $user_data = $_POST['EmailOrLogin'];
            $acc = Clients::getByLoginOrEmail($user_data);
            if (!is_null($acc))
            {
                $subject = 'PSYCHAT - Your forgotten login data';
                $body = date("M j, Y h:i a", time()) . "<br>
Your username: " . $acc->login . "<br>
Your password: " . $acc->password . "<br>
Your email address: " . $acc->emailaddress . "<br>";
                PsyMailer::send($acc->emailaddress, $subject, $body);
                $this->render('login_fail', array('page' => 'success'));
                return;
            } else
            {
                $this->render('login_fail', array('page' => 'error'));
                return;
            }
        }
        $this->render('login_fail');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    /**
     * Render page with list of readers
     */
    public function actionOurreaders()
    {
        $this->layout = $this->without_left_menu;

        if (isset($_POST['cat']) || isset($_POST['keyword']))
            $this->render('ourreaders', array('cat' => $_POST['cat'], 'keyword' => $_POST['keyword']));
        else
            if (isset($_POST['oo']))
                $this->render('ourreaders', array('oo' => $_POST['oo']));
            else
                $this->render('ourreaders');
    }

    /**
     * Render phone readings page
     */
    public function actionWebphone()
    {
        $this->render('webphone');
    }
    /**
     * Render our prices page
     */
    public function actionOurprices()
    {
        $this->render('ourprices2');
    }
    /**
     * FAQ page action
     */
    public function actionFaq()
    {
        $this->render('faq');
    }

    public function actionEmployment()
    {
        $this->render('employment');
    }

    public function actionDisclaimer()
    {
        $this->render('disclaimer');
    }

    public function actionRefundPolicy()
    {
        $this->render('refund_policy');
    }

    public function actionPrivacyPolicy()
    {
        $this->render('privacy_policy');
    }

    public function actionChatreadings()
    {
        $this->render('chatreadings');
    }    

    public function actionSitemap()
    {
        $this->render('sitemap');
    }

    /**
     * Ref page action
     * Sets a cookie with affilate variable and redirects to different pages
     */
    public function actionReference()
    {
        $affiliate_id = $_GET['id'];
        if (isset($_GET['gotourl']))
            $go2page = $_GET['gotourl'];
        else
            $go2page = Yii::app()->params['site_domain'] . '/index.php';

        setcookie('Affiliate', $affiliate_id, 0, "/");
        $this->redirect($go2page);
    }    
     
    public function actionSignup()
    {
        $this->pageTitle = Yii::app()->name . ' - Signup';
        $affiliate = $_COOKIE['Affiliate'];
        $gift = $_GET['gift'];
        $model = new Signup();
        $model->_prereg = new Prereg();
        // Gathering visitors statistic
        // Update information about visitors count
        $visitor = new VisitorsCounter();
        $visitor->ip = $_SERVER['REMOTE_ADDR'];
        $visitor->page_type = 'signup';
        $visitor->affiliate = ($_COOKIE['Affiliate']) ? $affiliate : 0;
        $visitor->register();
        
        if (isset($_POST['Signup']))
        {
            foreach ($_POST['Signup'] as $key => $value)
            {
                $model->$key = $value;
            }
            if($model->payment_method == 'PayPal')
            {
                $model->preregInit();            
            }
            
            if (!$model->validate())
            {
                $err = $model->getErrors();
                if (isset($_POST['redirect']))
                {
                    $error_code = serialize($err);
                    $this->redirect('http://' . $_POST['redirect'] . '&error=' . urlencode($error_code));
                }
                if (isset($_GET['register']))
                    $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                        $gift, 'errors' => $err, 'no_freebie' => 1));
                else
                    $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                        $gift, 'errors' => $err));
                return;
            } else
            {
                $reg = $model->registerUser();
                if (!is_bool($reg))
                {
                    if (isset($_POST['redirect']))
                    {
                        $error_code = serialize($reg);
                        $this->redirect('http://' . $_POST['redirect'] . '&error=' . urlencode($error_code));
                    }
                    if ($reg == 'banned')
                    {
                        $this->redirect('moreAccount');
                        return;
                    }
                    if (isset($_GET['register']))
                        $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                            $gift, 'errors' => $reg, 'no_freebie' => 1));
                    else
                        $this->render('signup', array('mod' => $model, 'errors' => $reg, 'affiliate' =>
                            $affiliate, 'gift' => $gift));
                    return;
                } else
                {
                    if (isset($_POST['redirect']))
                    {
                        $this->redirect('http://' . $_POST['redirect'] . '&error=0');
                    }
                    if ($_GET['site_type'] == PsyConstants::SITE_TYPE_CO_UK)
                        $this->render('signup_success');
                    else
                        $this->render('signup', array('mod' => $model, 'success' => 1));
                    return;
                }
            }
        }
        if (isset($_GET['register']))
            $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                $gift, 'no_freebie' => 1));
        else
            $this->render('signup', array('mod' => $model, 'affiliate' => $affiliate, 'gift' =>
                $gift));
    }
    
    public function actionSuccessPaypalSignup()
    {
        $this->render('successPaypalSignup');
    }
    
    public function actionSignupFirstStep()
    {
        $this->pageTitle = Yii::app()->name . ' - Signup';
        $affiliate = $_COOKIE['Affiliate'];
        $debug = ($_GET['fgtl']) ? 1 : 0;
        $model = new Signup();
        
        $this->render('signupFirstStep', array(
            'mod' => $model, 
            'affiliate' => $affiliate, 
            'gift' => $gift,
            'debug' => $debug
        ));
    }
    
    public function actionSignupSecondStep()
    {
        $model = new Signup();
        
        foreach($_POST['Signup'] as $key => $val)        
            $model->$key = $val;        
        $model->email_confirm = $model->email;
        
        $model->emailCheck();
        $model->loginCheck();
        
        if($model->getErrors())
        {
            $gift = $model->gift;
            $affiliate = $model->affiliate;
            $this->render('signupFirstStep', array(
                        'mod' => $model, 
                        'affiliate' => $affiliate, 
                        'gift' => $gift,
                        'errors' => $model->getErrors()
                    ));
            return;
        }
        
        if(isset($_POST['secondStep']))
        {
            foreach($_POST['Signup'] as $key => $val)
                $model->$key = $val;
            
            if(!$model->validate())
            {
                $err = $model->getErrors();
                
                $this->render('signupSecondStep', array(
                    'mod' => $model, 
                    'errors' => $err
                ));
                return;
            }
            else
            {
                $reg = $model->registerUser();
                if (!is_bool($reg))
                {                    
                    if ($reg == 'banned')
                    {
                        $this->redirect('moreAccount');
                        return;
                    }                    
                    $this->render('signupSecondStep', array(
                        'mod' => $model, 
                        'errors' => $reg, 
                    ));
                    return;
                } 
                else
                {
                    $this->render('signupSecondStep', array(
                        'mod' => $model, 
                        'success' => 1
                    ));
                    return;
                }
            }
        }
        
        $this->render('signupSecondStep', array(
            'mod' => $model, 
        ));
    }
    
    public function actionMoreAccount()
    {
        if (isset($_POST['forgot_password']))
        {
            $user_data = $_POST['EmailOrLogin'];
            $acc = Clients::getByLoginOrEmail($user_data);
            if (!is_null($acc))
            {
                $subject = 'PSYCHAT - Your forgotten user data';
                $body = date("M j, Y h:i a", time()) . "<br>
Your username: " . $acc->login . "<br>
Your password: " . $acc->password . "<br>
Your email address: " . $acc->emailaddress . "<br>";
                PsyMailer::send($acc->emailaddress, $subject, $body);
                $this->render('second_account', array('page' => 'success'));
                return;
            } else
            {
                $this->render('second_account', array('page' => 'error'));
                return;
            }
        }
        $this->render('second_account', array('page' => 'enter'));
    }

    public function actionSignupCert()
    {
        $this->pageTitle = Yii::app()->name . ' - Signup certificate';
        $readers = Readers::getReadersList();
        $model = new SignupCert();
        $readers_list[''] = Yii::t('lang', 'Please_Select');
        foreach ($readers as $reader)
            $readers_list[$reader->rr_record_id] = $reader->login;
        if (isset($_POST['register']))
        {
            foreach ($_POST['SignupCert'] as $key => $value)
            {
                $model->$key = $value;
            }
            $model->signup_type = $_POST['signup_type'];
            if (!$model->validate())
            {
                $this->render('signup_cert', array('readers_list' => $readers_list, 'mod' => $model,
                    'errors' => $model->getErrors()));
                return;
            } else
            {
                $gift_client_id = $model->createGiftClient();
                $postdata = "";
                $postdata .= "cmd=_xclick&";
                $postdata .= "business=" . urlencode(Yii::app()->params['paypal_buisness_email']) .
                    "&";
                $postdata .= "notify_url=" . urlencode(Yii::app()->params['http_addr'] .
                    "service/signupCertIpn") . "&";
                $postdata .= "item_name=" . urlencode("Psychic-Contact Time") . "&";
                $postdata .= "item_number=1&";
                $postdata .= "amount=19.95&";
                $postdata .= "custom=" . $gift_client_id . "&";
                $postdata .= "no_note=1&";
                $postdata .= "no_shipping=1&";
                $postdata .= "shipping=0&";
                $postdata .= "shipping2=0&";
                $postdata .= "first_name=" . $model->firstname . "&";
                $postdata .= "last_name=" . $model->lastname . "&";
                $postdata .= "return=" . urlencode(Yii::app()->params['http_addr'] .
                    "site/index") . "&";
                $postdata .= "currency_code=USD&";
                $postdata .= "bn=" . urlencode('Psychic-contact');
                // Link with get parameters for paypal payment system
                $paypal_link = Yii::app()->params['paypal_url'] . '/cgi-bin/webscr?' . $postdata;
                $this->redirect($paypal_link);
            }
        }
        $this->render('signup_cert', array('readers_list' => $readers_list, 'mod' => $model));
    }

    /*
     * Action to get list of readers, avliable for current site.
     * This action like API and maybe carried to anoter API controller ?
     */
    public function actionGetSiteReaders($group_id){
        $main_reader = ReadersVisibility::getMainGroupReader($group_id);
        if(!empty($main_reader)){
            $reader = Readers::getReader($main_reader);
            echo json_encode(array($main_reader => $reader->name));
            die();
        } else {
            $readers = ReadersVisibility::getAvaliableReaders($group_id);
            echo json_encode($readers);
            die();
       }
    }
    
    public function actionReloadReadersList()
    {
        $this->layout = 'application.views.layouts.zero';
        $cat = ($_GET['cat']) ? $_GET['cat'] : null;
        $keyword = ($_GET['keyword']) ? $_GET['keyword'] : null;
        
        $this->render('reloadReadersList', array('type' => $_GET['type'], 'online' => $_GET['online'], 'cat' => $cat, 'keyword' => $keyword, 'cnt' => $_GET['count'], 'title' => $_GET['title']));
    }
    
    public function actionAbout()
    {
        $this->render('about');
    }
    
    public function actionMoreReaders()
    {
        $this->layout = $this->without_left_menu;
        
        $this->render('ourreaders_full');
    }
    
    public function actionMemPackages()
    {
        $this->layout = $this->without_left_menu;
        
        $this->render('mem_packages');
    }
    
    public function actionPhonereaders($old = false)
    {
        if($old)
        {
            $this->layout = 'application.views.layouts.mainPhoneReaders';
            Yii::app()->theme = '';
        }
        else        
            $this->layout = $this->without_left_menu;            
        
        $this->pageTitle = "live psychic phone readings";
        $phoneReaders = Readers::phoneReadersList();
        
        $this->render('phone_readers', array('readers' => $phoneReaders));
    }
    
    public function actionTeambuy()
    {
        if($_POST)
        {
            $teambuy = new Teambuy();
            foreach($_POST as $key => $val)
                $teambuy->$key = $val;
            
            if($teambuy->validate())
            {
                $subject = "Data From Redemption Form";
                $body = "Form Data:<br>\n
                    Full name: ".$teambuy->name."<br>\n
                    Date of Birth: ".$teambuy->dob_month."/".$teambuy->dob_day."/".$teambuy->dob_year."<br>\n
                    Email address: ".$teambuy->email."<br>\n
                    Username: ".$teambuy->login."<br>\n
                    Password: ".$teambuy->pwd."<br>\n
                    Teambuy code: ".$teambuy->code."<br>\n
                    ";
                PsyMailer::send('mysticladydi@yahoo.com', $subject, $body);
                $this->render('teambuy', array('success' => 1));
                return;
            }
            else
            {
                $this->render('teambuy', array('errors' => $teambuy->getErrors()));
                return;
            }
        }        
        $this->render('teambuy');
    }
}
