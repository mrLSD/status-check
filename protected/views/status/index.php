<?php
/* @var $this StatusController */
/* @var $users Users */

// Init special script for this page
$cs = Yii::app()->clientScript;
$cs->registerCssFile('/css/sap.css');
$cs->registerScriptFile('/js/knockout-2.2.1.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/sap.js', CClientScript::POS_END);

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Status check',
);?>
<h1>Welcome, <?= $this->user['name'] ?>! </h1>

<?php // User status message ?>
<a id="status-message" data-bind="text: userStatus, visible: userStatusVisible, click: addStatus" href="#"><?= $this->user['status_message'] ?></a>
<form data-bind="submit: addStatus">
	<input data-bind="visible:  userStatusVisible()!=true, value: userStatus">
</form>
<a data-bind="click: addStatus, visible: statusChange" href="#">Change status</a>

<br/><br/>

<h3>Users at system:</h3>

<?php $this->renderPartial("_filter") ?>

<table class="mails" data-bind="with: userListData">
    <thead><tr>
	    <th>Name</th>
	    <th>E-mail</th>
	    <th data-bind="visible: $root.filter_online">Online status</th>
	    <th data-bind="visible: $root.filter_status()">Status message</th>
    </tr></thead>
    <tbody data-bind="foreach: users">
        <tr>
		<td data-bind="text: name"></td>
		<td data-bind="text: email"></td>
		<td data-bind="visible: $root.filter_online"><strong data-bind="text: online"></strong></td>
		<td data-bind="text: status_message, visible: $root.filter_status()"></td>

        </tr>
    </tbody>
</table>
<h5>Online Users: <span data-bind="text: onlineTotalUsers"></span> of Total Users: <span data-bind="text: totalUsers"></span></h5>