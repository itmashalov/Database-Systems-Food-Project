<?php



		process($_POST);
		
		function process($form_data)
		{
			include("Helper.php");
			// pretty_print_array($form_data);
			
			$a = session_id();
			include("connect.php");
			$conn = init($_SESSION["permission_type"]);
			
			$result = Array();
			
			if (isset ($form_data["transportoffer---relation"]))
				$result = deleteTransportOffer($form_data, $conn);
			else if (isset ($form_data["user---relation"]))
				$result = deleteUser($form_data, $conn);
			else if (isset ($form_data["contract---relation"]))
				$result = deleteContract($form_data, $conn);
			else if (isset ($form_data["market---relation"]))
				$result = deleteMarket($form_data, $conn);
			else if (isset ($form_data["transportoffer---relation"]))
				$result = deleteTransportOffer($form_data, $conn);
			else if (isset ($form_data["immigrants---relation"]))
				$result = deleteImigrants($form_data, $conn);
			else if (isset ($form_data["product---relation"]))
				$result = deleteProduct($form_data, $conn);
			else if (isset ($form_data["transportcompany---relation"]))
				$result = deleteTransportCompany($form_data, $conn);	
			else if (isset ($form_data["flavour---relation"]))				
				$result = deleteTaste($form_data, $conn);		
			else if (isset ($form_data["storagetype---relation"]))
				$result = deleteStoragetype($form_data, $conn);
			else if (isset ($form_data["country---relation"]))
				$result = deleteCountry($form_data, $conn);
			
			
			if ($result["error"] == "")
				$returnedData = $conn->query($result["data"]);
			
			if ($conn->connect_errno) {
				$result["error"] = $conn->connect_error;				
			}
			$result["data"] = "";
			echo (json_encode($result));
		}
		
		function deleteUser($form_data, $conn)
		{
			try{
			$name = htmlspecialchars($form_data["user-name-user-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a User", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete a User", "data" => "");
				
			$sql = "UPDATE `user` SET `active` = '0' WHERE `user`.`name` = '".$name."';";
			return Array ("error" => "", "data" => $sql);
		}
		
		function deleteContract($form_data, $conn)
		{
			try{
			$country = htmlspecialchars($form_data["contract-name-country-value1"]);
			$company = htmlspecialchars($form_data["contract-name-transportcompany-value1"]);
			$product = htmlspecialchars($form_data["contract-name-product-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Contract", "data" => "");
			}
			if(($country=="")||($company=="")||($product==""))
				return Array ("error" => "More data is required to delete a Contract", "data" => "");
				

			$sql = "UPDATE `contract` SET `active` = '0' WHERE `contract`.`country_name` = '".$country."' AND `contract`.`transport_company_name` = '".$company."' AND `contract`.`product_id` = '".$product."';";
			return Array ("error" => "", "data" => $sql);
		}
		
		function deleteMarket($form_data, $conn)
		{

			try{
			$country = htmlspecialchars($form_data["market-country-market-value1"]);
			$product = htmlspecialchars($form_data["market-product-market-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Market", "data" => "");
			}
			if(($country=="")||($product==""))
				return Array ("error" => "More data is required to delete a Market", "data" => "");

			$sql = "DELETE FROM `market`
			WHERE `market`.`country_name` = '".$country."' AND `market`.`product_id` = '".$product."'";
			
			return Array ("error" => "", "data" => $sql);
		}
				
		function deleteTransportOffer($form_data, $conn)
		{
			
			
			try{
			$company = htmlspecialchars($form_data["toffer-name-transportcompany-value1"]);
			$country = htmlspecialchars($form_data["toffer-name-country-value1"]);
			$storagetype = htmlspecialchars($form_data["toffer-name-storagetype-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Transport Offer", "data" => "");
			}
			if(($company=="")||($country=="")||($storagetype==""))
				return Array ("error" => "More data is required to delete a Transport Offer", "data" => "");
				
			
			$sql = "DELETE FROM `transportoffer`
			WHERE `transportoffer`.`country_name` = '".$country."' AND `transportoffer`.`transport_company_name` = '".$company."'
			AND `transportoffer`.`storage_type_id` = '".$storagetype."'";
			
			return Array ("error" => "", "data" => $sql);
		}
		
		function deleteImigrants($form_data, $conn)
		{
		
			try{
			$fromcountry = htmlspecialchars($form_data["immigrants-name-country-value1"]);
			$tocountry = htmlspecialchars($form_data["immigrants-name-immigrants_country-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Immigrants", "data" => "");
			}
			if(($fromcountry=="")||($tocountry==""))
				return Array ("error" => "More data is required to delete Immigrants", "data" => "");
				

			$sql = "DELETE FROM `immigrants`
			WHERE `immigrants`.`from_country` = '".$fromcountry."' AND `immigrants`.`to_country` = '".$tocountry."'";
			return Array ("error" => "", "data" => $sql);
		}
		
		function deleteCountry($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["country-name-country-value1"]);			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Country", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete a Country", "data" => "");
				
				
				
			$sql = "UPDATE `country` SET `active` = '0' WHERE `country`.`name` = '".$name."';";
			return Array ("error" => "", "data" => $sql);
			
		}
		
		function deleteProduct($form_data, $conn)
		{
			try{
			$name = htmlspecialchars($form_data["product-name-product-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Product", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete Product", "data" => "");
				
				
				
				$sql = "UPDATE `product` SET `active` = '0' WHERE `product`.`id` = '".$name."';";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		
		function deleteTransportCompany($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["transportcompany-name-transportcompany-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Company", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete Company", "data" => "");
				
			
			
				$sql = "UPDATE `transportcompany` SET `active` = '0' WHERE `transportcompany`.`name` = '".$name."';";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		
		
				
		function deleteTaste($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["transportcompany-name-transportcompany-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Taste", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete Taste", "data" => "");
				
				
				$name = htmlspecialchars($form_data["flavour-flavour-flavour-value1"]);
				$sql = "UPDATE `flavour` SET `active` = '0' WHERE `flavour`.`id` = '".$name."';";
				return Array ("error" => "", "data" => $sql);
		
		}
		
				
		function deleteStoragetype($form_data, $conn)
		{
			try{
				$name = htmlspecialchars($form_data["transportcompany-name-transportcompany-value1"]);
			}
			catch(Exception $e){
				return Array ("error" => "More data is required to delete a Storage Type", "data" => "");
			}
			if(($name==""))
				return Array ("error" => "More data is required to delete Storage Type", "data" => "");
				
				$name = htmlspecialchars($form_data["storagetype-typename-storagetype_-value1"]);
				$sql = "UPDATE `storagetype` SET `active` = '0' WHERE `storagetype`.`id` = '".$name."';";
				return Array ("error" => "", "data" => $sql);
			
		}
		
		function preprocess()
		{
		}
		
?>