<?php
declare(strict_types=1);

require_once "File.php";

class ParseTemplate {
    private File $file;

    public function __construct(File $file) {
        $this->file = $file;
    }

    public function parse(Iterator $data): string {
        $content = $this->file->getContent();
        foreach ($data as $key => $value) {
            $content = str_replace("{{" . $key . "}}", $value, $content);
        }
        return $content;
    }
}
