define(['jquery', 'knockout'], function ($, ko) {

	var self = this;
	
	self.FormType = "Modify"
	
	self.debug = ko.observable();
	self.selectData = Array();
	
	self.cuntry_list = ko.observableArray();
	self.type_list = ko.observableArray();
	self.offers = ko.observableArray();

	self.choice1 = ko.observable();
	self.choice2 = ko.observable();

	self.offerr = ko.observable(0);

	
	
	ERROR_MESSAGE5 = ko.observable("");

	self.choice1.subscribe(function(newValue) {
		if ((choice1())&& (choice2()))
			self.calculateit(choice1(),choice2());

	});
	self.choice2.subscribe(function(newValue) {
		if ((choice1())&& (choice2()))
			self.calculateit(choice1(),choice2());
	});


	self.calculateit = function(val1,val2)
	{
		for (var i= 1; i<self.offers().length;i++)
		{
			if ((self.offers()[i].country == val1)&&(self.offers()[i].storage_type == val2))
			{
				self.offerr(self.offers()[i].myoffer);
			}
		}
	}

	$.ajax({
		type: "POST",
		url: "BuyerOffer.php",
		cache: false,
		success: function(data)
		{	
			var tmp =  $.parseJSON(data);
			self.cuntry_list(tmp.country);
			self.type_list(tmp.storagetype);
			self.offers(tmp.soffer);
		},
		error: function(data)
		{
			var ff = data;
		}
	});
	

	


	self.sections =ko.observableArray([
		{sectionname: "Offer", relationname: "country---relation", fields: [ 
			{ title: "Country", type: "choicesingle", name: "country-name-country", callback:self.choice1, options: self.cuntry_list  },
			{ title: "Taste", type: "choicesingle", name: "storagetype-typename-storagetype ", callback:self.choice2, options: self.type_list  }
		]}		
	]);
	

	return {
	sections: self.sections,
	activate: function () {
		
		}
	}

		
});

