<?php $i=1; ?>
<?php if($page_type == 'new'): ?>
    <ul class="member_instructions">
        <?php foreach($menues as $menu): ?>
            <li><?php echo $i; ?> 
                <a class="LinkMedium" href="<?php echo $menu['link']; ?>"><?php echo $menu['title']; ?></a>      
                <?php echo ($menu['add']) ? $menu['add'] : '' ?>
            </li>           
    <?php $i++; ?>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
<?php foreach($menues as $menu): ?>
<tr>
    <td>
        <img width="56" height="55" src="<?php echo $menu['pic']; ?>"/>
    </td>
    <td width="15" nowrap=""><img src="<?php echo Yii::app()->params['http_addr'] ?>/images/transp.gif"/></td>
    <td nowrap="">
        <b><?php echo $i; ?> <a class="LinkMedium" href="<?php echo $menu['link']; ?>"><?php echo $menu['title']; ?></a>
            <?php echo ($menu['add']) ? $menu['add'] : '' ?>
        </b>
    </td>
    <td width="25" nowrap=""><img src="<?php echo Yii::app()->params['http_addr'] ?>/images/transp.gif"/></td>
    <td width="100%" valign="middle">
    </td>
</tr>
<?php $i++; ?>
<?php endforeach; ?>
<?php endif; ?>