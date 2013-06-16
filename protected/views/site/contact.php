<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('lang', 'Contact_us');
$this->breadcrumbs=array(
	Yii::t('lang', 'Contact_contact'),
);
?>

<h1><?php echo Yii::t('lang', 'Contact_us'); ?></h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p><?php echo Yii::t('lang', 'business_inquiries'); ?></p>

<div class="form">

<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo Yii::t('lang', 'Fields_with'); ?> <span class="required">*</span> <?php echo Yii::t('lang', 'are_required'); ?></p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'name'); ?>
		<?php echo CHtml::activeTextField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'email'); ?>
		<?php echo CHtml::activeTextField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'subject'); ?>
		<?php echo CHtml::activeTextField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'body'); ?>
		<?php echo CHtml::activeTextArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo CHtml::activeTextField($model,'verifyCode'); ?>
		</div>
		<div class="hint">
        <?php echo Yii::t('lang', 'Please_enter_the_letters'); ?>
        </div>
	</div>
	<?php endif; ?>

	<div class="row submit">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->

<?php endif; ?>