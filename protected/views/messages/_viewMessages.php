<?php
/* @var $this MessagesController */
/* @var $data Messages */
?>
<hr/>
<table border="0">
	<tr>
		<td>
			<strong>Message:</strong> #<?= ($index+1) ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Subject:</strong> <?= $data['subject'] ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>From:</strong> <?= $data['from']['name'] ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Date:</strong> <?= date( "Y-m-d h:i",$data['created'] ) ?>
		</td>
	</tr>
	<tr>
		<td>
			<?= $data['body'] ?>
		</td>
	</tr>
</table>