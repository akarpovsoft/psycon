<center>Pending Testimonials</center>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'id' => 'admin-ts',
    'columns' => array(
        array(
            'name' => 'Save date',
            'value' => '$data->ts'
        ),
        array(
            'name' => 'Testimonial Date',
            'value' => '$data->tm_date'
        ),
        array(
            'name' => 'Text',
            'value' => '$data->tm_text'
        ), 
        array(
            'name' => 'Reader',
            'value' => '$data->account->name'
        ),
        array(
            'template' => '{approve} {delete} {update}',
            'buttons' => array(
                'approve' => array(
                    'url' => 'Yii::app()->params["http_addr"]."testimonials/approve?id=".$data->id',
                    'imageUrl' => 'http://www.psychic-contact.com/advanced/images/icon-approve.png',
                    'click' => "function() {
                                var th=this;
                                var afterDelete=function(){};
                                $.fn.yiiGridView.update('admin-ts', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                                $.fn.yiiGridView.update('admin-ts');
                                                afterDelete(th,true,data);
                                        },
                                        error:function(XHR) {
                                                return afterDelete(th,false,XHR);
                                        }
                                });
                                return false;
                                }",
                ),
                'delete' => array(
                    'url' => 'Yii::app()->params["http_addr"]."testimonials/delete?id=".$data->id',
                    'imageUrl' => 'http://www.psychic-contact.com/advanced/images/icon-decline.png'
                ),
                'update' => array(
                    'url' => 'Yii::app()->params["http_addr"]."testimonials/edit?id=".$data->id',
                ),
            ),
            'class'=>'CButtonColumn',
        ),
    ),
));
?>
