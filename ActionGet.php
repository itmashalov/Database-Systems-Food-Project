<?php

		process($_POST);
		function process($form_data)
		{
			
			$a = session_id();
			include("connect.php");
			$conn = init($_SESSION["permission_type"]);
			
			include("Helper.php");
					//pretty_print_array($form_data);
					

			$processed_form_data = preprocess($form_data);
	

			$builder = new GetQueryBuilder();

			$coolresult = $builder->generateSQL($processed_form_data);
			
			$returnedData = "";
			//print_r($coolresult);
			
			$returnedData = $conn->query($coolresult["data"][0]);
					
			

			
		
			//echo ($coolresult["data"][0]);
			$coolresult["data"] = "";
			
			if ($conn->error) {
				if ($coolresult["error"] == "")
					$coolresult["error"] = $conn->error;				
			}
			else
			{		
				$coolresult["error"] = "";
				$coolresult["data"] = $returnedData->fetch_all(MYSQLI_ASSOC);
			}
			
		    echo json_encode($coolresult);
			
			
			
		}
		
		function preprocess($form_data)
		{
			$processed_form_data = array();
			foreach ($form_data as $i => $value1) {
			
				$i_d = explode("-", $i);
				if ((($i_d[3] == "known")&&($value1 == 'true'))||(($i_d[3] == "wanted")&&($value1 == 'true'))||(($i_d[3] == "relation")&&($value1 == 'true')))
				{
					foreach ($form_data as $j => $value2) {
						$j_d = explode("-", $j);
						if (($j_d[0] == $i_d[0])&&($j_d[1] == $i_d[1])&&($j_d[2] == $i_d[2]))
							$processed_form_data += [$j => $value2];
					}
				}
			}
			//$processed_form_data = removeUglyDataformat("contract-start_date-contract-", $processed_form_data);
			//$processed_form_data = removeUglyDataformat("contract-expiry_date-contract-", $processed_form_data);
			//$processed_form_data = removeUglyDataformat("salesrecord-date-salesrecord-", $processed_form_data);
			return $processed_form_data;
		}
		
		function removeUglyDataformat($datename, $processed_form_data)
		{
			if (isset($processed_form_data[$datename. "value0-value3"]))
			{
				$d1 = $processed_form_data[$datename."value0-value1"] . "-" . $processed_form_data[$datename."value0-value2"]. "-" . $processed_form_data[$datename."value0-value3"];
				$d2 = $processed_form_data[$datename."value1-value1"] . "-" . $processed_form_data[$datename."value1-value2"]. "-" . $processed_form_data[$datename."value1-value3"];
				unset($processed_form_data[$datename."value0-value1"]);
				unset($processed_form_data[$datename."value0-value2"]);
				unset($processed_form_data[$datename."value0-value3"]);
				unset($processed_form_data[$datename."value1-value1"]);
				unset($processed_form_data[$datename."value1-value2"]);
				unset($processed_form_data[$datename."value1-value3"]);				
				$processed_form_data += [$datename."value0" => $d1];
				$processed_form_data += [$datename."value1" => $d2];
			}
			return $processed_form_data;
		}
		
		class GetQueryBuilder
		{
			private $dbConstruction = array(
				"country" => array(
					"Alias" => "immigrants_country",
					"Type" => "Entity",
					"ID" => "name",
					"Attributes" => "name,population,health_factor",
				),
				"product" => array(
					"Alias" => "",
					"Type" => "Entity",
					"Connecting" => "product,flavour,storagetype",
					"ForeignKeys" => "id,flavour_id,storage_type_id",
					"ID" => "id",
					"Attributes" => "name,cost,extra_cost,weight,instock,health_factor",
				),
				"flavour" => array(
					"Alias" => "",
					"Type" => "Entity",
					"ID" => "id",
					"Attributes" => "flavour",
				),
				"user" => array(
					"Alias" => "",
					"Type" => "Entity",
					"ID" => "id",
					"Attributes" => "name,permission_type",
				),
				"storagetype" => array(
					"Alias" => "",
					"Type" => "Entity",
					"ID" => "id",
					"Attributes" => "typename",
				),
				"transportcompany" => array(
					"Alias" => "",
					"Type" => "Entity",
					"ID" => "name",
					"Attributes" => "name",
				),
				"salesrecord" => array(
					"Alias" => "",
					"Type" => "Entity",
					"Connecting" => "salesrecord,contract",
					"ForeignKeys" => "id,contract_id",
					"ID" => "id",
					"Attributes" => "cost,sale_price,transport_price,quantity,date",
				),
				"contract" => array(
					"Alias" => "",
					"Type" => "Relation",
					"Connecting" => "country,transportcompany,user,product",
					"ForeignKeys" => "country_name,transport_company_name,user_id,product_id",
					"ID" => "id",
					"Attributes" => "start_date,expiry_date",
				),
				"transportoffer" => array(
					"Alias" => "",
					"Type" => "Relation",
					"Connecting" => "country,transportcompany,storagetype",
					"ForeignKeys" => "country_name,transport_company_name,storage_type_id",
					"ID" => "id",
					"Attributes" => "start_date,expiry_date",
				),
				"immigrants" => array(
					"Alias" => "",
					"Type" => "Relation",
					"Connecting" => "country,immigrants_country",
					"ForeignKeys" => "from_country,to_country",
					"ID" => "id",
					"Attributes" => "percentage",
				),
				"market" => array(
					"Alias" => "",
					"Type" => "Relation",
					"Connecting" => "country,product",
					"ForeignKeys" => "country_name,product_id",
					"ID" => "id",
					"Attributes" => "volume,potential,minimum_price",
				)			
			);
			
			

			
			
			
			
			
			
			
			
			
			function generateSQL ($data)
			{ 
				$known = array();
				$wanted = array();
				$relation = array();
				foreach ($data as $i => $value1) {
					
					$i_d = explode("-", $i);
					if (($i_d[3] == "known")&&($value1 == 'true'))
					{
						
						foreach ($data as $j => $value2) {
							$j_d = explode("-", $j);
							if (($j_d[0] == $i_d[0])&&($j_d[1] == $i_d[1])&&($j_d[2] == $i_d[2])&&($j_d[3] != "known"))
								$known += [$j => $value2];
						}
					}
					else if (($i_d[3] == "wanted")&&($value1 == 'true'))
						$wanted += [$i_d[0] ."-". $i_d[1] ."-" . $i_d[2] => ""];
					else if (($i_d[3] == "relation"))
						$relation[] = $i_d[0];
					
				}
				/*
				pretty_print_array($data);
				echo ("Relation <br>");
				pretty_print_array($relation);
				echo ("Known <br>");
				pretty_print_array($known);
				echo ("Wanted <br>");
				pretty_print_array($wanted);	*/
				
				$SELECTSTAR = "";
				$SELECTFROM = "";
				$origin = "";
				
				foreach ($known as $know => $nothing) {
					$know = explode("-", $know);
					//echo("<br> Origin:". $this->isEntity($know[0]) ."<br>");
					if (($SELECTFROM == "")&&($this->isEntity($know[2])))
					{
						$SELECTFROM = $know[2]." ";
						$origin = $know[2];
					}
				}
				foreach ($wanted as $want => $nothing) {
					$want = explode("-", $want);
					$SELECTSTAR .= $want[2] . "." . $want[1] .", ";
					if (($SELECTFROM == "")&&($this->isEntity($want[2])))
					{
						$SELECTFROM = $want[2]." ";
						$origin = $want[2];
					}
				}
				$SELECTSTAR = substr($SELECTSTAR, 0, -2) . " ";
				
				if (sizeof($wanted) == 0)
					return Array("error" => "You need to select what you want", "data" => "");
				
				$requied_tables = $this->mergeKnownAndWnated($known, $wanted);
				
				//echo($requied_tables);
				
				//$this->showresult($requied_tables,0);
				
				//echo("<br><br><br><br><br><br><br><br>");
				//echo("RESULTS <br>");
				$result = array();
				$result[] = array();
				$tmpresults = $this->joinThem($origin,$origin,"");
				$results[$origin . ",,"] = $tmpresults;

				//$this->showresult($results,0);

				//echo("<br>END-------------------------------<br>");
				
				$final = $this->FindWay($results, $requied_tables);
				$errorarray = Array();
				
				//$this->showresult($final,0);
				$this->cleanTree($final, $requied_tables, $relation, $errorarray) ;
				
				//$this->showresult($final,0);
				//echo($errorarray);
				//echo("<br>END-------------------------------<br>");
				
				if (!isset($final[$origin . ",,"]))
					return Array("error" => "You need to secify your querry", "data" => "");
					
				$INNERJOIN = $this->buildJoinQuery($final[$origin . ",,"]);
				$SQLQUERY = array();

				
				$WHERE = $this->buildConstraintQuery($known)	;
				

				
				$SELECTSTAR = str_replace ("country.name","country.name as 'Country Name'",$SELECTSTAR);
				$SELECTSTAR = str_replace ("immigrants_country.name as 'Country Name'"," immigrants_country.name as 'Immigrant Country Name'",$SELECTSTAR);
				$SELECTSTAR = str_replace ("product.name","product.name as 'Product Name'",$SELECTSTAR);
				
				if ((strpos($SELECTSTAR,'immigrants_country.name') !== false) && (strpos($INNERJOIN,'immigrants_country.name') == false))
				{
					if (strpos($SELECTSTAR,"country.name as 'Country Name'") !== false)
					{
					$INNERJOIN +="INNER JOIN immigrants ON immigrants.from_country = country.name INNER JOIN country AS immigrants_country ON immigrants_country.name = immigrants.to_country";
					}
					else
					{
						
					}
				}
				else

				

				
				if ($SELECTSTAR == "")
					$SELECTSTAR = "*";
				

				$w = "";
				if ($WHERE != "")
					$w = " WHERE " . $WHERE;
				$SQLQUERY[]="SELECT " . $SELECTSTAR . "FROM " . $SELECTFROM . " " . $INNERJOIN . $w;
				//echo ("SELECT " . $SELECTSTAR . "FROM " . $SELECTFROM . " " . $INNERJOIN . $w . "<br><br>");

				
				
				$resultt =  Array();
				$resultt[] = $errorarray;
				$resultt[] = $SQLQUERY;
				return Array("error" => $errorarray, "data" => $SQLQUERY);
				
			}
			
			
			//Required_map is just a boolean array indicating whether the required has been found in the subtree or not. should be as long as the required array
			//required: those attributes which are required
			//connectors: the tables which should connect the attributes (useful when we have more branch and the decision is unambiguous)
			

				
				
			function cleanTree(&$tree, $required, $connectors, &$errorarray)
			{
				foreach($required as $r)
				{
					foreach($tree as $name => $subtree)
					{
						$tname = explode(",", $name);
						if ($r != $tname[0])
						{
							$this->r_cleanTree($subtree,$tname[0] ,$r, $connectors, $errorarray, $required);
							$tree[$name] = $subtree;
						}
						
					}
				}
			}
			

			function r_cleanTree(&$tree,$selfname, $aim, $connectors, &$errorarray, $requiredall)
			{		
				$tmpstuff = Array();
				$errors = Array();
				foreach($tree as $name => $subtree)
				{	
					$tname = explode(",", $name);
					
					if ($tname[0] == $aim)
						$tmpstuff[] = $tname[0];
					else if (is_array($subtree))
					{
						$subrerrors = Array();
						if ($this->r_cleanTree($subtree,$tname[0], $aim, $connectors, $subrerrors, $requiredall))
						{
							$tmpstuff[] = $tname[0];
							$errors[] = $subrerrors;
						}
						$tree[$name] = $subtree;
					}
				}
			
		
				#if more then one subree contains the same property
				if (sizeof($tmpstuff) > 1)
				{
					$rightone = Array();
					#find if the user has choosen the root
					
					foreach($tmpstuff as $stuff)
					{			
						//echo ("********************" . $stuff . "**********************<br>");
						#if the table is in the choosen ones put it to the right array
						if (in_array($stuff, $connectors))
							$rightone[] = $stuff;
					}
					//echo("<br><br>");
					$i = -1;
					#if there is only one right then delete the other subtrees
					if (sizeof($rightone) == 1)
					{
						foreach($tree as $name => $subtree)
						{	
							$i++;
							$tname = explode(",", $name);
							if ($tname[0] != $rightone[0])
							{
								if ((in_array($tname[0], $requiredall))||(in_array($tname[0], $connectors)))
								{
								}
								else
								{
									unset($tree[$name]);
									unset($errors[$i]);
								}
								//$errors[$i] = Array();
							}
							
						}
						foreach($errors as $erro)
						{
							foreach($erro as $err)
								$errorarray[] = $err;
						}
						return true;
					}
					$errorarray = Array();
					$errorarray[] = "It is not clear how should " . $aim . " be connected to " . $selfname;
					$errorarray[sizeof($errorarray)-1] .= " You may choose only one of the following connections: ";
					foreach($tmpstuff as $error)
						$errorarray[sizeof($errorarray)-1] .= $error . ", ";
					
					
					return true;
				}
				
				foreach($errors as $erro)
				{
					foreach($erro as $err)
						$errorarray[] = $err;
				}
				
				if (sizeof($tmpstuff) > 0)
					return true;
				return false;
			}
			
			
			function inInTree($tree, $c)
			{	
				foreach($tree as $name => $subtree)
				{	
					$tname = explode(",", $name);
					if ($tname[0] == $c)
						return true;
				}
				return false;
			}
			
			function buildConstraintQuery($known)
			{		
				$knowngroups = array();
				foreach($known as $constrant => $value){
					$table = explode("-",$constrant);
					$found = false;
					foreach($knowngroups as $group => $npthing){
						if ($table[2] . "-" .$table[1] == $group)
							$found = true;;
					}
					if ($found == false)
						$knowngroups += [$table[2] . "-" .$table[1]  => array()];
				}
				
				foreach($known as $constrant => $value){
					$table = explode("-",$constrant);
					$knowngroups[$table[2] . "-" .$table[1]] += [$constrant => $value];
				}

				
				$props = "";				
				foreach($knowngroups as $group => $attributes){
					$method = "=";
					foreach($attributes as $attribute => $value){
						$attribute = explode("-",$attribute);
						if ((isset($attribute[3]))&&($attribute[3] == "method"))
						{
							if (($value == "After")||($value == "Grater"))
								$method = ">";
							if (($value == "Before")||($value == "Less"))
								$method = "<";
							if (($value == "Equal")||($value == "Then"))
								$method = "=";
							if (($value == "Between"))
								$method = "BETWEEN";
						}
					}
					
					
					//echo($attributes);
					foreach($attributes as $attribute => $value){
							
							$constr = explode("-", $attribute);
							$constr = $constr[2] . "." .  $constr[1];
							
							$base = explode("-", $attribute);
							$base = $base[0] ."-". $base[1] . "-" . $base[2];
							
						    if (is_array ($attributes[$base . "-" . "value1"]))
							{	
								$tmp = "(";
								for ($i = 0; $i< count($value); $i++){
										$tmp .= $constr . $method . "'" . $attributes[$base . "-" . "value1"][$i] . "' OR ";
								}
								$tmp = substr($tmp, 0, -3) . ") AND ";
								$props .= $tmp;
								
							}
							else if ($method == "BETWEEN")
							{
								$props .="(" . $constr . " " . $method . " '" . $attributes[$base . "-" . "value1"] . "' AND '" . $attributes[$base . "-" . "value2"] ."') AND ";
							}
							else
							{
								$props .= $constr . " " . $method . " '" . $attributes[$base . "-" . "value1"] . "' AND ";
							}
							break;
							
					}				
				}
					$props = substr($props, 0, -4);
					return $props;
			}
			
			function buildJoinQuery($path, &$szar = "")
			{
				$result = "";
				foreach ($path as $elem => $subpath) 
				{
					
					$joinmap = explode (",",$elem);
					//echo("*****"  .$szar . ">>>>>" .$joinmap[0] . "*****<br>");
					if (strpos($szar,$joinmap[0]) == false) {
						//echo 'true';
						$szar .= " ,". $joinmap[0];
						$result .= " INNER JOIN " . $joinmap[0] . " ON " . $joinmap[1] . " = " .  $joinmap[2] . " ";
					}
					
					$result .= $this->buildJoinQuery($subpath, $szar);
				}
				return $result;
			}
			
			
			function showresult($results, $depth)
			{
				foreach ($results as $name => $result) {
					for ($i = 0; $i<$depth; $i++)
						echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
					echo($name . "<br>");
					if (is_array($result))
					{
						$this->showresult($result, $depth+1);						
					}
				}
			}
			
			
			function mergeKnownAndWnated($known, $wanted)
			{
				$required= array();
				foreach($known as $name => $line){
					$has = false;
					$table = explode("-",$name);
					
					for ($i=0; $i < count($required); $i++)
					{
						if ($required[$i] == $table[2])
							$has = true;
					}
					if ($has == false)
						$required[] = $table[2];
				}
				foreach($wanted as $name => $line){
					$has = false;
					$table = explode("-",$name);
					
					for ($i=0; $i < count($required); $i++)
					{
						if ($required[$i] == $table[2])
							$has = true;
					}
					if ($has == false)
						$required[] = $table[2];
				}
				return $required;
			}
					
			
			function isEntity($elem)
			{
				foreach ($this->dbConstruction as $tablename => $table) {
					if ($elem == $tablename)
					{
						if ($table["Type"] == "Entity")
							return true;
						else
							return false;
					}
				}
				return false;
			}
			
			
			function FindWay($tree, $findit)
			{

				foreach ($tree as $join => $subtree) {
					$tablename = explode(",",$join);	
					$tmp = explode(" ",$tablename[0]); //if we have an 'as' connection we need to get the name after the as					
					$tablename[0] = $tmp[sizeof($tmp)-1];
					/*
					if ((isset($tablename[3]))&&($tablename[3] == "important"))
					{			
						if (!$this->isInFindit($findit,$tablename[0]))
						{
							unset($tree[$join]);
							continue;
						}
					}-*/	
					if (is_array($subtree))
					{
						$tree[$join] = $this->FindWay($subtree, $findit);	
						if ($tree[$join] == array())
						{
							if (!$this->isInFindit($findit,$tablename[0]))
							{
								unset($tree[$join]);
								continue;
							}
						}
					}
				}
				return $tree;
			}
			
			function isInFindit($findit,$name)
			{
					for($i=0; $i<count($findit); $i++)
					{
						if ($findit[$i] == $name)
							return true;
					}
					return false;
			}
			
			
			function joinThem($absolute_origin, $origin, $already)
			{
				$result = array();

				
				foreach ($this->dbConstruction as $tablename => $table) {
					if(isset($table["Connecting"]))
					{
						
						$where = explode(',',$table["Connecting"]);//split the tables which it can connect so we can find the origin so we can find the id s we can connect
						$ids = explode(',',$table["ForeignKeys"]);
						$index = -1;
						for ($i =0; $i < count($where); $i++)
						{ 
							if($where[$i] == $origin)
								$index = $i;
						}	
						if (($index >= 0)&&($tablename !=$absolute_origin ))
						{
							if (!$this->isInResult($tablename,$already))
							{
								if ($table["Type"] == "Relation")
									$newbranch = $tablename . "," . $tablename . "." . $ids[$index] . "," . $origin . "." . $this->getIDofTable($origin) . ",important";
								else
									$newbranch = $tablename . "," . $tablename . "." . $ids[$index] . "," . $origin . "." . $this->getIDofTable($origin);
								
								$save_already = $already;
								$already .= "," . $tablename;
								$result += [$newbranch => $this->joinThem($absolute_origin, $tablename, $already)];
								$already = $save_already;
							}						
						}
					}
				}
				foreach ($this->dbConstruction as $tablename => $table) {
					if(   ($tablename == $origin)&&(isset($table["Connecting"])))
					{
						$where = explode(',',$table["Connecting"]);//split the tables which it can connect so we can find the origin so we can find the id s we can connect
						$ids = explode(',',$table["ForeignKeys"]);
						for ($i = 0; $i < count($where); $i++)
						{
							if (!$this->isInResult($where[$i],$already)&&($where[$i] != $absolute_origin))
							{
								
								$alias = $this->getAlias($where[$i]);
								$realname = $alias;
								if ($alias != $where[$i])
								
									$alias = $realname . " AS " . $where[$i];
								$newbranch = $alias . "," . $where[$i] . "." . $this->getIDofTable($realname) . "," . $tablename . "." . $ids[$i];
								$already .= "," . $where[$i];
								$result += [$newbranch => $this->joinThem($absolute_origin, $where[$i], $already)];
							}
						}
					}
				}
				return $result;
			}
			
			
			function isInResult($table, $result)
			{
				$alreadyin = explode(',',$result);
				for ($i = 0; $i < count($alreadyin); $i++)
				{
					if ($alreadyin[$i] == $table)
						return true;
				}
				return false;
			}
			
			function getIDofTable($origin)
			{
				foreach ($this->dbConstruction as $tablename => $table) {
					if($tablename == $origin)
						return $table["ID"];
				}
				return null;
			}
			
			
			
			
			function getAlias($name)
			{
				foreach ($this->dbConstruction as $tablename => $table) {
					if ($this->isstrContains($table["Alias"], $name))
					{
						
						return $tablename;
						}
				}
				return $name;
			}
			
			function isstrContains($str1, $str2)
			{
				if (strlen($str1) == 0)
					return false;
				if (strlen($str2) == 0)
					return false;
				for ($i = 0; $i<= strlen($str1)-strlen($str2); $i++ )
				{
					$subs = "";
					for ($j = 0; $j < strlen($str2); $j++)
						$subs .= $str1[$i+$j];
					if (strcmp ($subs, $str2) == 0)
						return true;
				}
				return false;
			}
	
			
			
			
			
		}
?>