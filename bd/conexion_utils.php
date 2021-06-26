<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

define("DB_HOST_UTILS", "localhost");
define("DB_NAME_UTILS", "giuliani");
define("DB_USER_UTILS", "root");
define("DB_PASSWORD_UTILS", "");

class ConexionUtils  extends PDO {

    private static $instancia;
    private $conn;
    private $host = DB_HOST_UTILS;
    private $db_name = DB_NAME_UTILS;
    private $username = DB_USER_UTILS;
    private $password = DB_PASSWORD_UTILS;
    protected $transactionCount = 0;

    public function beginTransaction()
    {
        if (!$this->transactionCounter++) {
            return parent::beginTransaction();
        }
        $this->exec('SAVEPOINT trans'.$this->transactionCounter);
        return $this->transactionCounter >= 0;
    }

    public function commit()
    {
        if (!--$this->transactionCounter) {
            return parent::commit();
        }
        return $this->transactionCounter >= 0;
    }

    public function rollback()
    {
        if (--$this->transactionCounter) {
            $this->exec('ROLLBACK TO trans'.$this->transactionCounter + 1);
            return true;
        }
        return parent::rollback();
    }
    
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            echo $error;

            die();
        }
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public static function singleton_conexion() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;

            self::$instancia = new $miclase;
        }

        return self::$instancia;
    }

    public function __clone() {
        trigger_error("La clonación de este objeto no está permitida", E_USER_ERROR);
    }
}
?>