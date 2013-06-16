<h2>Double readers</h2>
<form action="<?php echo Yii::app()->params['http_addr']; ?>admin/doubleReaders" method="post">
<table>
<?php for($i=0;$i<count($readers);$i++): ?>
    <?php $id2 = ''; $id3 = ''; $id4 = ''; ?>
    <tr>
    <td>
    <input type="hidden" name="id_<?php echo $i; ?>" value="<?php echo $readers[$i]['id'] ?>">
    <?php echo $readers[$i]['name']; ?> &nbsp;
    </td>
    <td>
    <select name="id2_<?php echo $i; ?>">
        <option value="">Please select...</option>
        <?php for($j=0;$j<count($readers);$j++): ?>
            <?php if($readers[$i]['id'] != $readers[$j]['id']): ?>
                <option value="<?php echo $readers[$j]['id']; ?>" 
                    <?php if(DoubleReaders::searchPair($readers[$i]['id'], $readers[$j]['id']))
                    {
                        $id2 = $readers[$j]['id'];
                        echo  'selected';
                    } else
                        echo ''; 
                    ?>
                >
                    <?php echo $readers[$j]['name'] ?>
                </option>
            <?php endif; ?>
        <?php endfor; ?>
    </select>
        <select name="id3_<?php echo $i; ?>">
        <option value="">Please select...</option>
        <?php for($j=0;$j<count($readers);$j++): ?>
        <?php 
        if($readers[$j]['id'] == $id2)
            continue;
        ?>
            <?php if($readers[$i]['id'] != $readers[$j]['id']): ?>
                <option value="<?php echo $readers[$j]['id']; ?>" 
                    <?php if(DoubleReaders::searchPair($readers[$i]['id'], $readers[$j]['id']) && $readers[$j]['id'] != $id2)
                    {
                        $id3 = $readers[$j]['id'];
                        echo  'selected';
                    } else
                        echo ''; 
                    ?>
                 >
                    <?php echo $readers[$j]['name'] ?>
                </option>
            <?php endif; ?>
        <?php endfor; ?>
    </select>
    <select name="id4_<?php echo $i; ?>">
        <option value="">Please select...</option>
        <?php for($j=0;$j<count($readers);$j++): ?>
        <?php 
        if($readers[$j]['id'] == $id2 || $readers[$j]['id'] == $id3)
            continue;
        ?>
            <?php if($readers[$i]['id'] != $readers[$j]['id']): ?>
                <option value="<?php echo $readers[$j]['id']; ?>" 
                    <?php if(DoubleReaders::searchPair($readers[$i]['id'], $readers[$j]['id']) && $readers[$j]['id'] != $id2 && $readers[$j]['id'] != $id3)
                    {
                        $id4 = $readers[$j]['id'];
                        echo  'selected';
                    } else
                        echo ''; 
                    ?>
                >
                    <?php echo $readers[$j]['name'] ?>
                </option>
            <?php endif; ?>
        <?php endfor; ?>
    </select>
    </td>
    </tr>
<?php endfor; ?>
    <tr>
        <td colspan="2" align="center">
            <input type="submit" value="Send">
        </td>
    </tr>
</table>
</form>
