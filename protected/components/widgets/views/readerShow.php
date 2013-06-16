<div class='readerInfo'>
        <div class='readerPhoto'>
            <img src='<?php echo Yii::app()->params['http_addr']; ?>api/xml/convertImage?id=<?php echo $Reader->rr_record_id; ?>&width=100' hspace="0" vspace="0" align="top">
        </div>

        <div class='readerDetails'>
                <b><?php echo $Reader->name; ?></b>
                <p><a href='<?php echo Yii::app()->params['http_addr']; ?>site/profile/readerview?id=<?php echo $Reader->rr_record_id; ?>' style="font-size: 13px;">Read Bio...</a></p>                
                <img src='<?php echo $Status; ?>'>

        </div>
        <div class='readerClients'>
            <a href='<?php echo Yii::app()->params['http_addr']; ?>profile/readerview?id=<?php echo $reader['@attributes']['id']; ?>' style="font-size: 13px;"><b><?php echo Testimonials::getCount($reader['@attributes']['id']); ?></b> Client Testimonials</a>
        </div>
</div>
