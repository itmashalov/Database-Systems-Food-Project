<?php
include("connect.php");
$db = init("buyer");


$rows = array();


$sql = "SELECT country, storage_type, round(min(price_per_kg)*1.1, 2) as myoffer FROM TransportOfferDetail GROUP BY country, storage_type;";
$result = $db->query($sql);
$sql2 = "SELECT DISTINCT country as name, country as id FROM TransportOfferDetail;";
$result2 = $db->query($sql2);
$sql3 = "SELECT DISTINCT storage_type as name, storage_type as id FROM TransportOfferDetail;";
$result3 = $db->query($sql3);


$rows["soffer"] = array();
$rows["country"] = array();
$rows["storagetype"] = array();
while ($r = mysqli_fetch_assoc($result)) {
	$rows["soffer"][] = $r;
}
while ($r = mysqli_fetch_assoc($result2)) {
	$rows["country"][] = $r;
}
while ($r = mysqli_fetch_assoc($result3)) {
	$rows["storagetype"][] = $r;
}

echo json_encode($rows);
?>