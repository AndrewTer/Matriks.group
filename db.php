<?php
    class database
    {
        protected $databaseLink;
        
        function __construct()
        {
            include "constants.php";
            $this->db_server = $dbInfo['server'];
            $this->mysql_user = $dbInfo['user'];
            $this->mysql_pass = $dbInfo['pass'];
            $this->db_name = $dbInfo['name'];
            $this->createConnection();
            return $this->get_link();
        }
        
        function createConnection()
        {
            $this->databaseLink = mysql_connect($this->db_server, $this->mysql_user, $this->mysql_pass) or die(mysql_error());
            mysql_select_db($this->db_name) or die("Cannot select DB");	
        }
    
        function get_link()
        {
            return $this->databaseLink;
        }
    }
?>