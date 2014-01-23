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

    // Behaviours
    self.getUsers = function() {
        $.post('/status/getUsersList', self.userListData);
		setTimeout(self.getUsers, 5000);
    };
    // Show Users list
    self.getUsers();

	//=======================================================
	// User status message changes
	self.statusEdit = ko.observable(false);
	self.addStatusText = ko.observable('Change status');
	self.userStatus = ko.observable( $("#status-message").text() );
	self.userStatusVisible = ko.observable(true);

	// Behaviors
	self.addStatus = function(){
		if( !self.statusEdit() )
		{
				self.statusEdit( !self.statusEdit() );
				self.userStatusVisible( !self.userStatusVisible() );
			self.addStatusText("Save status")
		} else {
			// Saving data
			$.post('/status/setUserStatusMessage', {message:self.userStatus()},function(data){
					if( data.response == 'ok' )
					{
						self.statusEdit( !self.statusEdit() );
						self.userStatusVisible( !self.userStatusVisible() );
						self.addStatusText("Change status");
					}
			}, 'json');
		}
	};

};

ko.applyBindings(new UsersViewModel());