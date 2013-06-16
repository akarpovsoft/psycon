<img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/beach-img.jpg" />
<h2>Articles / Blog</h2>
<ul>
    <li>
        <a href='<?php echo Yii::app()->params['http_addr'] ?>articles'>Spiritual articles</a>
    </li>
    <li>
        <a href='<?php echo Yii::app()->params['site_domain'] ?>/blog'>Psychic blog</a>
    </li>
    <?php foreach($art as $thread): ?>
        <li>
            <a href='<?php echo Yii::app()->params['http_addr'] ?>articles/thread?id=<?php echo $thread['alias']; ?>'><?php echo $thread['title']; ?></a>
        </li>
    <?php endforeach; ?>    
</ul>