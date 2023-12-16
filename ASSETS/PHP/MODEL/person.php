<?php

abstract class Person implements JsonSerializable
{
    private int $personId;
    private string $firstname;
    private string $surname;
    private string $phoneNumber;

    public function __construct(
        int $personId,
        string $firstname,
        string $surname,
        string $phoneNumber
    ) {
        $this->personId = $personId;
        $this->firstname = $firstname;
        $this->surname = $surname;
        $this->phoneNumber = $phoneNumber;
    }

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPersonId(int $personId): void
    {
        $this->personId = $personId;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "firstname" => $this->firstname,
            "surname" => $this->surname,
            "phoneNumber" => $this->phoneNumber
        ];
    }
}
