<?php
declare(strict_types=1);

class File {
    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getContent(): string {
        if (!file_exists($this->path)) {
            throw new Exception("File not found: " . $this->path);
        }
        $content = file_get_contents($this->path);
        if ($content === false) {
            throw new Exception("Cannot read file: " . $this->path);
        }
        return $content;
    }
}
