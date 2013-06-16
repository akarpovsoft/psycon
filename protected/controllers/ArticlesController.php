<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ArticlesController extends PsyController
{    
    public function actionIndex()
    {
        $path = Yii::app()->params['project_root'] . '/articles/';
        $fileStore = new PsyArFileStore($path);
        $full_artlist = $fileStore->load('meta.php', 'articles');
        $meta_artlist = $fileStore->load('metaphysical.php', 'metaphysical');
        
        if (isset($_GET['alias']))
        {
            $exp = explode('_', $_GET['alias']);
            $alias = substr($_GET['alias'], strlen($exp[0])+1);

            foreach($full_artlist as $art)
            {
                if($art['alias'] === $alias){
                    $article_author = $art['author'];
                    $article_link = $art['link'];
                }
            }
            if(empty($article_link))
                $article_link = $alias;

            if(file_exists($path . $article_link . '.txt'))
            	$article = file_get_contents($path . $article_link . '.txt');
            $this->render('index', array('single' => $article, 'author' => $article_author));
        } else
        {
            $artlist = array();
            foreach($full_artlist as $art)
            {
                if(in_array($art['link'], $meta_artlist))
                    $artlist[] = $art;
            }
                
            
            $this->render('index', array('artlist' => $artlist));
        }
    }
    
    public function actionThread()
    {
        if (!isset($_GET['id']))
            $this->redirect(Yii::app()->params['http_addr']);
        $art = new PsyArThread($_GET['id']);
        $data = $art->load();
        if (!is_array($data) || !file_exists(Yii::app()->params['project_root'] .
            "/articles/" . $data['article'] . ".txt"))
        {
            $err_msg = "Sorry, article doesn't exist";
            $this->render('thread', array('error' => $err_msg));
            return;
        } else
        {
            $article_text = file_get_contents(Yii::app()->params['project_root'] .
                "/articles/" . $data['article'] . ".txt");
            $archive = $art->archiveList();
        }
        $this->render('thread', array('text' => $article_text, 'archive' => $archive));
    }
}
?>
