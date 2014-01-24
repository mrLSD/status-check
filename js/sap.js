function UsersViewModel() {
    var self = this;
	//=======================================================
	// Users data
    self.userListData = ko.observable();
    self.totalUsers = ko.computed(function() {
		return ( self.userListData() == undefined ) ? 0 : self.userListData().users.length;
    });
	self.onlineTotalUsers = ko.computed(function() {
		return ( self.userListData() == undefined ) ? 0 : self.userListData().online;
    });

    // Users data Behaviours
    self.getUsers = function() {
        $.post('/status/getUsersList', self.userListData);
		setTimeout(self.getUsers, 5000);
    };
    // Show Users list
    self.getUsers();

	//=======================================================
	// User status message changes
	self.userStatus = ko.observable( $("#status-message").text() );
	self.userStatusVisible = ko.observable(true);
	self.statusChange = ko.observable( $("#status-message").text() == '' );

	// User status message Behaviors
	self.addStatus = function(){
		if( self.userStatusVisible() )
		{
				self.statusChange(false);
				self.userStatusVisible( !self.userStatusVisible() );
		} else {
			// Saving data
			$.post('/status/setUserStatusMessage', {message:self.userStatus()},function(data){
					if( data.response == 'ok' )
					{
						self.statusChange( self.userStatus() == '' );
						self.userStatusVisible( !self.userStatusVisible() );
					}
			}, 'json');
		}
	};

	//=======================================================
	// Status filtering
	var show_f_online =  $("#f_online").attr("checked") == 'checked';
	var show_f_status = $("#f_status").attr("checked") == 'checked';
	self.filter_online = ko.observable( show_f_online );
	self.filter_status = ko.observable( show_f_status );

	self.filterAll = ko.observable( $("#f_all").attr("checked") == 'checked' );
	self.filterOnline = ko.observable( show_f_online );
	self.filterStatus = ko.observable( show_f_status );

	// Filters behaviors
	function saveFilterSettings()
	{
		$.post('/status/setFilterSettings', {
			all: Number( $("#f_all").attr("checked") == 'checked' ),
			online: Number( $("#f_online").attr("checked") == 'checked' ),
			status: Number( $("#f_status").attr("checked") == 'checked' )
		});
	}
	self.changeFilter = function()
	{
		// Animation effect
		if( $("#filter-data").css('display') == "none" )
		{
			$("#filter-data").fadeIn(500);
		}
		else
			$("#filter-data").fadeOut(500);
	};
	self.setFilterAll = function()
	{
		var res = ( $("#f_all").attr("checked") == 'checked' );
		self.filter_online( res );
		self.filter_status( res );
		self.filterOnline( res );
		self.filterStatus( res );
		saveFilterSettings();
	};
	self.setFilterOnline = function()
	{
		var res = ( $("#f_online").attr("checked") == 'checked' );
		self.filter_online( res );
		self.filterAll( false );
		saveFilterSettings();
	};
	self.setFilterStatus = function()
	{
		var res = ( $("#f_status").attr("checked") == 'checked' );
		self.filter_status( res );
		self.filterAll( false );
		saveFilterSettings();
	};

	//=======================================================
	// Messages
	self.allUsersMessageCheck = ko.observable(true);
	self.userMessageRecipients = ko.observable(false);
	self.messageSendEnable = ko.observable(true);
	self.selectedUsersMessageCount = ko.observable(" (All users)");

	// Messages behavior
	self.allUsersChecked = function()
	{
		var checked = ( $("#all-users-message-check").attr("checked") == "checked" );
		$(".check-users_message").attr("checked", checked );
		self.messageSendEnable(checked);
		if( checked )
			self.selectedUsersMessageCount(" (All users)");
		else
			self.selectedUsersMessageCount(" (0 users)");
	};
	self.userMessageCheked = function()
	{
		var count_checked = $(".check-users_message:checked").length;
		var count_checkboxes = $(".check-users_message").length;
		self.allUsersMessageCheck(false);
		self.messageSendEnable(count_checked);
		if( count_checked == count_checkboxes )
		{
			self.selectedUsersMessageCount(" (All users)");
			self.allUsersMessageCheck(true);
		} else {
			self.selectedUsersMessageCount(" ("+count_checked+" users)");
		}
	};
	self.selectUserMessageRecipients = function()
	{
		self.userMessageRecipients( !self.userMessageRecipients() );
	}
}

ko.applyBindings(new UsersViewModel());