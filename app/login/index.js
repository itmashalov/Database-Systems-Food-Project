define(['durandal/app', 'durandal/system', 'durandal/setting', 'knockout', 'plugins/router'], function (app, system,setting, ko, router) {
	var self = this;
	self.szar = "";
	
	
	

	
	
	self.sendLogin = function()
	{
		$.ajax({
				type: "POST",
				url: "login.php",
				cache: false,
				data: $("#loginform").serialize(),
				success: function(data)
				{	
					data = jQuery.parseJSON(data);
					router.reset();
					setting.available_roots(data.path);
					setting.available_roots.push({ route: ['', 'login'], moduleId: 'login/index', title: 'Logout',  nav: 1 });
					router.map(setting.available_roots()).buildNavigationModel().mapUnknownRoutes('hello/index', 'not-found');
					
					if (data.status == "success")
					{
						router.navigate(router.routes[0].hash);
						setting.loggedin = true;
					}
				},
				error: function(data)
				{
					var ff = data;
				}
		});
	}
	
    return {	
		szar: self.szar,
		activate: function () {
			router.reset();			
			setting.available_roots([]);
			setting.available_roots.push({ route: ['', 'login'], moduleId: 'login/index', title: 'Login',  nav: 1 });
			setting.available_roots.push({ route: "offer-view", moduleId: 'buyer/index', title: 'Request an Offer',  nav: 1 });
			router.map(setting.available_roots()).buildNavigationModel().mapUnknownRoutes('hello/index', 'not-found');
			setting.loggedin = false;
		},
    };
});