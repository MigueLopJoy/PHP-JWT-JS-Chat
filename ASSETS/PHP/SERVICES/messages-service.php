<?php
class MessagesService
{
    private MessagesRepository $messagesRepository;
    private UsersService $usersService;
    private ContactsService $contactsService;
    private TokenService $tokenService;

    public function __construct()
    {
        $this->messagesRepository = new MessagesRepository();
        $this->usersService = new UsersService();
        $this->contactsService = new contactsService();
        $this->tokenService = new TokenService();
    }

    public function saveMessage($newMessage)
    {
        $user = $this->usersService->getUserFromJwt();
        $contact = $this->contactsService->searchContactByPhoneNumber($newMessage['contactPhoneNumber']);
        $this->contactsService->ensureMutualContactRelationship($user, $contact);
        $messageData = array(
            "user" => $user,
            "contact" => $contact,
            "message" => $newMessage['message'],
            "dateTime" => (new DateTime())->format('d/m/y H:i:s')
        );
        $insertResult = $this->messagesRepository->insertMessage($messageData);
        if ($insertResult) {
            return $this->messagesRepository->getLastInsertedMessage();
        }
    }

    public function getConversation($contactPhoneNumber)
    {
        $jwt = $this->tokenService->getClientJwt();
        $user = $this->usersService->getUserFromJwt($jwt);
        $userId = $user['userId'];
        $contact = $this->contactsService->searchContactByPhoneNumber($contactPhoneNumber);
        $contactId = $contact['contactId'];
        $contactUser = $this->usersService->searchUserByPhoneNumber($contact['phoneNumber']);
        $contactUserId = $contactUser['userId'];
        $userContact = $this->getUserContact($contactUser, $user);
        $userContactId = 0;
        if ($userContact) {
            $userContactId = $userContact['contactId'];
        }
        $conversation =
            $this->messagesRepository->getConversation($userId, $contactId, $contactUserId, $userContactId);
        if ($conversation !== null && count($conversation) > 0) {
            http_response_code(200);
            return array("conversation" => $conversation);
        } else {
            return (new ErrorHandler(404, "messages", "Void conversation"))->throwError();
        }
    }

    private function getUserContact($contactUser, $user)
    {
        $contactUserContacts = $this->contactsService->searchUserContacts($contactUser);
        if ($contactUserContacts !== null && count($contactUserContacts) > 0) {
            foreach ($contactUserContacts as $contactUserContact) {
                $contactPhoneNumber = $contactUserContact['phoneNumber'];
                if ($contactPhoneNumber === $user['phoneNumber']) {
                    return $contactUserContact;
                }
            }
        } else {
            return null;
        }
    }
}
