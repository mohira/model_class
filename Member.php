<?php

class Member {
    public $dbh;
    public $dsn  = 'mysql:host=localhost;dbname=sample_db;charset=utf8';
    public $user = 'root';
    public $pass = '';

    public function __construct() {
        try {
            $this->dbh = new PDO($this->dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



}


$member = new Member();

$data_1 = array(
    'name' => 'kashiwagi',
    'email' => 'kashiwagi@example.com'
    );

// $member->insert($data_1);


/*

// functions.php
function connectDb() {
    try
    {
        return new POD(DSN, DB_USER, DB_PASSWORD);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}


// config.php
define('DSN', 'mysql:host=localhost;dbname=sample_db;charset=utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// index.php
require 'config.php';
require 'functions.php';


$dbh = connectDb();

$sql = "";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id)
$stmt->execute();

*/






















