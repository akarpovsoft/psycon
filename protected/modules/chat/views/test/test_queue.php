<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery-1.5.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/jquery.json-2.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/util.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/oop.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/chat.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/chat_queue.js"></script>
<script>
    function init()
    {
        var ai = new AJAXInteraction;
        options = {
			url     : "http://localhost/psychic_contact_com_new/new/public_html/advanced/chat/test/testQueue?t=1",
		};
        ai.doPost(options);
    }
var mytimer = setInterval("init()",3000) ;    
</script>
