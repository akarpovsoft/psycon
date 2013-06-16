<?php
/**
 * PsyUser class file
 *
 * @author Alex Karov
 */

/**
 * CWebUser represents the persistent state for a Web application user.
 *
 * CWebUser is used as an application component whose ID is 'user'.
 * Therefore, at any place one can access the user state via
 * <code>Yii::app()->user</code>.
 *
 * CWebUser should be used together with an {@link IUserIdentity identity}
 * which implements the actual authentication algorithm.
 *
 * A typical authentication process using CWebUser is as follows:
 * <ol>
 * <li>The user provides information needed for authentication.</li>
 * <li>An {@link IUserIdentity identity instance} is created with the user-provided information.</li>
 * <li>Call {@link IUserIdentity::authenticate} to check if the identity is valid.</li>
 * <li>If valid, call {@link CWebUser::login} to login the user, and
 *     Redirect the user browser to {@link returnUrl}.</li>
 * <li>If not valid, retrieve the error code or message from the identity
 * instance and display it.</li>
 * </ol>
 *
 * The property {@link id} and {@link name} are both unique identifiers
 * for the user. The former is mainly used internally (e.g. primary key), while
 * the latter is for display purpose (e.g. username).  is a unique identifier for a user that is persistent
 * during the whole user session. It can be a username, or something else,
 * depending on the implementation of the {@link IUserIdentity identity class}.
 *
 * Both {@link id} and {@link name} are persistent during the user session.
 * Besides, an identity may have additional persistent data which can
 * be accessed by calling {@link getState}.
 * Note, when {@link allowAutoLogin cookie-based authentication} is enabled,
 * all these persistent data will be stored in cookie. Therefore, do not
 * store password or other sensitive data in the persistent storage. Instead,
 * you should store them directly in session on the server side if needed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CWebUser.php 1832 2010-02-20 03:22:45Z qiang.xue $
 * @package system.web.auth
 * @since 1.0
 */
class PsyUser extends CWebUser
{

	/**
	 * @var boolean whether to enable cookie-based login. Defaults to false.
	 */
	public $allowAutoLogin=true;
	/**
	 * @var string|array the URL for login. If using array, the first element should be
	 * the route to the login action, and the rest name-value pairs are GET parameters
	 * to construct the login URL (e.g. array('/site/login')). If this property is null,
	 * a 403 HTTP exception will be raised instead.
	 * @see CController::createUrl
	 */
	public $loginUrl=array('/site/login');
	/**
	 * @var array the property values (in name-value pairs) used to initialize the identity cookie.
	 * Any property of {@link CHttpCookie} may be initialized.
	 * This property is effective only when {@link allowAutoLogin} is true.
	 * @since 1.0.5
	 */
	public $identityCookie;
	/**
	 * @var boolean whether to automatically renew the identity cookie each time a page is requested.
	 * Defaults to false. This property is effective only when {@link allowAutoLogin} is true.
	 * When this is false, the identity cookie will expire after the specified duration since the user
	 * is initially logged in. When this is true, the identity cookie will expire after the specified duration
	 * since the user visits the site the last time.
	 * @see allowAutoLogin
	 * @since 1.1.0
	 */
	public $autoRenewCookie=false;

	private $_keyPrefix="";
	private $_access=array();

	public function getIsGuest()
	{
		return $this->getState('login_operator_id')===null;
	}

	/**
	 * @return mixed the unique identifier for the user. If null, it means the user is a guest.
	 */
	public function getId()
	{
		return $this->getState('login_operator_id');
	}

	/**
	 * @param mixed the unique identifier for the user. If null, it means the user is a guest.
	 */
	public function setId($value)
	{
		$this->setState('login_operator_id',$value);
	}

	/**
	 * Redirects the user browser to the login page.
	 * Before the redirection, the current URL will be kept in {@link returnUrl}
	 * so that the user browser may be redirected back to the current page after successful login.
	 * Make sure you set {@link loginUrl} so that the user browser
	 * can be redirected to the specified login URL after calling this method.
	 * After calling this method, the current request processing will be terminated.
	 */

	public function loginRequired()
	{
		$app=Yii::app();
		$request=$app->getRequest();
		$this->setReturnUrl($request->getUrl());
		if(($url=$this->loginUrl)!==null)
		{
			if(is_array($url))
			{
				$route=isset($url[0]) ? $url[0] : $app->defaultController;
				$url=$app->createUrl($route,array_splice($url,1));
			}
			$request->redirect($url);
		}
		else
			throw new CHttpException(403,Yii::t('yii','Login Required'));
	}

	/**
	 * Populates the current user object with the information obtained from cookie.
	 * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
	 * The user identity information is recovered from cookie.
	 * Sufficient security measures are used to prevent cookie data from being tampered.
	 * @see saveToCookie
	 */
	protected function restoreFromCookie()
	{
		//die();
		$app=Yii::app();
		$cookie=$app->getRequest()->getCookies()->itemAt($this->getStateKeyPrefix());
		print_r($cookie);
		if($cookie && !empty($cookie->value) && ($data=$app->getSecurityManager()->validateData($cookie->value))!==false)
		{
			$data=@unserialize($data);
			if(is_array($data) && isset($data[0],$data[1],$data[2],$data[3]))
			{
				list($id,$name,$duration,$states)=$data;
				$this->changeIdentity($id,$name,$states);
				if($this->autoRenewCookie)
				{
					$cookie->expire=time()+$duration;
					$app->getRequest()->getCookies()->add($cookie->name,$cookie);
				}
			}
		}
	}

	/**
	 * Saves necessary user data into a cookie.
	 * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
	 * This method saves user ID, username, other identity states and a validation key to cookie.
	 * These information are used to do authentication next time when user visits the application.
	 * @param integer number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
	 * @see restoreFromCookie
	 */
	protected function saveToCookie($duration)
	{
		$app=Yii::app();
		$cookie=$this->createIdentityCookie($this->getStateKeyPrefix());
		$cookie->expire=time()+$duration;
		$data=array(
			$this->getId(),
			$this->getName(),
			$duration,
			$this->saveIdentityStates(),
		);
		$cookie->value=$app->getSecurityManager()->hashData(serialize($data));
		$app->getRequest()->getCookies()->add($cookie->name,$cookie);
	}

	/**
	 * Creates a cookie to store identity information.
	 * @param string the cookie name
	 * @return CHttpCookie the cookie used to store identity information
	 * @since 1.0.5
	 */
	protected function createIdentityCookie($name)
	{
		$cookie=new CHttpCookie($name,'');
		if(is_array($this->identityCookie))
		{
			foreach($this->identityCookie as $name=>$value)
				$cookie->$name=$value;
		}
		return $cookie;
	}

	/**
	 * @return string a prefix for the name of the session variables storing user session data.
	 */
	public function getStateKeyPrefix()
	{
		return $this->_keyPrefix;
	}
        
        /**
         * if user has been logged as admin, return true
         * 
         */
        public function isAdmin()
        {
            if(Yii::app()->user->type == 'Administrator')
                return true;
            else
                return false;
        }


}
