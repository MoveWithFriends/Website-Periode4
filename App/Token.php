<?php

namespace App;

/**
 * unique random token
 * PHP version 7.1
 */
class Token
{
    /**
     * the token value
     * @var array
     */
    protected $token;

    public function __construct($token_value = null)
    {
        if ($token_value) {
            $this->token = $token_value;
        } else {
            $this->token = bin2hex(random_bytes(16)); //16 bytes =128 buts = 32 hex characters
        }
    }

    public function getValue()
    {
        return $this->token;
    }

    public function getHash()
    {
        return hash_hmac('sha256', $this->token, \App\Config::SECRET_KEY);  // sha256 = 64 chars
    }

}