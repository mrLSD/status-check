<?php
/* @var $this MessagesController */
/* @var $model MessageForm */
/* @var $users Users */
/* @var $messages Messages */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Messages';
$this->breadcrumbs=array(
        'Messages',
);?>
<h1>Send message</h1>
<?php if(Yii::app()->user->hasFlash('sended')): ?>
	<div class="flash-success">
		Message successful sended.
	</div>
<?php else: ?>
	<?php $this->renderPartial('_messageForm', array('model'=>$model,'users'=>$users)) ?>
<?php endif; ?>
<h1>Message List:</h1>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => new CArrayDataProvider( $messages ),
	'itemView'=>'_viewMessages',
)); ?>