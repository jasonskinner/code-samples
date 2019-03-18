<?php
/*
* Example
* Mysql database class - only one connection alowed
*/
class DatabaseConnection {
	/**
	 * @var mysqli
	 */
	private $_connection;

	/**
	 * @var
	 */
	private static $_instance;

	/**
	 * @var string
	 */
	private $_host = "HOST";

	/**
	 * @var string
	 */
	private $_username = "USERNAME";
	/**
	 * @var string
	 */
	private $_password = "PASSWORD";
	/**
	 * @var string
	 */
	private $_database = "DATABASE";

	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * DatabaseConnection constructor.
	 */
	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username,
			$this->_password, $this->_database);

		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				E_USER_ERROR);
		}
	}

	/**
	 * Prevent Dups
	 */
	private function __clone() { }

	/**
	 * Get instance
	 *
	 * @return mysqli
	 */
	public function getConnection() {
		return $this->_connection;
	}
}
?>
