<?php
class ContactsRepository
{
    private DBConnection $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DBConnection();
    }

    public function insertContact($personId, $userId)
    {
        $connection = $this->dbConnection->connect();
        $insertQuery = "insert into contacts(personId, userId) values(?, ?)";
        $statement = $connection->prepare($insertQuery);
        $statement->bind_param("ii", $personId, $userId);
        $insertResult = $statement->execute();
        $this->dbConnection->close($connection);
        return $insertResult;
    }

    public function getLastInsertedContact()
    {
        $sql =
            "
            SELECT c.contactId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM contacts c
            INNER JOIN persons p
            ON c.personId = p.personId
            ORDER BY contactId DESC
            LIMIT 1
            ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }

    public function findUserContacts(int $userId)
    {
        $sql =
            "
            SELECT c.contactId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM contacts c
            INNER JOIN persons p
            ON c.personId = p.personId
            WHERE c.userId = " . $userId;
        return $this->dbConnection->getMultipleSearchResult($sql);
    }

    public function findContactByPhoneNumber($phoneNumber, $userId)
    {
        $sql =
            "
            SELECT c.contactId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM contacts c
            INNER JOIN persons p
            ON c.personId = p.personId
            WHERE p.phoneNumber = $phoneNumber
            AND c.userId = $userId;
            ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }

    public function findContactByContactId($contactId)
    {
        $sql =
            "
            SELECT c.contactId, p.personId, p.firstname, p.surname, p.phoneNumber
            FROM contacts c 
            INNER JOIN persons p
            ON c.personId = p.personId
            WHERE contactId = " . $contactId;
        return $this->dbConnection->getSingleSearchResult($sql);
    }

    public function searchUnsavedContacts($userId)
    {
        $sql =
            "
        SELECT m.userId p.personId, p.firstname, p.surname, p.phoneNumber
        FROM contacts c 
        INNER JOIN persons p
        ON c.personId = p.personId
        WHERE contactId = " . $contactId;
        return $this->dbConnection->getSingleSearchResult($sql);
    }
}
