<?php
namespace System\Http;

class Response {
    private array $headers = [];

    public function setHeader(string $header): bool {
        $this->headers[] = $header;
        return true;
    }
    public function getHeaders(): array {
        return $this->headers;
    }
    public function setContentType(string $type): bool {
        $this->setHeader("content-type: {$type}");
        return true;
    }
    public function json(array|object $content): string {
        $this->setContentType('application/json');
        return json_encode($content);
    }
}