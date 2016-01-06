define(['jquery', 'knockout'], function ($, ko) {

	var self = this;
	self.FormType = "Get"
	self.debug = ko.observable();
	self.selectData = Array();
	
	self.cuntry_list = ko.observableArray();
	self.product_list = ko.observableArray();
	self.transportcompany_list = ko.observableArray();
	self.type_list = ko.observableArray();
	self.taste_list = ko.observableArray();
	
		query_results = ko.observableArray();
	query_keys = ko.observableArray();
	
	ERROR_MESSAGE4 = ko.observable("");
		
		
	$.ajax({
		type: "POST",
		url: "selector.php",
		cache: false,
		success: function(data)
		{	
			var tmp =  $.parseJSON(data);
			self.cuntry_list(tmp.country);
			self.product_list(tmp.product);
			self.transportcompany_list(tmp.transportcompany);
			self.type_list(tmp.storagetype);
			self.taste_list(tmp.flavour);
		},
		error: function(data)
		{
			var ff = data;
		}
	});
	
	self.sendDataForEvaluation = function()
	{
		var hell = $("#getTheseForm").serialize();
		$.ajax({
				type: "POST",
				url: "ActionGet.php",
				cache: false,
				data: $("#getTheseForm").serialize(),
				success: function(data)
				{	
					//self.debug(data);
					//self.debug(data);
					var message = jQuery.parseJSON( data );
					query_results(message.data);
					

						query_keys.removeAll();
					
					if ((message.data.length > 0)&&(message.error.length == 0))
						query_keys(Object.keys(message.data[0]));
					var happy = query_keys();
					ERROR_MESSAGE4(message.error);
				},
				error: function(data)
				{
					ERROR_MESSAGE4("No Connection");
				}
		});
	}
	
	


	self.sections =ko.observableArray([
		{sectionname: "Selling_History", relationname: "contract---relation", fields: [ 
			{ title: "Country", type: "choice", name: "country-name-country", callback:function(){}, options: self.cuntry_list  },
			{ title: "Product", type: "choice", name: "product-name-product", callback:function(){}, options: self.product_list  },
			{ title: "Transport Company", type: "choice", name: "transportcompany-name-transportcompany", callback:function(){}, options: self.transportcompany_list  },
			{ title: "Cost", type: "number", name: "salesrecord-cost-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Sold Price", type: "number", name: "salesrecord-sale_price-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Transport Price", type: "number", name: "salesrecord-transport_price-salesrecord", callback:function(){}, options: ko.observableArray()  },
				{ title: "Date", type: "date", name: "salesrecord-date-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Quantity", type: "number", name: "salesrecord-quantity-salesrecord", callback:function(){}, options: ko.observableArray()  }
		
		]},	
	]);
	

	return {
	sections: self.sections,
	activate: function () {
		
		}
	}

		
});

