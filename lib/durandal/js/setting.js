define(['knockout'], function(ko) {
  return {
    loggedin: false,
	
	available_roots: ko.observableArray([
                { route: ['', 'login'],                         moduleId: 'login/index',                title: 'Login',           nav: 1 },            
            ])
	
	
	
	
  }
});