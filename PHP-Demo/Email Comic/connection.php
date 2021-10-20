<?php

//This class is used to connect with database
class Dbconnection
{

    // Hold the class instance.
    // Null variable used to create new instance 
    private static $instance = null;
    
    // Varible db_conn is mysql varible used to get connection with database
    private $db_conn;
    
    // Varible host is String varible used to provide hostname as a parameter in mysqli method
    private $host = 'localhost';
    
    // Varible db_name is String Varible used to provide database name as a parameter in mysqli method
    private $db_name = 'db_xkcd';
    
    // Varible user_name is String varible which is passed as parameter in mysqli as database user
    // The variable hold the database credential as username
    // (eg. private $user_name = 'Provide your database username')
    // (eg. private $user_name = 'test')
    private $user_name = '';
    
    // Varible pass is String variable which is passed as parameter in mysqli as database user password
    // The variable hold the database credential as password
    // (eg. private $pass = 'Provide your database password')
    // (eg. private $pass = 'test@123')
    private $pass = '';

    // Here the private constructor is used to get connection with the database
    // To initialize private variable conn  
    private function __construct()
    {
        // conn variable intialized with the connection of database
        $this->db_conn = new mysqli($this->host, $this->user_name, $this->pass, $this->db_name);
        if ($this-> db_conn -> connect_errno) {
            echo 'Connection Failed With Database: ' . $this -> conn -> connect_error;
            exit();
        }
    }

    // This method is used to create the object of class once, the concept of singlton class
    // The class is initialize only once 
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Dbconnection();
        }

        return self::$instance;
    }

    // The function is used to get connection of database
    public function getConnection()
    {
        return $this->db_conn;
    }
}