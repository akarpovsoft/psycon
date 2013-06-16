<?php
/**
 * Widget to render comments input form
 */
class BlogComment extends CWidget {

    public $user_id;
    public $art_id;
    public $blog_user;

    public function init(){
        $this->blog_user = BlogUsers::getBlogUser($this->user_id);
    }

    public function run(){
        $this->render('blogComment', array('user' => $this->blog_user, 'art' => $this->art_id));
    }
}

?>
