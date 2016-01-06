define(['jquery', 'knockout'], function ($, ko) {

	var self = this;
	self.FormType = "Modify"
	self.debug = ko.observable();
	self.selectData = Array();
	
	ERROR_MESSAGE2 = ko.observable("");
		
	self.cuntry_list = ko.observableArray();
		self.immingrants_cuntry_list = ko.observableArray();
		self.immingrants_cuntry_list_selected =ko.observable();
		self.contract_cuntry_list_selected =ko.observable();
		self.toffer_cuntry_list=ko.observableArray();
		self.toffer_cuntry_list_selected =ko.observable();
		self.market_country_list_selected=ko.observable();
	self.product_list = ko.observableArray();
			self.contract_product_list = ko.observableArray();
			self.market_product_list=ko.observableArray();
	self.transportcompany_list = ko.observableArray();
			self.contract_transportcompany_list = ko.observableArray();
			self.contract_transportcompany_list_selected =ko.observable();
			self.toffer_transportcompany_list = ko.observableArray();
			self.toffer_transportcompany_list_selected =ko.observable();
			//self.transportcompany_list_selected = ko.observable();
	self.type_list = ko.observableArray();
		self.toffer_storagetype_list = ko.observableArray();
	self.taste_list = ko.observableArray();
	self.user_list = ko.observableArray();
	
	self.immigrants_table = ko.observableArray();
	self.contract_table = ko.observableArray();
	self.toffer_table = ko.observableArray();
	self.market_table = ko.observableArray();
	
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
			self.immigrants_table(tmp.immigrants);
			self.contract_table(tmp.contract);
			self.toffer_table(tmp.transportofer);
			self.market_table(tmp.market);
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
	
	self.sendForm = function(form)
	{
		var hell = $("#getTheseForm").serialize();
		$.ajax({
				type: "POST",
				url: "ActionDelete.php",
				cache: false,
				data: $("#"+form).serialize(),
				success: function(data)
				{	
					// self.debug(data);
					var message = jQuery.parseJSON( data );
					ERROR_MESSAGE2(message.error);
					if (message.error == "")
						alert("Operation was successful!");
				},
				error: function(data)
				{
					ERROR_MESSAGE2("No Connection");
				}
		});
	}
	
	
	//immigrants
	self.update_immigrants_country = function(obj, event)
	{
			var value = $(obj).val();
	}
	
	


		//Contract**************************************************************************************
	self.self.toffer_cuntry_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.toffer_table().length; i++)
		{
			var dsf = self.toffer_table()[i];
			if ((newValue == self.toffer_table()[i].country_name)&&(toffer_transportcompany_list_selected() == self.toffer_table()[i].transport_company_name))
			{
				$tmp = getItemByName(self.type_list, self.toffer_table()[i].typename);
				if (self.toffer_storagetype_list.indexOf($tmp) == -1)
					self.toffer_storagetype_list.push($tmp );
			}
		}
	});
	
	self.toffer_transportcompany_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.toffer_table().length; i++)
		{
			if (newValue == self.toffer_table()[i].transport_company_name)
			{
				$tmp = getItemByName(self.cuntry_list, self.toffer_table()[i].country_name);
				if (self.toffer_cuntry_list.indexOf($tmp) == -1)
					self.toffer_cuntry_list.push($tmp );
			}
		}
	});
	
	//Contract**************************************************************************************
	self.contract_transportcompany_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.contract_table().length; i++)
		{
			var dsf = self.contract_table()[i];
			if ((newValue == self.contract_table()[i].transport_company_name)&&(contract_cuntry_list_selected() == self.contract_table()[i].country_name))
			{
				$tmp = getItemByName(self.product_list, self.contract_table()[i].name);
				if (self.contract_product_list.indexOf($tmp) == -1)
					self.contract_product_list.push($tmp );
			}
		}
	});
	
	self.contract_cuntry_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.contract_table().length; i++)
		{
			if (newValue == self.contract_table()[i].country_name)
			{
				$tmp = getItemByName(self.transportcompany_list, self.contract_table()[i].transport_company_name);
				if (self.contract_transportcompany_list.indexOf($tmp) == -1)
					self.contract_transportcompany_list.push($tmp );
			}
		}
	});
	
	//Immigrants**************************************************************************************
	self.immingrants_cuntry_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.immigrants_table().length; i++)
		{
			if (newValue == self.immigrants_table()[i].from_country)
				self.immingrants_cuntry_list.push( getItemByName(self.cuntry_list, self.immigrants_table()[i].to_country));
		}
	});
	
	//Market**************************************************************************************
	self.market_country_list_selected.subscribe(function(newValue) {		
		for(var i = 0; i < self.market_table().length; i++)
		{
			if (newValue == self.market_table()[i].country_name)
				self.market_product_list.push( getItemByName(self.product_list, self.market_table()[i].name));
		}
	});
	

	
	self.getItemByName = function(item, search)
	{
		for (var j = 0; j < item().length; j++)
		{
			if (item()[j].name == search)
				return item()[j];
		}
	}


	self.sections =ko.observableArray([
		{sectionname: "Country", relationname: "country---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "country-name-country", callback:function(){}, options: self.cuntry_list  },
		]},
		{sectionname: "Product", relationname: "product---relation", fields: [ 
			{ title: "Product", type: "choicesingle", name: "product-name-product", callback:function(){}, options: self.product_list  },
		]},
		{sectionname: "Transport Company", relationname: "transportoffer---relation", fields: [ 
			{ title: "Company", type: "choicesingle", name: "transportcompany-name-transportcompany", callback:function(){}, options: self.transportcompany_list  },
		]},
		{sectionname: "Taste", relationname: "flavour---relation", fields: [ 
			{ title: "Taste", type: "choicesingle", name: "flavour-flavour-flavour", callback:function(){}, options: self.taste_list  }
		]},
		{sectionname: "Storage Type", relationname: "storagetype---relation", fields: [ 
			{ title: "Taste", type: "choicesingle", name: "storagetype-typename-storagetype ", callback:function(){}, options: self.type_list  },
		]},	
		{sectionname: "User", relationname: "user---relation", fields: [ 
			{ title: "User", type: "choicesingle", name: "user-name-user", callback:function(){}, options: self.user_list  },
		]},	
		{sectionname: "Immigrants", relationname: "immigrants---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "immigrants-name-country", callback: self.immingrants_cuntry_list_selected, options: self.cuntry_list  },
			{ title: "Immigrants", type: "choicesingle", name: "immigrants-name-immigrants_country", callback: function(){}, options: self.immingrants_cuntry_list  },
		]},
		{sectionname: "Market", relationname: "market---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "market-country-market", callback: self.market_country_list_selected, options: self.cuntry_list  },
			{ title: "Product", type: "choicesingle", name: "market-product-market", callback: function(){}, options: self.market_product_list  },
		]},
		{sectionname: "Contract", relationname: "contract---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "contract-name-country", callback: self.contract_cuntry_list_selected, options: self.cuntry_list  },
			{ title: "Transport Company", type: "choicesingle", name: "contract-name-transportcompany", callback: contract_transportcompany_list_selected, options: self.contract_transportcompany_list  },
			{ title: "Product", type: "choicesingle", name: "contract-name-product", callback:function(){}, options: self.contract_product_list  },
		]},
		
		{sectionname: "Transport Offer", relationname: "transportoffer---relation", fields: [ 
			{ title: "Transport Company", type: "choicesingle", name: "toffer-name-transportcompany", callback: toffer_transportcompany_list_selected, options: self.transportcompany_list  },
			{ title: "Country", type: "choicesingle", name: "toffer-name-country", callback: self.toffer_cuntry_list_selected, options: self.toffer_cuntry_list },
			{ title: "Storage Type", type: "choicesingle", name: "toffer-name-storagetype", callback:function(){}, options: self.toffer_storagetype_list  },
		]},

		
	]);
	

	return {
	sections: self.sections,
	activate: function () {
		sendForm: self.sendForm;
		}
	}

		
});

