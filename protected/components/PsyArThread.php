<?php
/* 
 *  Articles thread
 */
class PsyArThread
{
    private $id;
    private $fs;

	public function __construct($id) {
        $this->id = $id;
        $this->fs=new PsyArFileStore(Yii::app()->params['project_root']."/articles");
        $arthreads=$this->_list();
            if(is_array($arthreads)) {
        	foreach($arthreads as $k=>$thread) {
                    if($thread["alias"]==$id) {
                        $this->id = $thread['id'];
                    }
                }
            }
	}

    public function _list(){
    	$arthreads=$this->fs->load("arthreads.php", "arthreads");
        if(!is_array($arthreads))
            $arthreads=array();
    	return $arthreads;
    }

    public function load(){
    	$arthreads=$this->_list();
    	if(is_array($arthreads)) {
        	foreach($arthreads as $k=>$thread) {
        	    if($thread["id"]==$this->id) {
        	    	return $thread;
        	    }
        	}
    	}
    	return false;
    }

    public function save($article, $alias, $title=null){
    	$arthreads=$this->_list();
    	$done=false;
    	if(is_array($arthreads)) {
        	foreach($arthreads as $k=>$thread) {
        	    if($thread["id"]==$this->id) {
        	        $this->addToArchive($thread["article"], $thread["alias"], $thread["title"], $thread["time"]);
        	        $arthreads[$k]["article"]=$article;
                        $arthreads[$k]["alias"]=$alias;
        	        $arthreads[$k]["time"]=time();
        	        if(!is_null($title))
        	        	$arthreads[$k]["title"]=$title;
        	        $done=true;
        	    }
        	}
    	}
    	else {
    	    $arthreads=array();
    	}
    	if(!$done) {
    	   $arthreads[]=array("id" => $this->id, "article" => $article, "alias" => $alias, "title" => $title, "time" => time());
    	}
    	$this->fs->save("arthreads.php", $arthreads, "arthreads");
    }

    public function delete(){
    	$arthreads=$this->_list();
        $tmp=array();
    	foreach($arthreads as $k=>$thread) {
    	    if($thread["id"]!=$this->id) {
    	        $tmp[]=$thread;
    	    }
    	}
    	$this->fs->save("arthreads.php", $tmp, "arthreads");
    	$this->deleteArchive();
    }

    public function addToArchive($article, $title, $time) {
        $archive=$this->fs->load("archive.php", "archive");
    	if(!is_array($archive))
            $archive=array();
        $archive[]=array("id" => $this->id, "article" => $article, "title" => $title, "time" => $time);
    	$this->fs->save("archive.php", $archive, "archive");

    }

    public function archiveList() {
        $archive=$this->fs->load("archive.php", "archive");
        $tmp=array();
        if(is_array($archive)) {
        	foreach($archive as $k=>$thread) {
        	    if($thread["id"]==$this->id) {
        	        $tmp[$thread["time"]]=$thread;
        	    }
        	}
        }
        ksort($tmp);
        return array_values($tmp);
    }

    public function deleteArchive() {
        $archive=$this->fs->load("archive.php", "archive");
        $tmp=array();
    	foreach($archive as $k=>$thread) {
    	    if($thread["id"]!=$this->id) {
    	        $tmp[]=$thread;
    	    }
    	}
    	$this->fs->save("archive.php", $tmp, "archive");
    }

    public function search($article) {

    	$arthreads=$this->_list();
    	if(is_array($arthreads)) {
        	foreach($arthreads as $k=>$thread) {
        	    if($thread["article"]==$article) {
        	        return true;
        	    }
        	}
    	}

        $archive=$this->fs->load("archive.php", "archive");
        if(is_array($archive)) {
        	foreach($archive as $k=>$thread) {
        	    if($thread["article"]==$article) {
        	        return true;
        	    }
        	}
        }

    	return false;

    }
}

?>
