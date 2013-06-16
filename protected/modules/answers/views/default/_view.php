<h1> 
<a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres(); ?>/default/view<?php echo '?id='. $data->id .'&'. answersFunc::addSessionToUrl(); ?>"><?php echo $data->title; ?></a>
<?php if($data->pub) { echo ' * ';} ?>
</h1>
<p style="color: #696969"> Posted by <?php echo answersFunc::userLogin($data->author_id); ?> on <?php echo date('F j, Y \a\t h:i a',$data->createtime); ?> </p>
<p> <?php echo $data->content; ?> </p>
<?php if (count(SiteAnswers::model()->findAllByAttributes(array('question_id'=>$data->id)))) {
    echo 'Have '.count(SiteAnswers::model()->findAllByAttributes(array('question_id'=>$data->id))).' answers';
    echo ' from ';
    foreach ( SiteAnswers::model()->findAllByAttributes(array('question_id'=>$data->id)) as $ans ) {
        $string .=  answersFunc::readerLogin($ans->author_id) . ', ';
    }
    echo  substr($string,0,strlen($string)-2); 
} else {
    echo 'No answers';
} ?> 
