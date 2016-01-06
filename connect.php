<?php
if(!defined("INIT")) {

	define("INIT", "TRUE", false);

	session_start();
	
	function error($message) {
		session_destroy();
//		Header("HTTP/1.1 303 See Other"); 
//		Header("Location: /");
		$message .= "<br />";
		$message .= "Note: Now running in debug mode, no auto redirect for errors.<br />";
		$message .= "If your browser stayed on this page, please click <a href='/'>here</a> to the index page.";
		die($message);
	}
	
	function logout() {
		Header("HTTP/1.1 303 See Other"); 
		Header("Location: /");
		error("NOT AN ERROR: Log out.");
	}

	function init($permission, $getdb = true) {

		$dbdesc = array(
			"host" => "silva.computing.dundee.ac.uk",
			"schema" => "14ac3d07",
			//permission map, map a user type to it's mysql user & password
			"pmap" => array(
				"login" => array("user" => "14ac3extra07", "password" => "bac132"),
				"admin" => array("user" => "14ac3user07", "password" => "bac132"),
				"ceo" => array("user" => "14ac3extra07", "password" => "bac132"),
				"manager" => array("user" => "14ac3extra07", "password" => "bac132"),
				"accountant" => array("user" => "14ac3extra07", "password" => "bac132"),
				"buyer" => array("user" => "14ac3other07", "password" => "bac132")
			),
			//home map, map a user type to it's home page url
			"hmap" => array(
				"admin" => array(array('route' => 'admin-create', 'moduleId' => 'admin/create/index', 'title' => 'Create', 'nav' => true),
								 array('route' => 'admin-delete', 'moduleId' => 'admin/delete/index', 'title' => 'Delete', 'nav' => true)),
				"ceo" => array(array('route' => 'ceo-view', 'moduleId' => 'ceo/index', 'title' => 'CEO', 'nav' => true)),
				"manager" => array(array('route' => 'manager-view', 'moduleId' => 'manager/index', 'title' => 'Manager View', 'nav' => true)),
				"accountant" => array(array('route' => 'accountant-view', 'moduleId' => 'accountant/index', 'title' => 'Accountant', 'nav' => true)),
			)
		);

		if(!defined("INIT_FUNCTIONS")) {

			define("INIT_FUNCTIONS", "TRUE", false);

			function getdb($permission, $dbdesc) {
				$db = new mysqli(
					$dbdesc["host"],
					$dbdesc["pmap"][$permission]["user"],
					$dbdesc["pmap"][$permission]["password"],
					$dbdesc["schema"]
				);
				if($db->connect_error) {
					error("Fatal: Database connection error (".$db->connect_error).").";
				}
				if(!$db->set_charset("utf8")) {
					error("Fatal: Unable to set charset (".$db->error.").");
				}
				return $db;
			}

			function login($dbdesc) {
				$request = $_POST;
				$db = getdb("login", $dbdesc);
				$sql = 
					"SELECT 
						id, name, permission_type 
					FROM 
						user 
					WHERE 
						name = ? AND 
						password = SHA1(?) AND 
						active = true;";
				$stmt = $db->prepare($sql);
				echo $db->error;
				$stmt->bind_param("ss", $request["username"], $request["password"]);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_array();
				if($result->lengths < 1) {
					error(
						"Fatal: Invalid user name or password used, or your account is not active now."
					);
				}
				$_SESSION["id"] = $row["id"];
				$_SESSION["name"] = $row["name"];
				$_SESSION["permission_type"] = $row["permission_type"];
				
				/*
				Header("HTTP/1.1 303 See Other"); 
				Header("Location: ".$dbdesc["hmap"][$_SESSION["permission_type"]]);
				echo "If your browser stayed on this page, click <a href="
					.$dbdesc["hmap"][$_SESSION["permission_type"]]
					.">here</a> to your home page.<br />";*/
					
				echo (json_encode( Array ("status" => "success", "path" => $dbdesc["hmap"][$_SESSION["permission_type"]]) ));
				
			}

			function checkPermission($permission) {
				if(!$permission || !$_SESSION) {
					error("Fatal: Not logged in or internal session error.");
				}
				if(!$_SESSION["permission_type"] || !$_SESSION["name"]) {
					error("Fatal: Internal session error.");
				}
				if($_SESSION["permission_type"] != $permission) {
					error("Fatal: Permission denied for user '"
						.$_SESSION["name"]
						."' to use the permission type of '"
						.$permission
						."' on this page.");
				}
				return true;
			}			
		}

		if(!array_key_exists($permission, $dbdesc["pmap"])) {
			error("Fatal: Illegal permission type value '"
				.$permission
				."' given for db access, no such permission type."
			);
		}

		if($permission == "login") {
			login($dbdesc);
			return null;
		}
		else if($permission == "buyer") {
			return getdb($permission, $dbdesc);			
		}
		else if($getdb && checkPermission($permission)) {
			return getdb($permission, $dbdesc);
		}
		else {
			return null;
		}
	}
}
?>
