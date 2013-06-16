<h2><a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres().'?'.answersFunc::addSessionToUrl(); ?>" > Back</a></h2>
<div class="form">

<?php $form=$this->beginWidget('system.web.widgets.CActiveForm', array(
	'id'=>'SiteQuestion-form',
	'enableAjaxValidation'=>false,
)); ?>

<h1>Create question</h1>

	<?php echo $form->errorSummary($model); ?>
    
    <div class="row">
    <input type="checkbox" name="pub" value="1"/> Show the question to other users?
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>20, 'cols'=>60)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>