<?php



		process($_POST);
		
		function process($form_data)
		{

			
			$a = session_id();
			include("connect.php");
			$conn = init($_SESSION["permission_type"]);
			
			
			$query = "
				SELECT salesrecord.cost, salesrecord.sale_price, salesrecord.transport_price FROM `salesrecord` 
				inner join contract on contract.id=salesrecord.contract_id
				inner join user on contract.user_id=user.id
				Order by salesrecord.date
			";
			if ((isset($form_data['choosenuser']))&&($form_data['choosenuser'] != ""))
			{
				
			$query = "
				SELECT salesrecord.cost, salesrecord.sale_price, salesrecord.transport_price FROM `salesrecord` 
				inner join contract on contract.id=salesrecord.contract_id
				inner join user on contract.user_id=user.id
				Where user.name = '".$form_data['choosenuser']."'
				Order by salesrecord.date
				";
			}
			
			$result = $conn->query($query);
			
			$rows = $result->fetch_all(MYSQLI_ASSOC);
			
			$stat = array();
			
			$i = 0;
			$sum = 0;
			foreach($rows as $column) {
				$i += 1;
				if ($i == 100)
				{
					$stat[] = $sum / 100;
					$i = 0;
					$sum = 0;
				}
				$sum +=  floatval($column['sale_price']) /  (floatval($column['cost'])+floatval($column['transport_price'])  );
				
				//echo (   $hh . "<br>"   );
				
			}
			$stat[] = $sum / $i;
			
			echo (json_encode(Array("stat" => $stat)));
			

		}
		
		
		
?>