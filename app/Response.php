<?php
declare(strict_types=1);

class Response {
    private $content;
    private int $code;
    private array $headers;

    public function __construct($content, int $code = 200, array $headers = []) {
        $this->content = $content;
        $this->code = $code;
        $this->headers = $headers;
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html; charset=UTF-8';
        }
    }

    public function send(): void {
        http_response_code($this->code);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->content;
    }
}
