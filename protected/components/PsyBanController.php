<?php
/*
 * PsyBanController class
 *
 * Adding a redirect control to page:
 * if user status is banned or banned_by_reader - it hasn't access to any page of site
 *
 * @author  Den Kazka den.smart(at)gmail.com
 * @since   2010
 *
 */
class PsyBanController extends CFilter {


    protected function preFilter($filterChain) {
        return true;
    }

    protected function postFilter($filterChain) {

        $userInfo = ClientLimit::getUserInfo(Yii::app()->user->id);
        if(($userInfo->Client_status == 'banned') || ($userInfo->Client_status == 'banned_by_reader')){
            $filterChain->run();
            CController::redirect(Yii::app()->params['http_addr'].'users/banned');
        } 
    }
}

?>
