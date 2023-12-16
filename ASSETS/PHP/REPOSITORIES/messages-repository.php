<?php
class MessagesRepository
{
    private DBConnection $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DBConnection();
    }

    public function insertMessage($messageData)
    {
        $message = $messageData['message'];
        $userId = $messageData['user']['userId'];
        $contactId = $messageData['contact']['contactId'];
        $dateTime = $messageData['dateTime'];

        $connection = $this->dbConnection->connect();
        $insertQuery = "insert into messages(userId, contactId, message, dateTime) values(?, ?, ?, ?)";
        $statement = $connection->prepare($insertQuery);
        $statement->bind_param("iiss", $userId, $contactId, $message, $dateTime);
        $insertResult = $statement->execute();
        $this->dbConnection->close($connection);
        return $insertResult;
    }

    function getConversation($userId, $contactId, $contactUserId, $userContactId)
    {
        $sql =
            "
                SELECT m.messageId, m.userId, p.firstname, p.surname, p.phoneNumber, m.contactId, m.message, m.dateTime
                FROM messages m
                INNER JOIN users u
                ON m.userId = u.userId
                INNER JOIN persons p
                ON u.personId = p.personId
                WHERE (m.userId = $userId AND m.contactId = " . $contactId . ") 
                OR (m.userId = " . $contactUserId . " AND m.contactId = " . $userContactId . ")
            ";
        return $this->dbConnection->getMultipleSearchResult($sql);
    }

    public function getLastInsertedMessage()
    {
        $sql =
            "
            SELECT m.messageId, m.userId, p.firstname, p.surname, p.phoneNumber, m.contactId, m.message, m.dateTime
            FROM messages m
            INNER JOIN users u
            ON m.userId = u.userId
            INNER JOIN persons p
            ON u.personId = p.personId            
            ORDER BY m.messageId DESC
            LIMIT 1
            ";
        return $this->dbConnection->getSingleSearchResult($sql);
    }
}
