<?php
class AuthenticationController
{

    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
    }

    public function isUserAuthenticated()
    {
        return $this->authenticationService->isUserAuthenticated();
    }

    public function authenticateUser($request)
    {
        return json_encode($this->authenticationService->authenticateUser($request));
    }

    public function registerUser($request)
    {
        return json_encode($this->authenticationService->registerUser($request));
    }
}
