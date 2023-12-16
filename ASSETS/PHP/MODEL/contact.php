<?php

class Contact extends Person implements JsonSerializable
{
    private int $contactId;

    public function __construct(
        int $contactId,
        int $personId,
        string $firstname,
        string $surname,
        string $phoneNumber
    ) {
        parent::__construct($personId, $firstname, $surname, $phoneNumber);
        $this->contactId = $contactId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId): void
    {
        $this->contactId = $contactId;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "contactId" => $this->contactId,
            "personId" => $this->getPersonId(),
            "firstname" => $this->getFirstname(),
            "surname" => $this->getSurname(),
            "phoneNumber" => $this->getPhoneNumber()
        ];
    }
}
