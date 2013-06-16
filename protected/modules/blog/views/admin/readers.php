<center>
    <form action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/readers" method="POST">
        <table border>
            <tr>
                <td colspan="2">Add new reader</td>
            </tr>
            <tr>
                <td>Login:</td>
                <td><input type="text" name="new_login" value=""></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="text" name="new_pass" value=""></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><input type="submit" name="add" value="Add"></td>
            </tr>
        </table>
    </form>
    <h2>READERS LIST</h2>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
        'columns' => array(
                array(
                        'name' => 'Login',
                        'value' => '$data->login'
                ),
                array(
                        'name' => 'Password',
                        'value' => '$data->password'
                ),
                array(
                        'name' => 'Register date',
                        'value' => '$data->register_date'
                ),
                array(
                        'template' => '{update} {delete}',
                        'buttons' => array(
                            'update' => array(
                                'url' => '"editReader?id=".$data->id'
                            ),
                            'delete' => array(
                                'url' => '"readers?del=".$data->id'
                            ),
                        ),
                        'class'=>'CButtonColumn',
                ),
        ),
        'emptyText' => 'There is no readers'
));
?>
</center>