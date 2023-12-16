<?php
class Token
{
    private array $headers;
    private string $secret;

    public function __construct()
    {
        $this->headers = [
            'alg' => 'HS256'
        ];
        $this->secret = '5eefadc4453b7c2fbd3f84789435dc809d148861b1201df7413883f717f62e29';
    }


    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
