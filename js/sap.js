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

}

ko.applyBindings(new UsersViewModel());