<?php
class PersonsService
{
    private PersonsRepository $personsRepository;

    public function __construct()
    {
        $this->personsRepository = new PersonsRepository();
    }

    public function savePerson($user)
    {
        return $this->personsRepository->insertPerson($user);
    }

    public function getLastInsertedPersonId()
    {
        return $this->personsRepository->getLastInsertedPersonId();
    }

    public function getAllPhoneNumbers()
    {
        return $this->personsRepository->getAllPhoneNumbers();
    }

    function existsPhoneNumber($phoneNumber)
    {
        if ($this->personsRepository->findPersonByPhoneNumber($phoneNumber) !== null) {
            return true;
        } else {
            return false;
        }
    }

    function searchPersonByPhoneNumber($phoneNumber)
    {
        $person = $this->personsRepository->findPersonByPhoneNumber($phoneNumber);
        if ($person) {
            return $person;
        } else {
            return null;
        }
    }
}
