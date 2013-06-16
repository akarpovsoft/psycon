<h2>
<a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres(); ?>/default/question<?php echo '?id='. $data->id; ?>"><?php echo $data->title; ?></a>
</h2>
<p style="color: #696969"> Posted at <?php echo date('F j, Y \a\t h:i a',$data->createtime); ?> </p>
<p> <?php echo $data->content; ?> </p>
<?php 
$answers =  SiteAnswers::model()->findAllByAttributes(array('question_id'=>$data->id,'pub'=>1));

if (count($answers)) {
    echo 'Have '.count($answers).' answers';
    echo ' from ';
    foreach ($answers as $ans) {
        $string .=  answersFunc::readerLogin($ans->author_id) . ', ';
    }
    echo  substr($string,0,strlen($string)-2); 
} else {
    echo 'No answers';
} ?> 