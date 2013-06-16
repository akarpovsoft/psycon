<?php foreach ($links as $link): ?>
<td>
    <a href="<?php echo $link['link'] ?>" style="text-decoration: none;">
        <img border="0" src="<?php echo $link['img'] ?>">
    </a>
</td>
<?php endforeach; ?>
