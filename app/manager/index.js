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
	
	ERROR_MESSAGE3 = ko.observable("");
		
		
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
	/*
	self.kocloneArray = function(arr)
	{
		arr.forEach(function(entry) {
    console.log(entry);
});
	}*/
	
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
					ERROR_MESSAGE3(message.error);
				},
				error: function(data)
				{
					ERROR_MESSAGE3("No Connection");
				}
		});
	}
	
	


	self.sections =ko.observableArray([
		{sectionname: "Country", relationname: "country---relation", fields: [ 
			{ title: "Country", type: "choice", name: "country-name-country", callback:function(){}, options: self.cuntry_list  },
			{ title: "Population", type: "number", name: "country-population-country", callback:function(){}, options: ko.observableArray()  },
			{ title: "Immigrants", type: "choice", name: "country-name-immigrants_country", callback:function(){}, options: self.cuntry_list  },
			{ title: "Immigrants Percentage", type: "number", name: "immigrants-percentage-immigrants", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "Contracts", relationname: "contract---relation", fields: [ 
			{ title: "Start Date", type: "date", name: "contract-start_date-contract", callback:function(){}, options: ko.observableArray()  },
			{ title: "End Date", type: "date", name: "contract-expiry_date-contract", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "Market", relationname: "market---relation", fields: [ 
			{ title: "Market Volume", type: "number", name: "market-volume-market", callback:function(){}, options: ko.observableArray()  },
			{ title: "Market Potencial", type: "number", name: "market-potential-market", callback:function(){}, options: ko.observableArray()  },
			{ title: "Lowest price", type: "number", name: "market-minimum_price-market", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "Transport", relationname: "transportoffer---relation", fields: [ 
			{ title: "Transport Company", type: "choice", name: "transportcompany-name-transportcompany", callback:function(){}, options: self.transportcompany_list  },
			{ title: "Transport Price", type: "number", name: "transportoffer-price_per_kg-transportoffer", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "Product", relationname: "product---relation", fields: [ 
			{ title: "Product", type: "choice", name: "product-name-product", callback:function(){}, options: self.product_list  },
			{ title: "Price", type: "number", name: "product-cost-product", callback:function(){}, options: ko.observableArray()  },
			{ title: "In Stock", type: "bool", name: "product-instock-product", callback:function(){}, options: ko.observableArray()  },
			{ title: "Type", type: "choice", name: "storagetype-typename-storagetype", callback:function(){}, options: self.type_list  },
			{ title: "Taste", type: "choice", name: "flavour-flavour-flavour", callback:function(){}, options: self.taste_list  },
			{ title: "Helth Factor", type: "number", name: "product-health_factor-product", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "History", relationname: "history---relation", fields: [ 
			{ title: "Sold Cost", type: "number", name: "salesrecord-cost-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Sold Price", type: "number", name: "salesrecord-sale_price-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Transport Price", type: "number", name: "salesrecord-transport_price-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Quantity", type: "number", name: "salesrecord-quantity-salesrecord", callback:function(){}, options: ko.observableArray()  },
			{ title: "Date", type: "date", name: "salesrecord-date-salesrecord", callback:function(){}, options: ko.observableArray()  }
		]}	

		
	]);
	

	return {
	sections: self.sections,
	activate: function () {
		
		}
	}

		
});

