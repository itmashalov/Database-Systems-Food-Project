define(['plugins/router', 'durandal/setting'], function (router, settings) {
    return {
        router: router,
        activate: function () {
			/*
			router.guardRoute = function(model, route) {
				return settings.loggedin || '#login';
			};*/
            return router.map(settings.available_roots()).buildNavigationModel()
              .mapUnknownRoutes('hello/index', 'not-found')
              .activate();
        }
    };
});