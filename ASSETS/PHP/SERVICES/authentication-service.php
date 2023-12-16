<?php
class AuthenticationService
{

    private UsersService $usersService;
    private PersonsService $personsService;
    private TokenService $tokenService;

    public function __construct()
    {
        $this->usersService = new UsersService();
        $this->personsService = new PersonsService();
        $this->tokenService = new TokenService();
    }

    public function isUserAuthenticated()
    {
        $clientJwt = $this->tokenService->getClientJwt();
        $isUserAuthenticated = $this->tokenService->isTokenValid($clientJwt);
        return json_encode(array("isUserAuthenticated" => $isUserAuthenticated));
    }

    public function authenticateUser($request)
    {
        $phoneNumber = $request['phoneNumber'];
        $password = $request['password'];
        if ($this->personsService->existsPhoneNumber($phoneNumber)) {
            $user = $this->usersService->searchUserByPhoneNumber($phoneNumber);
            if ($this->usersService->isCorrectPassword($user, $password)) {
                $jwt = $this->tokenService->generateJwt($phoneNumber);
                return (array("responseCode" => 200, 'jwt' => $jwt));
            } else {
                return (new ErrorHandler(401, "login", "Wrong Password"))->throwError();
            }
        } else {
            return (new ErrorHandler(404, "login", "Contact Not Found"))->throwError();
        }
    }

    public function registerUser($request)
    {
        return $this->usersService->saveUser($request);
    }
}
