function UsersViewModel() {
    // Data
    var self = this;
    self.userListData = ko.observable();

    // Behaviours
    self.getUsers = function() {
        $.post('/status/getUsersList', self.userListData);
		setTimeout(self.getUsers, 1000);
    };

    // Show Users list
    self.getUsers();
};

ko.applyBindings(new UsersViewModel());