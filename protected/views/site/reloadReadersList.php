<?php if($title == 'no_title'): ?>
<?php

$cat = htmlentities(strip_tags($cat));
$keyword = htmlentities(strip_tags($keyword));
$type = htmlentities(strip_tags($type));
$online = htmlentities(strip_tags($online));
$cnt = htmlentities(strip_tags($cnt));


 $this->widget('ReadersList', array('online' => $online, 'type' => $type, 'cat' => $cat, 'count' => $cnt, 'keyword' => $keyword)); ?>
<?php else: ?>
<div class="online_readers">               
    <h2>Online Readers</h2>              
    <?php $this->widget('ReadersList', array('online' => $online, 'type' => $type, 'cat' => $cat, 'count' => $cnt, 'keyword' => $keyword)); ?>         
    <img src="/advanced/new_images/reader-bottom.jpg" />
</div>
<br>
<center><a href="<?php echo Yii::app()->params['http_addr'] ?>site/ourreaders">See More Readers Online</a></center>
<?php endif; ?>
