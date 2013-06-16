<?php
class XmlController extends Controller
{
    public function actionThread($k){
        $domain = BlogDomains::getDomainByKey($k);
        $threads = Threads::getThreadByDomain($domain->id, 'blog_articles');

        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('threads');        
        foreach($threads as $thread){
            $threadDom = $dom->createElement('thread');
            $root->appendChild($threadDom);
            $threadDom->setAttribute('id', $thread->id);
            $threadDom->setAttribute('title', $thread->title);
            $threadDom->setAttribute('alias', $thread->alias);
            
            $artText = file_get_contents(Yii::app()->params['project_root'].'/articles/'.$thread->blog_articles->link.'.txt');            
            $text = $dom->createElement('article');
            $cont = $dom->createCDATASection($artText);
            $text->appendChild($cont);
            $root->appendChild($text);
            
            $archive = Archive::getThread($thread->id, 'blog_articles');
            
            $storage = $dom->createElement('archive');
            foreach($archive as $arch){
                $archElem = $dom->createElement('archRecord');
                $storage->appendChild($archElem);
                $archElem->setAttribute('id', $arch->id);
                $archElem->setAttribute('title', $arch->blog_articles->title);
                $archElem->setAttribute('link', $arch->blog_articles->link);
                $archElem->setAttribute('date', $arch->date);
            }
            $root->appendChild($storage);            
        }
        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        
        die();
    }
}

?>
