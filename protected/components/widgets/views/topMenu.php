<tr>
	<td height="28" valign="middle" background="<?php echo Yii::app()->params['http_addr']; ?>images/tabs_bg.gif">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="white13">
		  <tr>
			<?php foreach($items as $k=>$item): ?>
				<td width="<?php echo $item['width']; ?>" align="center" valign="middle">
					<?php echo CHtml::link($item['label'],$item['url'],array('class'=>'white13')); ?>
				</td>
				<?php echo ($k==sizeof($items)-1)? '' : '<td width="1%" align="center">|</td>';?>
			<?php endforeach; ?>
		  </tr>
		</table>
	</td>
</tr>




	