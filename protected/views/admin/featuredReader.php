<center>
    <form action="<?php echo Yii::app()->params['http_addr']; ?>admin/featuredReader" method="POST" enctype="multipart/form-data">
        <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
                <font color="red"><b><?php echo $error; ?></b></font>
            <?php endforeach; ?>
        <?php endif; ?>
    <table border>
        <tr>
            <td colspan="2" align="center">
                Featured Reader
            </td>
        </tr>
        <tr>
            <td>
                Reader:
            </td>
            <td>
                <select name="name">
                    <option id="0">-- SELECT READER --</option>
                    <?php foreach($readers as $reader): ?>
                    <option id="<?php echo $reader->rr_record_id;?>" <?php echo ($reader->name == rtrim($fr_data['name'])) ? 'selected' : '' ?>><?php echo $reader->name; ?></option>
                    <?php endforeach; ?>
                </select>                
            </td>
        </tr>
        <tr>
            <td>
                Reader photo:
            </td>
            <td>
                <input type="file" name="photo" size="25"> <?php echo $FR->loadFRImage(); ?>
            </td>
        </tr>
        <tr>
            <td>
                Description:
            </td>
            <td>
                <textarea cols="30" rows="10" name="description"><?php echo $fr_data['desc']; ?></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" name="add_fr" value="Save">
    </form>
    
    <?php $FR->drawFRBox(); ?>
</center>
