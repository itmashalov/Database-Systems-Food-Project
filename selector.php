<?php
	$a = session_id();
	include("connect.php");
	$conn = init($_SESSION["permission_type"]);

	
	
	$user_sql = "Select name as id, name, active from user";
	$product_sql = "Select id, name, active from product";
	$country_sql = "Select name as id, name, active from country";
	$flavour_sql = "Select id, flavour as name, active from flavour";
	$storagetype_sql = "Select id, typename as name from storagetype";
	$transportcompany_sql = "Select name as id, name, active from transportcompany";
	
	
	$contract_sql = "Select contract.id, country_name, transport_company_name, product.name, contract.active from contract inner join product on contract.product_id=product.id";	
	$immigrants_sql = "Select immigrants.id, from_country, to_country from immigrants";
	$market_sql = "Select market.id, country_name, product.name from market inner join product on market.product_id=product.id";
	$salesrecord_sql = "Select salesrecord.id, country_name, product.name from market inner join product on market.product_id=product.id";
	
	$transportofer_sql = "Select transportoffer.id, transport_company_name, country_name, storagetype.typename from transportoffer inner join storagetype on transportoffer.storage_type_id=storagetype.id";
	
	
	

	$rows = array();
	$result = $conn->query($product_sql);
	$rows["product"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["product"][] = $r;
	}

	if (($_SESSION["permission_type"] == "admin")||($_SESSION["permission_type"] == "ceo"))
	{
		$result = $conn->query($user_sql);
		$rows["user"] = array();
		while($r = mysqli_fetch_assoc($result)) {
			$rows["user"][] = $r;
		}
	}
	
	$result = $conn->query($country_sql);
	$rows["country"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["country"][] = $r;
	}
	$result = $conn->query($storagetype_sql);
	$rows["storagetype"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["storagetype"][] = $r;
	}
	$result = $conn->query($flavour_sql);
	$rows["flavour"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["flavour"][] = $r;
	}
	$result = $conn->query($transportcompany_sql);
	$rows["transportcompany"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["transportcompany"][] = $r;
	}
	$result = $conn->query($contract_sql);
	$rows["contract"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["contract"][] = $r;
	}
	$result = $conn->query($immigrants_sql);
	$rows["immigrants"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["immigrants"][] = $r;
	}
	
	$result = $conn->query($market_sql);
	$rows["market"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["market"][] = $r;
	}/*
	$result = $conn->query($salesrecord_sql);
	$rows["salesrecord"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["salesrecord"][] = $r;
	}*/
	$result = $conn->query($transportofer_sql);
	$rows["transportofer"] = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows["transportofer"][] = $r;
	}
	
	echo json_encode($rows);
	
?>