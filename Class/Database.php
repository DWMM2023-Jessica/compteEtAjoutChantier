<?php

abstract class Database{

    public $host = "localhost";
    public $dbname = "jessica";
    public $username = "root";
    public $password = "";
    
    public function dbConnect(){
        $dbConnect = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnect;
    }
}