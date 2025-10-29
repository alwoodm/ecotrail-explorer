<?php
declare(strict_types=1);

class DataTemplate implements Iterator {
    private array $data;

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    public function addData(string $key, string $value): void {
        $this->data[$key] = $value;
    }

    public function current(): mixed {
        return current($this->data);
    }

    public function key(): mixed {
        return key($this->data);
    }

    public function next(): void {
        next($this->data);
    }

    public function rewind(): void {
        reset($this->data);
    }

    public function valid(): bool {
        return key($this->data) !== null;
    }
}
