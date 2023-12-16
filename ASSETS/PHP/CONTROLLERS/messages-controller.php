<?php
class MessagesController
{

    private MessagesService $messagesService;

    public function __construct()
    {
        $this->messagesService = new MessagesService();
    }

    public function getConversation($contactPhoneNumber)
    {
        return json_encode($this->messagesService->getConversation($contactPhoneNumber));
    }

    public function saveMessage($messageData)
    {
        return json_encode($this->messagesService->saveMessage($messageData));
    }
}
