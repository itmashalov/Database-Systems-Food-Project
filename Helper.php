<?php
		function pretty_print_array($form_data)
		{
			foreach($form_data as $key=>$value) {
				echo($key . "=>");
				print_r($value);
				echo("<br>");
			}
		}
?>