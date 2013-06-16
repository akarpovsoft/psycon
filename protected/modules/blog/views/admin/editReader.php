<center>
    <form action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/editReader" method="POST">
    <input type="hidden" name="id" value="<?php echo $reader->id; ?>">
    <table>
        <tr>
            <td>Reader Login:</td>
            <td><input type="text" name="login" value="<?php echo $reader->login ?>"></td>
        </tr>
        <tr>
            <td>Reader Password:</td>
            <td><input type="text" name="pass" value="<?php echo $reader->password ?>"</td>
        </tr>
        <tr>
            <td>Reader's domain:</td>
            <td>
                <select name="domain">
                    <?php foreach($domains as $domain): ?>
                    <option value="<?php echo $domain->id; ?>"><?php echo $domain->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" name="update" value="Send"></td>
        </tr>
    </table>
    </form>
</center>
