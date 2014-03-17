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
				$charity = validate_charity_link($split[0]);
				if ($charity === false) {
					show_not_found();
				}
				else {
					if ($charity["logo_url"] == '') {
						output_charity_page($split, $charity["name"], $charity["id"], $charity["contacts"], $charity["paypal"], $charity["bg_color"]);
					}
					else {
						output_charity_page($split, $charity["name"], $charity["id"], $charity["contacts"], $charity["paypal"], $charity["bg_color"], $charity["logo_url"]);
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

			$result = $conn->query("SELECT id, name, bg_color, logo_url, email, phone, address, paypal FROM charities WHERE link = '{$safe_link}'");
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
	* Generates a random password of specified length (10 by default)
	*/
	function generate_password($length = 10) {
		$list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$result .= $list[rand(0, strlen($list) - 1)];
		}
		return $result;
	}

	/*
	* Redirects user to a "Not Found" page
	*/
	function show_not_found() {
		include "404.php";
	}

	/*
	* Sends a link with a delete code for lost and found entries
	* to the user that created the entry.
	*/
	function email_delete_code($data, $code) {
		$message = "Thank you for submitting a Lost and Found entry, you can remove it at any time by following this link:<br>http://www.charityhost.eu/delete.php?code=" . $code;
		$subject = 'Lost and Found - CharityHost.eu';
		$headers = 'From: CharityHost.eu <mail@charityhost.eu>' . "\r\n" .
					'Reply-To: mail@charityhost.eu' . "\r\n" .
					'Content-Type: text/html' . "\r\n";
		
		mail($data["email"], $subject, $message, $headers);
	}

	/*
	* Sends the user their login details
	*/
	function email_user_details($email, $password, $charity_name = '', $new = false) {
		if ($new) {
			$message = "Hello, you've been added as an administrator to {$charity_name}.<br>
				Here are your login details:<br>
				<b>Login:</b> {$email}<br>
				<b>Password:</b> {$password}<br><br>
				You can now log in on http://www.charityhost.eu/admin";
		}
		else { //reset
			$message = "Hello, here are your new login details:<br>
				<b>Login:</b> {$email}<br>
				<b>Password:</b> {$password}<br><br>
				You can now log in on http://www.charityhost.eu/admin";
		}
		$subject = $new ? 'Welcome - CharityHost.eu' : 'Password Reset - CharityHost.eu';
		$headers = 'From: CharityHost.eu <mail@charityhost.eu>' . "\r\n" .
					'Reply-To: mail@charityhost.eu' . "\r\n" .
					'Content-Type: text/html' . "\r\n";
		
		mail($email, $subject, $message, $headers);
	}

	/*
	* Cleans array with htmlentities
	*/
	function htmlentities_array(&$data) {
		$safe = array();
		foreach ($data as $key => $value) {
			$safe[$key] = htmlentities($value);
		}
		return $safe;
	}
?>