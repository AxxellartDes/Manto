<?php

class Database{

    private $hostsql;
    private $usersql;
    private $passwordsql;

    private $hostsqlunn;
    private $usersqlunn;
    private $passwordsqlunn;

    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    private $hostdt;
    private $dbdt;
    private $userdt;
    private $passworddt;
    private $charsetdt;

    public function __construct()
    {

        $this->hostsql = constant('HOSTSQL');
        $this->usersql = constant('USERSQL');
        $this->passwordsql = constant('PASSWORDSQL');

        $this->hostsqlunn = constant('HOSTSQLUNN');
        $this->usersqlunn = constant('USERSQLUNN');
        $this->passwordsqlunn = constant('PASSWORDSQLUNN');

        $this->host = constant('HOST');
        $this->db = constant('DB');
        $this->user = constant('USER');
        $this->password = constant('PASSWORD');
        $this->charset = constant('CHARSET');

        $this->hostdt = constant('HOSTDT');
        $this->dbdt = constant('DBDT');
        $this->userdt = constant('USERDT');
        $this->passworddt = constant('PASSWORDDT');
        $this->charsetdt = constant('CHARSETDT');
    }

    function connect(){
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" .  $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES  => false,
            ];
            $pdo = new PDO ($connection, $this->user, $this->password, $options);
            return $pdo;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }

    function connectdt(){
        try {
            $connection = "mysql:host=" . $this->hostdt . ";dbname=" .  $this->dbdt . ";charset=" . $this->charsetdt;
            $options = [
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES  => false,
            ];
            $pdo = new PDO ($connection, $this->userdt, $this->passworddt, $options);
            return $pdo;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }

    function connectSql(){
        try {
            $conn = new PDO("odbc:$this->hostsql", $this->usersql, $this->passwordsql);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }

    function connectSqlUnn(){
        try {
            $conn = new PDO("odbc:$this->hostsqlunn", $this->usersqlunn, $this->passwordsqlunn);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}

?>