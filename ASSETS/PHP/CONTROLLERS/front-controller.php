<?php
include "./../MODEL/person.php";
include "./../MODEL/user.php";
include "./../MODEL/contact.php";
include "./../MODEL/message.php";
include "./../MODEL/token.php";
include "./authentication-controller.php";
include "./contacts-controller.php";
include "./messages-controller.php";
include "./../SERVICES/authentication-service.php";
include "./../SERVICES/persons-service.php";
include "./../SERVICES/users-service.php";
include "./../SERVICES/contacts-service.php";
include "./../SERVICES/messages-service.php";
include "./../SERVICES/token-service.php";
include "./../REPOSITORIES/db-connection.php";
include "./../REPOSITORIES/persons-repository.php";
include "./../REPOSITORIES/users-repository.php";
include "./../REPOSITORIES/contacts-repository.php";
include "./../REPOSITORIES/messages-repository.php";
include "./../EXCEPTIONS/general-exception-handler.php";

if (isset($_GET['authenticate-user']) || isset($_GET['register-user']) || isset($_GET['is-user-authenticated'])) {
    $authenticationController = new AuthenticationController();
    if (isset($_GET['authenticate-user']) || isset($_GET['register-user'])) {
        $data = getInputData();
        if (isset($_GET['register-user'])) {
            echo $authenticationController->registerUser($data);
        } else if (isset($_GET['authenticate-user'])) {
            echo $authenticationController->authenticateUser($data);
        }
    } else if (isset($_GET['is-user-authenticated'])) {
        $authenticationController = new AuthenticationController();
        echo $authenticationController->isUserAuthenticated();
    }
} else {
    if (doesRequestPassJwtFilter()) {
        if (isset($_GET['get-contacts']) || isset($_GET['search-contact'])) {
            $contactsController = new ContactsController();
            if (isset($_GET['get-contacts'])) {
                echo $contactsController->getUserContacts();
            } else if (isset($_GET['search-contact'])) {
                echo $contactsController->searchContact($_GET['search-contact']);
            }
        } else if (isset($_GET['get-conversation']) || isset($_GET['send-message'])) {
            $messagesController = new MessagesController();
            if (isset($_GET['get-conversation'])) {
                echo $messagesController->getConversation($_GET['get-conversation']);
            } else if (isset($_GET['send-message'])) {
                $data = getInputData();
                echo $messagesController->saveMessage($data);
            }
        }
    } else {
        http_response_code(401);
        echo json_encode(array("statusCode" => 401, "field" => "general", "message" => "Unauthorized"));
    }
}

function doesRequestPassJwtFilter()
{
    $tokenService = new TokenService();
    $jwt = $tokenService->getClientJwt();
    return $tokenService->isTokenValid($jwt);
}

function getInputData()
{
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}
