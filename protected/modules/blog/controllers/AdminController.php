<?php

class AdminController extends Controller
{
        public function filters() {
            return array(
                    'userAccess + UsersToApprove, ApproveUsers, DeclineUsers, CommentsToApprove, ApproveComments, DeclineComments,
                        Index, AddArticle, DelArticle, EditArticle, UpdateArticle, AddThread, DelThread, ReplaceThread, mainArticle'
            );
        }

        /* Access control filter */

        public function filterUserAccess($filterChain){
            $blog_user = BlogSessions::getSession($_COOKIE['PHPSESSID']);

            if(empty($blog_user))
                $user_state = 'guest';
            else {
                $log = unserialize($blog_user->session_value);
                $user = BlogUsers::getBlogUser($log['id']);
                if($user->approved == 0)
                    $user_state = 'guest';
            }
            if($user_state == 'guest')
                $this->redirect(Yii::app()->params['http_addr'].'blog/users/login');
            $filterChain->run();
        }

        /* --- Blog users & blog comments block --- */

        public function actionUsersToApprove(){
            $approve = BlogUsers::getUnapprovedUsers();
            $this->render('users_approve_list', array('data' => $approve));
        }

        public function actionApproveUsers(){
            $user = BlogUsers::getBlogUser($_POST['user_id']);
            $user->approved = 1;
            $user->save();
            echo "User approved";
        }

        public function actionDeclineUsers(){
            $user = BlogUsers::getBlogUser($_POST['user_id']);
            $user->delete();
            echo "User declined";
        }

        public function actionCommentsToApprove(){
            $approve = BlogComments::getUnapprovedComments();            
            $this->render('comments_approve_list', array('data' => $approve));
        }

        public function actionApproveComments(){
            $comment = BlogComments::getComment($_POST['comment_id']);
            $comment->approved = 1;
            $comment->save();
            echo "Comment approved ";
        }

        public function actionDeclineComments(){
            $comment = BlogComments::getComment($_POST['comment_id']);
            $comment->delete();
            echo "Comment declined";
        }

        /* --- --- */

        public function actionIndex(){
            $articles = Articles::_list();
            $threads = Threads::_list();

            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }


