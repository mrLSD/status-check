<div>
	<label for="user_<?= $index?>"><?= $data['name']?></label>
	<input type="checkbox" id="user_<?= $index?>" data-bind="event: {change:userMessageCheked}" class="check-users_message" checked />
</div>