<?php



		process($_POST);
		
		function process($form_data)
		{
			include("Helper.php");
			//pretty_print_array($form_data);
			
			$a = session_id();
			include("connect.php");
			$conn = init($_SESSION["permission_type"]);
			
			$result = Array();
			
			if (isset ($form_data["country---relation"]))
				$result = createCountry($form_data, $conn);
			else if (isset ($form_data["product---relation"]))
				$result = createProduct($form_data, $conn);
			else if (isset ($form_data["transportcompany---relation"]))
				$result = createTransportCountry($form_data, $conn);
			else if (isset ($form_data["flavour---relation"]))
				$result = createTaste($form_data, $conn);
			else if (isset ($form_data["storagetype---relation"]))
				$result = createStoragetype($form_data, $conn);
			else if (isset ($form_data["immigrants---relation"]))
				$result = createImigrants($form_data, $conn);
			else if (isset ($form_data["market---relation"]))
				$result = createMarket($form_data, $conn);
			else if (isset ($form_data["contract---relation"]))
				$result = createContract($form_data, $conn);
			else if (isset ($form_data["transportoffer---relation"]))
				$result = createTransportOffer($form_data, $conn);
			else if (isset ($form_data["user---relation"]))
				$result = createUser($form_data, $conn);
				
			if ($result["error"] == "")
				$returnedData = $conn->query($result["data"]);
			
			if ($conn->error) {
				$result["error"] = $conn->error;			
			}
			$result["data"] = "";
			echo (json_encode($result));
		}
		
		
		function createUser($form_data, $conn)
		{
				try{
				$name = htmlspecialchars($form_data["user-name-value1"]);
				$pass = htmlspecialchars($form_data["user-password-value1"]);
				$type = htmlspecialchars($form_data["user-type-value1"]);
				}
				catch(Exception $e){
					return Array ("error" => "More data is required to create a User", "data" => "");
				}
				if(($name=="")||($pass=="")||($type==""))
					return Array ("error" => "More data is required to create an User", "data" => "");
				
				$sql = "INSERT INTO `my_food_company`.`user` (`id`, `name`, `password`, `permission_type`, `active`) 
				VALUES (NULL, '".$name."', '".sha1($pass)."', '".$type."', '1');";
				return Array ("error" => "", "data" => $sql);
		}
		
		function createImigrants($form_data, $conn)
		{
				try{
				$from = htmlspecialchars($form_data["immigrants-fromcountry-value1"]);
				$to = htmlspecialchars($form_data["immigrants-tocountry-value1"]);
				$percentage = htmlspecialchars($form_data["immigrants-percentage-value1"]);
				}
				catch(Exception $e){
					return Array ("error" => "More data is required to create an immigrants", "data" => "");
				}
				if(($to=="")||($from=="")||($percentage==""))
					return Array ("error" => "More data is required to create an immigrants", "data" => "");
				
				$sql = "INSERT INTO `immigrants` (`id`, `from_country`, `to_country`, `percentage`) 
				VALUES (NULL, '".$from."', '".$to."', '".$percentage."');";
				return Array ("error" => "", "data" => $sql);
		}
		
		function createMarket($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["market-countryname-value1"]);
				$prod = htmlspecialchars($form_data["market-product-value1"]);
				$volume = htmlspecialchars($form_data["market-volume-value1"]);
				$potencial = htmlspecialchars($form_data["market-potencial-value1"]);
				$min = htmlspecialchars($form_data["market-minimumprice-value1"]);
			}
			catch(Exception $e){
					return Array ("error" => "More data is required to create an market", "data" => "");
			}
			if(($name=="")||($prod=="")||($volume=="")||($potencial=="")||($min==""))
					return Array ("error" => "More data is required to create an market", "data" => "");
			
			$sql = "INSERT INTO `market` (`id`, `country_name`, `product_id`, `volume`, `potential`, `minimum_price`) 
			VALUES (NULL, '".$name."', '".$prod."', '".$volume."', '".$potencial."', '".$min."');";
			return Array ("error" => "", "data" => $sql);
		}
		
		
		function createContract($form_data, $conn)
		{
			try{
				$country = htmlspecialchars($form_data["contract-countryname-value1"]);
				$tcompany = htmlspecialchars($form_data["contract-transportcompany-value1"]);
				$product = htmlspecialchars($form_data["contract-product-value1"]);
				$user = htmlspecialchars($form_data["contract-user-value1"]);
				$start = htmlspecialchars($form_data["contract-startdate-value1"]);
				$end = htmlspecialchars($form_data["contract-expirydate-value1"]);
			}
			catch(Exception $e){
					return Array ("error" => "More data is required to create an Contract", "data" => "");
			}
			if(($country=="")||($tcompany=="")||($product=="")||($user=="")||($start=="")||($end==""))
				return Array ("error" => "More data is required to create an Contract", "data" => "");
			
			$sql = "INSERT INTO `contract` (`id`, `country_name`, `transport_company_name`, `product_id`, `user_id`, `start_date`, `expiry_date`) 
			VALUES (NULL, '".$country."', '".$tcompany."', '".$product."', '".$user."', '".$start."', '".$end."');";
			return Array ("error" => "", "data" => $sql);
		}
		
		
		function createTransportCountry($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["transportcompany-name-value1"]);
				}
			catch(Exception $e){
					return Array ("error" => "More data is required to create a Transport Company", "data" => "");
			}	
			if(($name==""))
				return Array ("error" => "More data is required to create an Transport Company", "data" => "");
			
			$sql = "INSERT INTO `transportcompany` (`name`, `active`) 
			VALUES ('".$name."', '0');";
			return Array ("error" => "", "data" => $sql);
				
		}
		
		function createStoragetype($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["storagetype-typename-value1"]);
				}
			catch(Exception $e){
					return Array ("error" => "More data is required to create a storage type", "data" => "");
			}	
			if(($name==""))
				return Array ("error" => "More data is required to create an sorage type", "data" => "");
			
				$sql = "INSERT INTO `storagetype` (`id`, `typename`) 
				VALUES (NULL, '".$name ."');";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function createTaste($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["flavour-flavour-flavour-value1"]);
				}
			catch(Exception $e){
				return Array ("error" => "More data is required to create a Taste", "data" => "");
			}	
			if(($name==""))
				return Array ("error" => "More data is required to create an sorage type", "data" => "");
			
				$sql = "INSERT INTO `flavour` (`flavour`, `active`) 
				VALUES ('".$name."', '0');";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function createProduct($form_data, $conn)
		{
			try{
				$type = htmlspecialchars($form_data["product-storagetype-value1"]);
				$flavour = htmlspecialchars($form_data["product-flavour-value1"]);
				$name = htmlspecialchars($form_data["product-name-value1"]);
				$cost = htmlspecialchars($form_data["product-cost-value1"]);
				$weight = htmlspecialchars($form_data["product-wight-value1"]);
				$hfactor = htmlspecialchars($form_data["product-helthfactor-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to create a Product", "data" => "");
			}
			if(($type=="")||($flavour=="")||($name=="")||($cost=="")||($weight=="")||($hfactor==""))
				return Array ("error" => "More data is required to create an Product", "data" => "");
						
				$sql = "INSERT INTO `product` (`id`, `flavour_id`, `storage_type_id`, `name`, `cost`, `weight`, `instock`, `health_factor`, `active`) 
				VALUES (NULL, '".$flavour."', '".$type."', '".$name."', '".$cost."', '".$weight."', '1', '".$hfactor."', '1');";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function createTransportOffer($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["transportoffer-transportcompany-value1"]);
				$countryname = htmlspecialchars($form_data["transportoffer-countryname-value1"]);
				$product = htmlspecialchars($form_data["transportoffer-product-value1"]);
				$price = htmlspecialchars($form_data["transportoffer-price-value1"]);
				}
			catch(Exception $e){
				return Array ("error" => "More data is required to create a Transport Offer", "data" => "");
			}
			if(($name=="")||($countryname=="")||($product=="")||($price==""))
				return Array ("error" => "More data is required to create a Transport Offer", "data" => "");
			
				$sql = "INSERT INTO `transportoffer` (`id`, `storage_type_id`, `country_name`, `transport_company_name`, `price_per_kg`) VALUES (NULL, '".$product."', '".$countryname."', '".$name."',  '".$price."');";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function createCountry($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["country-name-value1"]);
				$population = htmlspecialchars($form_data["country-population-value1"]);
				$hfactor = htmlspecialchars($form_data["country-healthfactor-value1"]);				
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to create a Country", "data" => "");
			}
			if(($name=="")||($population=="")||($hfactor==""))
				return Array ("error" => "More data is required to create a Country", "data" => "");
						
				$sql = "INSERT INTO `country` (`name`, `population`, `health_factor`, `active`)
				VALUES ('".$name."', '".$population."', '".$hfactor."', '1');";
				
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function preprocess()
		{
		}
		
?>