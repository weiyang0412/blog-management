<?php
class db
{
    private $dbConnection = null;
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['pass'];
        $this->dbname = $config['dbname'];
    }

    public function connect()
    {
        if ($this->dbConnection === null) {
            try {
                $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
                $this->dbConnection = new PDO($mysql_connect_str, $this->user, $this->password);
                $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return $this->dbConnection;
    }
}
