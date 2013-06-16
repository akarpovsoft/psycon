<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_viewQuestions',
    'template'=>"{items}\n{pager}",
)); ?>