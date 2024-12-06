<?php

class PrintQueue
{
    private const RULES_UPDATES_DELIMITATION = "\n\n";
    public array $rules = [];
    public array $updates = [];
    private function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public static function create(string $path): self
    {
        return new self($path);
    }

    public function parseData(): self
    {
        $content = explode(self::RULES_UPDATES_DELIMITATION, $this->getContent());

        $this->rules = array_map(function (string $rule) {
            return Rule::fromString($rule);
        }, explode("\n", $content[0]));

        $this->updates = array_map(function (string $updates) {
            return new Update($updates);
        }, explode("\n", $content[1]));

        return $this;
    }

    private static function exists(string $path): bool
    {
        return file_exists($path);
    }

    private function getContent(): string
    {
        return trim(file_get_contents($this->path));
    }
}
