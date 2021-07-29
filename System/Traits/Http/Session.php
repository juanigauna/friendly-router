<?php
namespace System\Traits\Http;

use Throwable, Exception;

trait Session {
    private array $session;
    private array $some = [];

    public function start(): bool {
        try {
            session_start();
            $this->session = $_SESSION;
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
            return false;
        }
        return true;
    }
    // Getters
    public function getValue(string $name): mixed {
        if (!isset($this->session[$name])) {
            throw new Exception('Not found "'.$name.'" cookie value.');
            return false;
        }
        return $this->session[$name];
    }
    // Setters
    public function setValue(string $name = '', mixed $value = ''): bool {
        if (empty($name)) {
            throw new Exception('You should specify a name to create a session value.');
            return false;
        }
        $_SESSION[$name] = $value;
        return true;
    }
    public function addValue(string $name) {
        $this->some[] = $name;
    }
    public function getSome(){
        return $this->some;
    }
}
