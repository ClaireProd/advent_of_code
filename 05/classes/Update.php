<?php

class Update
{
    private const PAGE_SEPARATOR = ',';
    public function __construct(public string $pages) {}

    public function isValid(array $rules): bool
    {
        foreach ($rules as $rule) {
            $firstPage = strpos($this->pages, $rule->priorityPage);
            $secondPage = strpos($this->pages, $rule->secondaryPage);

            if (!$rule->validate($firstPage, $secondPage)) {
                return false;
            }
        }

        return true;
    }

    public function getCenterItem(): int
    {
        $pages = explode(self::PAGE_SEPARATOR, $this->pages);

        return (int) $pages[ceil((count($pages) - 1)/2)];
    }
}
