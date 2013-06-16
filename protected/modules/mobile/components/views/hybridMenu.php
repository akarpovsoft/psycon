<ul>
<?php foreach($links as $link): ?>
    <li><a href="<?php echo $link['Link']; ?>"><?php echo $link['Title']; ?></li>
<?php endforeach; ?>
</ul>