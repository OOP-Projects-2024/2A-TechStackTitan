<?php
    //meta data
    header("Access-Control-Allow-Origin: *"); //for sites request from client to allow anyone to send request for the API.
    header("Content-Type: application/json; charset=uft-8"); //type of file to be passed on the backend
    header("Access-Control-Allow-Methods: POST, GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers"); //optional

    date_default_timezone_set("Asia/Manila"); //setting default timezone for back-end server

    define("SERVER", "localhost");
    define("DBASE", "skilllink_db"); //name of your own database
    define("USER", "root"); //by default
    define("PWORD", ""); //by default
    define("TOKEN_KEY", "C7F7E1674B9DFD7BC6316DF3CC2CE");
    define("SECRET_KEY","KAMOTETALONGISDAPIDS");

    class Connection {
        protected $connectionString = "mysql:host=" . SERVER . ";dbname=" .DBASE. ";charset=utf8"; //concatenate host, database name.
        protected $options = [ //seting for database
            \PDO::ATTR_ERRMODE =>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //fetch mode is how database returns data. Converts value being retrieved as column value pair.
            \PDO::ATTR_EMULATE_PREPARES => false //for preparation for pdo
        ]; 

        public function connect(){ //responsible for returning pdo instance
            return new \PDO($this->connectionString, USER, PWORD, $this->options);
        }
    }
?>