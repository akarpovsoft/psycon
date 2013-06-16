<script type="text/javascript">
tinyMCE.init({ 
// General options 
mode : "textareas", 
theme : "advanced", 
plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras", 
theme_advanced_buttons1 : "preview,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,link,unlink,image,code", 
theme_advanced_buttons2 : "undo,redo,|,forecolor,backcolor,|,tablecontrols,|,hr,removeformat,|,sub,sup,|,charmap,emotions", 
theme_advanced_buttons3 : "", 
theme_advanced_toolbar_location : "top", 
theme_advanced_toolbar_align : "left", 
theme_advanced_statusbar_location : "bottom", 
theme_advanced_resizing : true
}); 
</script>
<h2><a href="<?php echo Yii::app()->params['http_addr'] ?>staticPage">Manage pages</a></h2>

<?php if($page->id): ?>
<center><h1>Update Page <?php echo $page->fullname; ?></h1></center>
<?php else: ?>
<center><h1>Create new page</h1></center>
<?php endif; ?>
<?php if($errors): ?>
    <?php foreach($errors as $error): ?>
    - <font color="red"><b><?php echo $error[0]; ?></b></font><br>
    <?php endforeach; ?>
<?php endif; ?>
<br>All Fields marked with a red <font color="red">*</font> are required

<?php echo CHtml::beginForm(); ?>
<table>
    <tr>
        <td>
           <font color="red">*</font> Name:
        </td>
        <td>
            <input type="text" name="fullname" value="<?php echo $page->fullname; ?>">
        </td>
    </tr>
    <tr>
        <td>
            Title:
        </td>
        <td>
            <input type="text" name="title" value="<?php echo $page->title; ?>">
        </td>
    </tr>
    <tr>
        <td>
           <font color="red">*</font> Alias (shows in page URL):
        </td>
        <td>
            <input type="text" name="alias" value="<?php echo $page->alias; ?>">
        </td>
    </tr>
    <tr>
        <td>
            Keywords (for SEO):
        </td>
        <td>
            <input type="text" name="keywords" value="<?php echo $page->keywords; ?>" size="50">
        </td>
    </tr>
    <tr>
        <td>
            Description (for SEO):
        </td>
        <td>
            <input type="text" name="description" value="<?php echo $page->description; ?>" size="50">
        </td>
    </tr>
    <tr>
        <td>
           <font color="red">*</font> Content:
        </td>
        <td>
            <textarea name="content" cols="50" rows="10"><?php echo $page->content; ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="submit" value="Send data">
        </td>
    </tr>
</table>
<?php echo CHtml::endForm(); ?>