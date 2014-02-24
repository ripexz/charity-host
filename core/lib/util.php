<?php

	require_once("db.php");

	/*
	* Checks if requested page belongs to charity or
	* if it's a normal page. Then redirects user to
	* the required page.
	*/
	function redirect_request($request) {
		$request = (string) $request;
		$request = trim($request);
		$request = trim($request, "/");

		$split = explode("/", $request);

		var_dump($split);
		return false;

		switch ($split[0]) {
			case 'home':
				header("Location: home.php?page=home&title=Home");
				break;

			case 'faq':
				header("Location: home.php?page=faq&title=FAQ");
				break;
			
			default:
				break;
		}
		$validCharity = validate_charity_link($split[0]);
		if (!$validCharity) {
			header("Location: 404.php");
		}
		else {
			header("Location: charity.php");
		}
	}

	/*
	* Checks if there's a charity with the link provided
	*/
	function validate_charity_link($link) {
		$db = new db(null);
		$conn = $db->connect();
		if (!$conn->connect_errno) {
			$safe_link = $conn->real_escape_string($safe_link);

			$result = $conn->query("SELECT id FROM charities WHERE link = '{$safe_link}'");
			if ($result) {
				if ($return->num_rows() == 1) {
					return true;
				}
			}
		}
		return false;
	}

?>