<?php
namespace FriendlyRouter;

class Response {
    private $status = 200, $content, $json = false;
    public function status($status) {
        $this->status = $status;
    }
    public function content($content) {
        $this->content = $content;
    }
    public function json($content) {
        $this->json = true;
        $this->content = $content;
    }
    public function send($contentType = 'Content-Type: text/html') {
        http_response_code($this->status);
        if ($this->json || $contentType === 'Content-Type: application/json') {
            header('Content-Type: application/json');
            echo json_encode($this->content, JSON_PRETTY_PRINT);
            return;
        }
        header($contentType);
        if (!empty($this->content)) echo $this->content;
    }
}