<?php $i = 0; ?>
    <table width="100%">
        <tr>
        <?php foreach($links as $link): ?>
            <td width="140" align="center" style="vertical-align: middle;">
                <a href="<?php echo $link['Link']; ?>"><?php echo $link['Title']; ?></a>
            </a>
            <?php $i++; ?>
            <?php if($i%3 == 0): ?>
            </tr><tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tr>
    </table>
<br>
<center>
<?php if(!empty($leftArt) && (!$this->guest)): ?>
<?php foreach($leftArt as $thread): ?>
    <a href ="<?php echo Yii::app()->params['http_addr'] ?>articles/thread?id=<?php echo $thread['alias']; ?>"><?php echo $thread['title']; ?></a>
<br>
<?php endforeach; ?>
<?php endif; ?>
</center>