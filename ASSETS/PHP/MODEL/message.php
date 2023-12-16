<?php

class MessageObj implements JsonSerializable
{
    private int $messageId;
    private User $user;
    private Contact $contact;
    private string $message;
    private string $dateTime;

    public function __construct(
        int $messageId,
        User $user,
        Contact $contact,
        string $message,
        string $dateTime
    ) {
        $this->messageId = $messageId;
        $this->user = $user;
        $this->contact = $contact;
        $this->message = $message;
        $this->dateTime = $dateTime === "" ? $this->getCurrentDateTime() : $dateTime;
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setDateTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "messageId" => $this->messageId,
            "user" => $this->user,
            "contact" => $this->contact,
            "message" => $this->message,
            "dateTime" => $this->dateTime
        ];
    }

    private function getCurrentDateTime()
    {
        $dateTime = new DateTime();
        return $dateTime->format('d/m/y H:i:s');
    }
}
