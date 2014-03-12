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
					if ($validCharity["logo_url"] == '') {
						output_charity_page($split, $validCharity["name"], $validCharity["id"], $validCharity["contacts"], $validCharity["bg_color"]);
					}
					else {
						output_charity_page($split, $validCharity["name"], $validCharity["id"], $validCharity["contacts"], $validCharity["bg_color"], $validCharity["logo_url"]);
					}
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

			$result = $conn->query("SELECT id, name, bg_color, logo_url, email, phone, address FROM charities WHERE link = '{$safe_link}'");
			if ($result->num_rows == 1) {
				$data = $result->fetch_assoc();
				$contacts = array();
				$contacts['email'] = $data['email'] ? $data['email'] : NULL;
				$contacts['phone'] = $data['phone'] ? $data['phone'] : NULL;
				$contacts['address'] = $data['address'] ? $data['address'] : NULL;
				$data["contacts"] = $contacts;
				return $data;
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

	/*
	* Redirects user to a "Not Found" page
	*/
	function show_not_found() {
		include "404.php";
	}

	/*
	* Sends a link with a delete code to the user
	*/
	function email_delete_code($data, $delete_code) {
		//todo
	}
?>