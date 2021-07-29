<?php
namespace System\Traits\Http;

use Exception, Throwable;

trait Cookies {
    private array $cookies;
    private array $cookieStructure = [
        'name' => '',
        'value' => '',
        'expires' => 0,
        'path' => '',
        'domain' => '',
        'secure' => false,
        'httponly' => false
    ];

    public function __construct() {
        $this->cookies = $_COOKIE;
    }
    // Helpers 
    public function validateCookieValues(array $dataInput): array {
        foreach ($this->cookieStructure as $param => $value) {
            if (!isset($dataInput['name']) || empty($dataInput['name'])) {
                throw new Exception('You should specify a name to create a cookie.');
            }
            if (!isset($dataInput[$param])) {
                $dataInput[$param] = $value;
            }
        }
        return $dataInput;
    } 
    // Getters
    public function getCookie(string $name): mixed {
        if (!isset($this->cookies[$name])) {
            throw new Exception('Not found "'.$name.'" cookie value.');   
        }
        return $this->cookies[$name];
    }
    // Setters
    public function setCookie(
        array $data = [
            'name' => '',
            'value' => '',
            'expires' => 0,
            'path' => '',
            'domain' => '',
            'secure' => false,
            'httponly' => false
        ]
    ): bool {
        try {
            $data = $this->validateCookieValues($data);
            setcookie(
                $data['name'],
                $data['value'],
                $data['expires'],
                $data['path'],
                $data['domain'],
                $data['secure'],
                $data['httponly']
            );
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
            return false;
        }
        return true;
    }
}
