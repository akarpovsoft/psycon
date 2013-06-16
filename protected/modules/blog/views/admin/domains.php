<center>
    <form action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/domains" method="POST">
        <table border>
            <tr>
                <td colspan="2">Add new reader</td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="new_domain" value=""></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><input type="submit" name="add" value="Add"></td>
            </tr>
        </table>
    </form>
    <h2>DOMAINS LIST</h2>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
        'columns' => array(
                array(
                        'name' => 'Key',
                        'value' => '$data->key'
                ),
                array(
                        'name' => 'Domain',
                        'value' => '$data->name'
                ),
                array(
                        'template' => '{update} {delete}',
                        'buttons' => array(
                            'update' => array(
                                'url' => '"editDomain?id=".$data->id'
                            ),
                            'delete' => array(
                                'url' => '"domains?del=".$data->id'
                            ),
                        ),
                        'class'=>'CButtonColumn',
                ),
            ),
        'emptyText' => 'There is no domains for approve'
));
?>
</center>
