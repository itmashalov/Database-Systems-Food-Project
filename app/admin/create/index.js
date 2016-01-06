define(['jquery', 'knockout'], function ($, ko) {

	var self = this;
	var selfiii = this;
	self.FormType = "Modify"
	
	self.debug = ko.observable();
	self.selectData = Array();
	
	self.cuntry_list = ko.observableArray();
	self.product_list = ko.observableArray();
	self.transportcompany_list = ko.observableArray();
	self.type_list = ko.observableArray();
	self.taste_list = ko.observableArray();
	self.user_list = ko.observableArray();
	
	self.usertypelist = ko.observableArray([{ name: "admin", id: "admin"},{ name: "ceo", id: "ceo"},{ name: "manager", id: "manager"},{ name: "accountant", id: "accountant"}]);
	
	var faszom = self.usertypelist();
	
	ERROR_MESSAGE = ko.observable("");
	
	/*
	self.userTypes = ko.observableArray();
	self.userTypes.push(name: "admin");
	self.userTypes.push("ceo");
	self.userTypes.push("manager");
	self.userTypes.push("accountant");*/

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
			self.user_list(tmp.user);
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
	self.greeting=function(dfsdf)
	{
		var rrr = 2;
	}
	self.sendForm2 = function(form)
	{
		$.ajax({
				type: "POST",
				url: "ActionCreate.php",
				cache: false,
				data: $("#"+form).serialize(),
				success: function(data)
				{	
					//self.debug(data);
					var message = jQuery.parseJSON( data );
					ERROR_MESSAGE(message.error);
					if (message.error == "")
						alert("Creation was successful!");
				},
				error: function(data)
				{
					ERROR_MESSAGE("No Connection");
				}
		});
	}
	


	self.sections =ko.observableArray([
		{sectionname: "Country", relationname: "country---relation", fields: [ 
			{ title: "Country", type: "text", name: "country-name", callback:function(){}, options: ko.observableArray()  },
			{ title: "Population", type: "text", name: "country-population", callback:function(){}, options: ko.observableArray()  },
			{ title: "Health Factor", type: "text", name: "country-healthfactor", callback:function(){}, options: ko.observableArray()  },
		]},
		{sectionname: "Product", relationname: "product---relation", fields: [ 
			{ title: "Flavour", type: "choicesingle", name: "product-flavour", callback: ko.observable(), options: self.taste_list   },
			{ title: "Storage Type", type: "choicesingle", name: "product-storagetype", callback: ko.observable(), options: self.type_list  },
			{ title: "Name", type: "text", name: "product-name", callback:function(){}, options: ko.observableArray()  },
			{ title: "Cost", type: "text", name: "product-cost", callback:function(){}, options: ko.observableArray()  },
			{ title: "weight", type: "text", name: "product-wight", callback:function(){}, options: ko.observableArray()  },
			{ title: "Health Factor", type: "text", name: "product-helthfactor", callback:function(){}, options: ko.observableArray()  },
		]},
		{sectionname: "Transport Company", relationname: "transportcompany---relation", fields: [ 
			{ title: "Company", type: "text", name: "transportcompany-name", callback:function(){}, options: ko.observableArray()  },
		]},
		{sectionname: "Taste", relationname: "flavour---relation", fields: [ 
			{ title: "Taste", type: "text", name: "flavour-flavour-flavour", callback:function(){}, options: ko.observableArray()  }
		]},
		{sectionname: "Storage Type", relationname: "storagetype---relation", fields: [ 
			{ title: "Taste", type: "text", name: "storagetype-typename", callback:function(){}, options: ko.observableArray()  },
		]},	
		{sectionname: "User", relationname: "user---relation", fields: [ 
			{ title: "Username ", type: "text", name: "user-name", callback:function(){}, options: ko.observableArray()  },
			{ title: "Password ", type: "text", name: "user-password", callback:function(){}, options: ko.observableArray()  },
			{ title: "type ", type: "choicesingle", name: "user-type", callback:function(){}, options: self.usertypelist  },
		]},
		{sectionname: "Immigrants", relationname: "immigrants---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "immigrants-fromcountry", callback: function(){}, options: self.cuntry_list  },
			{ title: "Immigrants", type: "choicesingle", name: "immigrants-tocountry", callback: function(){}, options: self.cuntry_list  },
			{ title: "Immigrants Percentage", type: "text", name: "immigrants-percentage", callback: function(){}, options: ko.observableArray()  },
		]},
		{sectionname: "Market", relationname: "market---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "market-countryname", callback: function(){}, options: self.cuntry_list  },
			{ title: "Product", type: "choicesingle", name: "market-product", callback: function(){}, options: self.product_list  },
			{ title: "Volume", type: "text", name: "market-volume", callback: function(){}, options: ko.observableArray()  },
			{ title: "Potencial", type: "text", name: "market-potencial", callback: function(){}, options: ko.observableArray()  },
			{ title: "Minimum price", type: "text", name: "market-minimumprice", callback: function(){}, options: ko.observableArray()  },
		]},
		
		{sectionname: "Contract", relationname: "contract---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "contract-countryname", callback: function(){}, options: self.cuntry_list  },
			{ title: "Transport Company", type: "choicesingle", name: "contract-transportcompany", callback: function(){}, options: self.transportcompany_list},			
			{ title: "Product", type: "choicesingle", name: "contract-product", callback: function(){}, options: self.product_list  },
			{ title: "User", type: "choicesingle", name: "contract-user", callback: function(){}, options: self.user_list  },
			{ title: "Start Date", type: "text", name: "contract-startdate", callback: function(){}, options: ko.observableArray()  },
			{ title: "Expiry Date", type: "text", name: "contract-expirydate", callback: function(){}, options: ko.observableArray()  },
		]},
		
		{sectionname: "Transport Offer", relationname: "transportoffer---relation", fields: [ 
			{ title: "Transport Company", type: "choicesingle", name: "transportoffer-transportcompany", callback: function(){}, options: self.transportcompany_list},	
			{ title: "Country", type: "choicesingle", name: "transportoffer-countryname", callback: function(){}, options: self.cuntry_list  },			
			{ title: "Product", type: "choicesingle", name: "transportoffer-product", callback: function(){}, options: self.product_list  },
			{ title: "Price", type: "text", name: "transportoffer-price", callback: function(){}, options: ko.observableArray()  },
		]},
		
	]);
	

	return {
	sections: selfiii.sections,
	
	activate: function () {
		sendForm= selfiii.sendForm2;
		}
	}

		
});

