<?php
class Connection{
	// Include database connection settings
	private $username = "tamara_dev";
	private $password = "dev123";
	private $hostname = "localhost";
	private $database = "tamara_dokdig";
    
	public function up(){
		$this->dbh_system = mysql_connect($this->hostname, $this->username, $this->password) or die("Unable to connect to MySQL");
		//print "Connected to MySQL<br>";
		$this->selected = mysql_select_db($this->database, $this->dbh_system) or die("Could not fetch database");
		//print "Select ganiko_users Database<br>";
	}

	public function down(){
		$dbh_system = mysql_connect($this->hostname, $this->username, $this->password);
		mysql_close($dbh_system);
	}

}
?>