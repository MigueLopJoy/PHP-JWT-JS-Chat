<?php
class ContactsController
{

    private ContactsService $contactsService;
    private UsersService $usersService;

    public function __construct()
    {
        $this->contactsService = new ContactsService();
        $this->usersService = new UsersService();
    }

    public function getUserContacts()
    {
        return json_encode($this->contactsService->getUserContacts());
    }

    public function searchContact($contactPhoneNumber)
    {
        return json_encode($this->contactsService->searchContactByPhoneNumber($contactPhoneNumber));
    }
}
