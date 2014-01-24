<?php
/* @var $this MessagesController */
/* @var $model MessageForm */
/* @var $users Users */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Messages';
$this->breadcrumbs=array(
        'Messages',
);?>
<h1>Send message</h1>
<?php if(Yii::app()->user->hasFlash('contact')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('contact'); ?>
	</div>
<?php else: ?>
	<?php $this->renderPartial('_messageForm', array('model'=>$model,'users'=>$users)) ?>
<?php endif; ?>
<h1>Message List:</h1>