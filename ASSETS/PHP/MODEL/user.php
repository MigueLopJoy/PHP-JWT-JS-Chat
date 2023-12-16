<?php
class User extends Person implements JsonSerializable
{
    private int $userId;
    private string $password;
    private array $contacts;

    public function __construct(
        int $userId,
        int $personId,
        string $firstname,
        string $surname,
        string $phoneNumber,
        string $password
    ) {
        parent::__construct($personId, $firstname, $surname, $phoneNumber);
        $this->userId = $userId;
        $this->password = $password;
        $this->contacts = array();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function addNewContact(User $contact): void
    {
        $this->contacts[] = $contact;
    }

    public function deleteContact(int $contactId): void
    {
        foreach ($this->contacts as $key => $contact) {
            if ($contact->getUserId() == $contactId) {
                unset($this->contacts[$key]);
                $this->contacts = array_values($this->contacts);
                break;
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            "userId" => $this->userId,
            "firstname" => $this->getFirstname(),
            "surname" => $this->getSurname(),
            "phoneNumber" => $this->getPhoneNumber()
        ];
    }
}
