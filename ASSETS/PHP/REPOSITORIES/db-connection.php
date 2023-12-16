<?php
class DBConnection
{
    private $host;
    private $user;
    private $password;
    private $database;

    public function __construct()
    {
        $this->host = "localhost:3306";
        $this->user = "root";
        $this->password = "";
        $this->database = "FETCH+PHP+Chat";
    }

    public function connect()
    {
        $connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($connection->connect_error) {
            echo "$connection->connect_error";
            die("Connection Failed : " . $connection->connect_error);
        }
        return $connection;
    }

    public function close($connenction)
    {
        $connenction->close();
    }

    public function getSingleSearchResult($sql)
    {
        $result = $this->executeSql($sql);
        if ($result !== null) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getMultipleSearchResult($sql)
    {
        $result = $this->executeSql($sql);
        if ($result !== null) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    private function executeSql($sql)
    {
        $connection = $this->connect();
        $result = $connection->query($sql);
        if ($result && $result->num_rows > 0) {
            $this->close($connection);
            return $result;
        }
        return null;
    }
}
