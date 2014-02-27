<?php

	require_once("db.php");
	require_once("main_site.php");
	require_once("charity.php");

	/*
	* Checks if requested page belongs to charity or
	* if it's a normal page. Then redirects user to
	* the required page.
	*/
	function handle_request($request) {
		$request = (string) $request;
		$request = trim($request);
		$request = trim($request, "/");

		$split = explode("/", $request);

		switch ($split[0]) {
			case 'home':
				output_page("home", "Home");
				break;

			case 'faq':
				output_page("faq", "FAQ");
				break;
			
			default:
				$validCharity = validate_charity_link($split[0]);
				if ($validCharity === false) {
					show_not_found();
				}
				else {
					output_charity_page($split, $validCharity["name"], $validCharity["id"]);
				}
				break;
		}
	}

	/*
	* Checks if there's a charity with the link provided,
	* returns charity data or false if not found.
	*/
	function validate_charity_link($link) {
		$db = new db(null);
		$conn = $db->connect();
		if (!$conn->connect_errno) {
			$safe_link = $conn->real_escape_string($link);

			$result = $conn->query("SELECT id, name FROM charities WHERE link = '{$safe_link}'");
			if ($result->num_rows == 1) {
				return $result->fetch_assoc();
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	/*
	* Encrypts password provided using whirlpool hashing algorithm
	*/
	function encrypt($password) {
		return hash("whirlpool", $password);
	}

	function show_not_found(); {
		include "404.php";
	}
?>