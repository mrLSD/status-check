<?php
// Check and set filters data
if( isset( $this->user['filter']) ){
	$f_all = ( $this->user['filter']['all'] ) ? "checked" : "";
	$f_online = ( $this->user['filter']['online'] ) ? "checked" : "";
	$f_status = ( $this->user['filter']['status'] ) ? "checked" : "";
} else {
	$f_all = "checked";
	$f_online = "checked";
	$f_status = "checked";
} ?>
<a data-bind="click: changeFilter" href="#">Notifications filter</a>
<div id="filter-data" style="display: none">
	<form>
		<input id="f_all" type="checkbox" <?=$f_all?> data-bind="event: {change: setFilterAll}, checked: filterAll" />
		<label for="f_all">All</label>
		<br/>

		<input id="f_online" type="checkbox" <?=$f_online?> data-bind="event: {change: setFilterOnline}, checked: filterOnline" />
		<label for="f_online">Online status</label>
		<br/>

		<input id="f_status" type="checkbox" <?=$f_status?> data-bind="event: {change: setFilterStatus}, checked: filterStatus" />
		<label for="f_status">Status message</label>
	</form>
</div>