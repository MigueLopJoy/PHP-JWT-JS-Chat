<?php
class UsersRepository
{
    private DBConnection $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DBConnection();
    }

    public function insertUser($user)
    {
        $personId = $user['personId'];
        $password = $user['password'];

        $connection = $this->dbConnection->connect();
        $insertQuery = "insert into users(personId, password) values(?, ?)";
        $statement = $connection->prepare($insertQuery);
        $statement->bind_param("is", $personId, $password);
        $insertResult = $statement->execute();
        $this->dbConnection->close($connection);
        return $insertResult;
    }

    public function findUserByUserId($userId)
    {
        $sql =
            "
            SELECT u.userId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM users u
            INNER JOIN persons p
            ON u.personId = p.personId
            WHERE u.userId = $userId
            ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }

    public function findUserByPhoneNumber($phoneNumber)
    {
        $sql =
            "
            SELECT u.userId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM users u
            INNER JOIN persons p
            ON u.personId = p.personId
            WHERE p.phoneNumber = $phoneNumber
            ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }

    public function getUserPassword($userId)
    {
        $sql = "SELECT password FROM users WHERE userId = " . $userId;
        return ($this->dbConnection->getSingleSearchResult($sql))['password'];
    }
}
