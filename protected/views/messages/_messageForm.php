<?php
/* @var $this MessagesController */
/* @var $model MessageForm */
/* @var $users Users */
/* @var $form CActiveForm */
//new CListView();
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'message-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
)); ?>
	<h5><a href="#" data-bind="click: selectUserMessageRecipients">Recipient filter<span data-bind="text: selectedUsersMessageCount"></span></a></h5>
	<div data-bind="visible: userMessageRecipients">
	<?php if( count($users['users']) > 0 ): ?>
		<div>
			<label for="all-users-message-check">All</label>
			<input id="all-users-message-check" type="checkbox" data-bind="checked: allUsersMessageCheck, event:{change: allUsersChecked}"/>
		</div>
	<?php endif; ?>
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider' => new CArrayDataProvider( $users['users'] ),
			'itemView'=>'_view',
			'id' => 'users-message-list',
			'template'=>"{items}",
			'enablePagination'=>false,
		)); ?>
	</div>
        <div class="row">
                <?php echo $form->labelEx($model,'subject'); ?>
                <?php echo $form->textField($model,'subject',array('size'=>28, 'data-bind'=>'enable: messageSendEnable')); ?>
                <?php echo $form->error($model,'subject'); ?>
        </div>
        <div class="row">
                <?php echo $form->labelEx($model,'body'); ?>
                <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'data-bind'=>'enable: messageSendEnable')); ?>
                <?php echo $form->error($model,'body'); ?>
        </div>
        <div class="row buttons">
                <?php echo CHtml::submitButton('Send', array('data-bind'=>'enable: messageSendEnable')); ?>
        </div>
<?php $this->endWidget(); ?>
</div>