<?php

class UsersService
{
    private UsersRepository $usersRepository;
    private PersonsService $personsService;
    private TokenService $tokenService;

    public function __construct()
    {
        $this->usersRepository = new UsersRepository();
        $this->personsService = new PersonsService();
        $this->tokenService = new TokenService();
    }

    public function saveUser($user)
    {
        if ($this->personsService->existsPhoneNumber($user['phoneNumber'])) {
            return (new ErrorHandler(400, "register", "Phone Number Already Taken"))->throwError();
        }
        $personInsertResult = $this->personsService->savePerson($user);
        if ($personInsertResult) {
            $personId = $this->personsService->getLastInsertedPersonId();
            $user['personId'] = $personId;
            $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
            $user['password'] = $hashedPassword;
            $userInserResult = $this->usersRepository->insertUser($user);
            if ($userInserResult) {
                return array("statusCode" => 200, "message" => "Successfully Registered");
            } else {
                return (new ErrorHandler(400, "register", "User Could Not Be Registered"))->throwError();
            }
        } else {
            return (new ErrorHandler(400, "register", "User Could Not Be Registered"))->throwError();
        }
    }

    public function searchUserByPhoneNumber($phoneNumber)
    {
        $user = $this->usersRepository->findUserByPhoneNumber($phoneNumber);
        if ($user) {
            return $user;
        } else {
            return new ErrorHandler(404, "phoneNumber", "User Not Found");
        }
    }

    public function searchUserByUserId($userId)
    {
        $userData = $this->usersRepository->findUserByUserId($userId);
        if ($userData) {
            return $userData;
        } else {
            return new ErrorHandler(404, "general", "User Not Found");
        }
    }

    public function getUserFromJwt()
    {
        $jwt = $this->tokenService->getClientJwt();
        $phoneNumber = $this->tokenService->getPhoneNumberFromJwt($jwt);
        return $this->searchUserByPhoneNumber($phoneNumber);
    }

    public function isCorrectPassword($user, $password)
    {
        $savedPassword = $this->usersRepository->getUserPassword($user['userId']);
        return password_verify($password, $savedPassword);
    }
}
