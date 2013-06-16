<center>
    <table>
        <tr>
            <td>
                <a href="<?php Yii::app()->params['http_addr'] ?>">Home</a>
            </td>
            <td>
                |
            </td>
            <td>
                <a href="<?php Yii::app()->params['http_addr'] ?>users/mainmenu">Your account</a>
            </td>
        </tr>
    </table>
</center>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$data,
            'columns' => array(
                array(
                    'name' => 'Id',
                    'value' => '$data["Id"]'
                ),
                array(
                    'name' => 'Date',
                    'value' => '$data["Date"]'
                ),
                array(
                    'name' => 'Username',
                    'value' => '$data["Username"]'
                ),
                array(
                    'name' => 'Notes',
                    'type' => 'html',
                    'value' => '$data["Notes"]'
                ),
                array(
                    'name' => '',
                    'type' => 'raw',
                    'value' => '"<a href=\"".Yii::app()->params["http_addr"]."users/nrrQuest?del=".$data["Id"]."\">Finish and delete</a>"'
                ),
            ),
        ));
?>
