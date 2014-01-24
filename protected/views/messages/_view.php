<?php
/* @var $this MessagesController */
/* @var $data Messages */
?>
<div>
	<label for="user_<?= $index?>"><?= $data['name']?></label>
	<input type="checkbox" id="user_<?= $index?>" data-bind="event: {change:userMessageCheked}" class="check-users_message" checked name="users[]" value="<?= $data["_id"] ?>" />
</div>