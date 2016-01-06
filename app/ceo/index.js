define(['jquery', 'knockout', 'jqplot'], function ($, ko) {
  // constructor
  	self.debug = ko.observable();
	self.user_list = ko.observableArray();
	
	self.stat_array = ko.observableArray();
	self.stat_array.push(2);
	self.stat_array.push(3);
	self.stat_array.push(7);
	self.stat_array.push(1);
	
  	$.ajax({
		type: "POST",
		url: "selector.php",
		cache: false,
		success: function(data)
		{	
			var tmp =  $.parseJSON(data);
			self.user_list(tmp.user);
	
		},
		error: function(data)
		{
		}
	});
  
  	self.sendForm = function()
	{
		var hell = $("#ceoform").serialize();
		$.ajax({
				type: "POST",
				url: "getCeoChart.php",
				cache: false,
				data: $("#ceoform").serialize(),
				success: function(data)
				{	
					//self.debug(data);
					var dsds = jQuery.parseJSON(data);
					self.stat_array(dsds.stat);
					var ddd = self.stat_array();
					$('#chart').empty();
					$.jqplot ('chart', [self.stat_array()]);
				},
				error: function(data)
				{
					ERROR_MESSAGE2("No Connection");
				}
		});
	}
  
  
  var ctor = function () {
    var self = this;

    // properties
    self.chartConfig = {};
  };


  ctor.prototype.compositionComplete = function (view, parent) {
   
    self.sendForm();
  };

  return ctor;
});
