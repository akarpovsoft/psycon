<?php
/*
 * PsyNetController class
 *
 * Adding a redirect control to page:
 * when this filter is enabled - user will be redirected to $site_type site page
 * 
 * @author  Den Kazka den.smart[at]gmail.com
 * @since   2010
 */
class PsyNetController extends CFilter {

    private $site_type;

    protected function preFilter($filterChain) {
        return true;
    }

    protected function postFilter($filterChain) {     
        $site = Yii::app()->session['site_type'];
        if($site) {
            $this->site_type = PsyConstants::getName($site);
            $filterChain->run();
            CController::redirect('http://'.$this->site_type.'/users/mainmenu');
        }
    }
}

?>