        public function actionAddArticle(){

            $model = new Articles();
            $errors = array();
            $model->attach = CUploadedFile::getInstanceByName('new_art_file');
            
            if(!$model->attach){
                $error['type'] = 'addArticle';
                $error['value'] = 'Please, select your article file';
                
                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }
            
            $file_name = $model->attach->getName();
            $file_type = $model->attach->getType();
            $file_size = $model->attach->getsize();
            // File size check
            if($file_size > 1024*3*1024){
                $error['type'] = 'addArticle';
                $error['value'] = 'Too big file size';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // File type check
            $exp = explode('.', $file_name);
            if((count($exp)>2)||($exp[1] != 'txt')){
                $error['type'] = 'addArticle';
                $error['value'] = 'Only ".txt" files is avaliable';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // Article title cannot be blank
            if(empty($_POST['new_art_title'])){
                $error['type'] = 'addArticle';
                $error['value'] = 'Article title cannot be blank';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // All clear
            $model->link = $exp[0];
            $model->title = $_POST['new_art_title'];
            $model->author = $_POST['new_art_author'];
            if(!empty($_POST['articleslist_page']))
                $model->meta_page = 1;
            $model->addArticle();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionDelArticle(){
            $article = Articles::getArticle($_GET['id']);
            $error = array();
            // Checking exist tread with current article
            $thread = Threads::getThreadByArticle($_GET['id']);
            if(!empty($thread)){
                $error['type'] = 'articlesList';
                $error['value'] = 'Deleted article cannot be part of any thread. Please, delete thread at first';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // Delete from archive
            $archive = Archive::getArticle($_GET['id']);
            if(!empty($archive))
                $archive->delete();

            // Delete article file
            unlink(Yii::app()->params['project_root'].'/articles/'.$article->link.'.txt');

            // Delete article
            $article->delete();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionEditArticle(){
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile(Yii::app()->baseUrl.'/js/tiny_mce/tiny_mce.js');

            $updArticle = Articles::getArticle($_GET['id']);
            $updText = file_get_contents(Yii::app()->params['project_root'].'/articles/'.$updArticle->link.'.txt');

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array(
                    'articles' => $articles,
                    'threads' => $threads,
                    'updatedArt' => $updArticle,
                    'articleText' => $updText
                ));
        }

        public function actionUpdateArticle(){
            // Update article's title
            $updArt = Articles::getArticle($_POST['art_id']);
            $updArt->title = $_POST['art_title'];
            $updArt->save();

            // Update article's text
            $file = fopen(Yii::app()->params['project_root'].'/articles/'.$updArt->link.'.txt', 'w');
            fwrite($file, $_POST['art_text']);
            fclose($file);

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionAddThread(){
            $thread = new Threads();
            $error = array();

            // Thread Alias cannot be empty
            if(empty($_POST['alias'])){
                $error['type'] = 'addThread';
                $error['value'] = 'Thread alias cannot be blank';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // Thread title cannot be empty
            if(empty($_POST['alias'])){
                $error['type'] = 'addThread';
                $error['value'] = 'Thread title cannot be blank';

                $articles = Articles::_list();
                $threads = Threads::_list();
                $this->render('index', array('articles' => $articles, 'threads' => $threads, 'error' => $error));
                return;
            }

            // All clear
            $thread->article_id = $_POST['art_id'];
            $thread->title = $_POST['title'];
            $thread->alias = $_POST['alias'];
            $thread->save();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionDelThread(){
            $thread = Threads::getThread($_GET['id']);

            // Del from archive
            $archive = Archive::getThread($_GET['id']);
            if(!empty($archive)){
                foreach($archive as $ar)
                    $ar->delete();
            }
            // Thread delete
            $thread->delete();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        /* Replace main article of current thread with new and add previous article to archive list */
        public function actionReplaceThread(){
            $thread = Threads::getThread($_POST['thread_id']);

            // Add current thread to archive
            $archive = new Archive;
            $archive->thread_id = $thread->id;
            $archive->article_id = $thread->article_id;
            $archive->addToArchive();

            // Update current thread with new
            $thread->alias = $_POST['new_alias'];
            $thread->article_id = $_POST['new_art'];
            $thread->save();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionMainArticle(){
            $article = Articles::getArticle($_GET['id']);
            if($_GET['act'] == 'ins')
                $article->meta_page = 1;
            else
                $article->meta_page = 0;
            $article->save();

            $articles = Articles::_list();
            $threads = Threads::_list();
            $this->render('index', array('articles' => $articles, 'threads' => $threads));
        }

        public function actionReaders(){
            $readers = BlogUsers::getReaders();
            if(isset($_POST['add'])){
                $new_reader = new BlogUsers();
                $new_reader->login = $_POST['new_login'];
                $new_reader->password = $_POST['new_pass'];
                $new_reader->type = 1;
                $new_reader->register_date = date('Y-m-d H:i:s');
                $new_reader->approved = 0;
                $new_reader->save();

                $readers = BlogUsers::getReaders();
                $this->render('readers', array('data' => $readers));
                return;
            }
            if(isset($_GET['del'])){
                $reader = BlogUsers::getBlogUser($_GET['del']);
                $reader->delete();

                $readers = BlogUsers::getReaders();
                $this->render('readers', array('data' => $readers));
                return;
            }
            $this->render('readers', array('data' => $readers));
        }

        public function actionDomains(){
            if(isset($_POST['add'])){
                $new_domain = new BlogDomains();
                $new_domain->name = $_POST['new_domain'];
                $new_domain->addDomain();

                $domains = BlogDomains::getAllDomainsData();
                $this->render('domains', array('data' => $domains));
                return;
            }
            if(isset($_GET['del'])){
                $domain = BlogDomains::getDomain($_GET['del']);
                $domain->delete();

                $domains = BlogDomains::getAllDomainsData();
                $this->render('domains', array('data' => $domains));
                return;
            }
            $domains = BlogDomains::getAllDomainsData();
            $this->render('domains', array('data' => $domains));
        }

        public function actionEditReader(){
            $reader = (isset($_GET['id'])) ? BlogUsers::getBlogUser($_GET['id']) : BlogUsers::getBlogUser($_POST['id']);
            $domains = BlogDomains::getAllDomains();
            if(isset($_POST['update'])){

                $reader->login = $_POST['login'];
                $reader->password = $_POST['pass'];
                $reader->save();

                $readers = BlogUsers::getReaders();
                $this->render('readers', array('data' => $readers));
                return;
            }
            $this->render('editReader', array('reader' => $reader, 'domains' => $domains));
        }
        
}


?>
