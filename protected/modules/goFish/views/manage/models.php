<h1><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/index';?>" > Manage Answers</a></h1>
<h1>Manage Fish models</h1>
<form action="#" method="post">
<table>
<tr><td>Reader</td><td>Model</td><td>Fish name</td></tr>
<?php foreach ($readers as $reader): ?>
    <tr>
        <td><?php echo $reader->name; ?></td>
        <td><?php echo GoFishFunc::writeSelect($reader->rr_record_id); ?></td>
        <td>
            <input type="text" name="fish_name[<?php echo $reader->rr_record_id; ?>]"
                   value="<?php echo GoFishFunc::getFishName($reader->rr_record_id); ?>">
        </td>
    </tr>
<?php endforeach; ?>
</table>
<input type="submit" value="Update" /> <br /><br />
</form>