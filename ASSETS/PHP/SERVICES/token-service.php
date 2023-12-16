<?php
class TokenService
{
    public function __construct()
    {
    }

    public function generateJwt($phoneNumber): string
    {

        $token = new Token();
        $headers = $this->encode(json_encode($token->getHeaders()));
        $payloadData = [
            'phoneNumber' => $phoneNumber,
            'exp' => time() + (60 * 60)
        ];
        $payload = $this->encode(json_encode($payloadData));
        $signature = hash_hmac('SHA256', "$headers.$payload", $token->getSecret(), true);
        $signature = $this->encode($signature);
        return "$headers.$payload.$signature";
    }

    public function isTokenValid($jwt)
    {
        if ($jwt !== null) {
            $token = new Token();

            $jwtSections = explode('.', $jwt);
            if (!isset($jwtSections[0]) || !isset($jwtSections[1]) || !isset($jwtSections[2])) {
                return false;
            }
            $headers = base64_decode($jwtSections[0]);
            $payload = base64_decode($jwtSections[1]);
            $clientSignature = $jwtSections[2];

            if (!$this->isTokenNonExpired($payload)) {
                return false;
            }

            $base64_header = $this->encode($headers);
            $base64_payload = $this->encode($payload);

            $signature = hash_hmac('SHA256', $base64_header . "." . $base64_payload, $token->getSecret(), true);
            $base64_signature = $this->encode($signature);

            return ($base64_signature === $clientSignature);
        } else {
            return false;
        }
    }

    private function encode(string $str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    private function isTokenNonExpired($payload)
    {
        return (json_decode($payload) && (json_decode($payload)->exp - time()) > 0);
    }

    function getClientJwt()
    {
        $jwt = null;
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $pos = strpos($authHeader, 'Bearer ');
            $jwt = substr($authHeader, $pos + strlen('Bearer '));
        }
        return $jwt;
    }


    function getPhoneNumberFromJwt(string $jwt)
    {
        $token = explode('.', $jwt);
        $JSONPayload = base64_decode($token[1]);
        $payload = json_decode($JSONPayload, true);
        return $payload['phoneNumber'];
    }
}
