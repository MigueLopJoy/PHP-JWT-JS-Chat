<?php
class ContactsService
{

    private ContactsRepository $contactsRepository;
    private UsersService $usersService;
    private PersonsService $personsService;

    public function __construct()
    {
        $this->contactsRepository = new ContactsRepository();
        $this->usersService = new UsersService();
        $this->personsService = new PersonsService();
    }

    public function saveContact($newContactPhoneNumber)
    {
        $person = $this->personsService->searchPersonByPhoneNumber($newContactPhoneNumber);
        if ($person) {
            $user = $this->usersService->getUserFromJwt();
            $insertResult = $this->contactsRepository->insertContact($person['personId'], $user['userId']);
            if ($insertResult) {
                return $this->contactsRepository->getLastInsertedContact();
            }
        } else {
            return null;
        }
    }

    public function saveContactUserContact($user, $newContactPhoneNumber)
    {
        $person = $this->personsService->searchPersonByPhoneNumber($newContactPhoneNumber);
        if ($person) {
            $insertResult = $this->contactsRepository->insertContact($person['personId'], $user['userId']);
            if ($insertResult) {
                return $this->contactsRepository->getLastInsertedContact();
            }
        }
    }

    public function getUserContacts()
    {
        $user = $this->usersService->getUserFromJwt();
        $contacts = $this->contactsRepository->findUserContacts($user['userId']);
        if (count($contacts) > 0) {
            return array("contacts" => $contacts);
        } else {
            return new ErrorHandler(404, "contacts", "No Contact Were Found");
        }
    }

    public function searchUserContacts($user)
    {
        $contactsData = $this->contactsRepository->findUserContacts($user['userId']);
        if (count($contactsData) > 0) {
            return $contactsData;
        } else {
            return null;
        }
    }

    public function searchContactByPhoneNumber($contactPhoneNumber)
    {
        $user = $this->usersService->getUserFromJwt();
        $contact = $this->contactsRepository->findContactByPhoneNumber($contactPhoneNumber, $user['userId']);
        if ($contact !== null) {
            return $contact;
        } else {
            $savedContact = $this->saveContact($contactPhoneNumber);
            if ($savedContact !== null) {
                return $savedContact;
            } else {
                return (new ErrorHandler(404, "contacts", "Phone Number Doesn't Exist"))->throwError();
            }
        }
    }

    public function searchContactByContactId($contactId)
    {
        $contactData = $this->contactsRepository->findContactByContactId($contactId);
        if ($contactData) {
            return $contactData;
        } else {
            return null;
        }
    }

    public function ensureMutualContactRelationship($user, $contact)
    {
        $contactUser = $this->usersService->searchUserByPhoneNumber($contact['phoneNumber']);
        $contactUserContacts = $this->searchUserContacts($contactUser);
        if ($contactUserContacts !== null && count($contactUserContacts) > 0) {
            foreach ($contactUserContacts as $contactUserContact) {
                if ($contactUserContact['phoneNumber'] === $user['phoneNumber']) {
                    return;
                }
            }
        }
        return $this->saveContactUserContact($contactUser, $user['phoneNumber']);
    }
}
