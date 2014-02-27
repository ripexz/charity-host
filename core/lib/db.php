<?php
	class db {
		private $host = "localhost";
		private $user = "root";
		private $pass = "";
		private $name = "charity_host";

		public function __construct($params) {
			if ($params !== null) {
				$db->host = $params["host"];
				$db->user = $params["user"];
				$db->pass = $params["pass"];
				$db->name = $params["name"];
			}
		}

		public function connect() {
			$mysqli = new mysqli($this->host, $this->user, $this->pass, $this->name);
			return $mysqli;
		}

		public function escape_array($conn, &$itemArray) {
			foreach ($itemArray as $key => $value) {
				$itemArray[$key] = $conn->real_escape_string($value);
			}
			return $itemArray;
		}
	}
?>