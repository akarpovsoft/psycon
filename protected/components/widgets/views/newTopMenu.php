<?php $i=0; ?>
<div class="navigation">
    <img src="/advanced/new_images/nav-right.jpg" style="float: right;" /><img src="/advanced/new_images/nav-left.jpg" style="float: left;" />
    <ul>
     <?php foreach($links as $name=>$link): ?>
         <?php $i++; ?>
         <li>
             <a href="<?php echo $link; ?>" 
             <?php echo (Yii::app()->params['site_domain'].$_SERVER['REQUEST_URI'] == $link) ? 'class="current"' : ''; ?>>
             <?php echo $name; ?>
             </a>
         </li>
         <?php if($i < count($links)): ?>
         <li>
             <img src="/advanced/new_images/navigation-break.jpg" />
         </li>
         <?php endif; ?>         	
     <?php endforeach; ?>
    </ul>
</div>
