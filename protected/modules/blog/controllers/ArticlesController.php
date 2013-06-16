<?php

class ArticlesController extends Controller
{

        /* Main action with articles list */
        public function actionIndex(){
            $list = Articles::mainPageList();
            
            $this->render('index', array('artList' => $list));
        }

        /* Shows current article with comments */
        public function actionShow(){
            $article = Articles::getArticle($_GET['id']);
            $artText = file_get_contents(Yii::app()->params['project_root'].'/articles/'.$article->link.'.txt');
            $artComments = BlogComments::getArtComments($article->id);
            // Check and get blog user
            $blog_user = BlogSessions::getSession($_COOKIE['PHPSESSID']);

            if(empty($blog_user))
                $log = 'guest';
            else{
                $log = unserialize($blog_user->session_value);
                $user = BlogUsers::getBlogUser($log['id']);
                if($user->approved == 0)
                      $log = 'guest';
            }
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.js');
            $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ajax_queue.js');
            $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.json-2.2.js');
            
            $this->render('show', array('id' => $article->id, 'text' => $artText, 'comments' => $artComments, 'user' => $log));
        }

        /* AJAX action for add new comment to approve */
        public function actionAddComment(){
            $comment = new BlogComments();
            $comment->user_id = $_POST['user_id'];
            $comment->article_id = $_POST['art_id'];
            $comment->text = $_POST['comment'];
            $comment->addComment();
            
            echo 'Comment send for admin\'s approve';
        }

        public function actionThread(){
            $thread = Threads::getThreadByAlias($_GET['alias'], 'blog_articles');

            $artText = file_get_contents(Yii::app()->params['project_root'].'/articles/'.$thread->blog_articles->link.'.txt');
            $artComments = BlogComments::getArtComments($thread->article_id);
            $archive = Archive::getThread($thread->id, 'blog_articles');

            $blog_user = BlogSessions::getSession($_COOKIE['PHPSESSID']);
            if(empty($blog_user))
                $log = 'guest';
            else{
                $log = unserialize($blog_user->session_value);
                $user = BlogUsers::getBlogUser($log['id']);
                if($user->approved == 0)
                      $log = 'guest';
            }
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.js');
            $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ajax_queue.js');
            $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.json-2.2.js');
 
            $this->render('thread', array('archive' => $archive, 'artText' => $artText, 'comments' => $artComments, 'user' => $log));
        }
}

?>
