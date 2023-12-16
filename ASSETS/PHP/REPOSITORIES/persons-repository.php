<?php
class PersonsRepository
{
    private DBConnection $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DBConnection();
    }

    public function insertPerson($user)
    {
        $firstname = $user['firstname'];
        $surname = $user['surname'];
        $phoneNumber = $user['phoneNumber'];

        $connection = $this->dbConnection->connect();
        $insertQuery = "insert into persons(firstname, surname, phoneNumber) values(?, ?, ?)";
        $statement = $connection->prepare($insertQuery);
        $statement->bind_param("sss", $firstname, $surname, $phoneNumber);
        $insertResult = $statement->execute();
        $this->dbConnection->close($connection);
        return $insertResult;
    }

    public function getLastInsertedPersonId()
    {
        $sql = "SELECT MAX(personId) as lastPersonId FROM persons";
        return ($this->dbConnection->getSingleSearchResult($sql))['lastPersonId'];
    }

    public function getAllPhoneNumbers()
    {
        $sql = "SELECT phoneNumber FROM persons";
        return $this->dbConnection->getMultipleSearchResult($sql);
    }

    function findPersonByPhoneNumber($phoneNumber)
    {
        $sql =
            "
            SELECT personId, firstname, surname, phoneNumber 
            FROM persons 
            WHERE phoneNumber = $phoneNumber;
        ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }
}
