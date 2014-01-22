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

<h3>Users at system:</h3>
<table class="mails" data-bind="with: userListData">
    <thead><tr>
	    <th>Name</th>
	    <th>E-mail</th>
    </tr></thead>
    <tbody data-bind="foreach: users">
        <tr>
            <td data-bind="text: name"></td>
            <td data-bind="text: email"></td>
        </tr>
    </tbody>
</table>