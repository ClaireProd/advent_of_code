<?php

class Rule
{
    private const RULE_SEPARATOR = '|';
    public function __construct(public int $priorityPage, public int $secondaryPage)
    {
    }

    public static function fromString(string $rule): self
    {
        $rule = explode(self::RULE_SEPARATOR, $rule);
        return new self($rule[0], $rule[1]);
    }

    public function validate(int|bool $firstPage , int|bool $secondPage): bool
    {
        if ($firstPage === false || $secondPage === false) {
            return true;
        }

        if ($firstPage < $secondPage) {
            return true;
        }

        return false;
    }
}
