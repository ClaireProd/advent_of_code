<?php

class Update
{
    private const PAGE_SEPARATOR = ',';
    public array $pages;
    public function __construct(string $pages)
    {
        $this->pages = array_map('intval', explode(self::PAGE_SEPARATOR, $pages));
    }

    public function isValid(array $rules): bool
    {
        foreach ($rules as $rule) {
            $firstPage = strpos(implode(self::PAGE_SEPARATOR, $this->pages), $rule->priorityPage);
            $secondPage = strpos(implode(self::PAGE_SEPARATOR, $this->pages), $rule->secondaryPage);

            if (!$rule->validate($firstPage, $secondPage)) {
                return false;
            }
        }

        return true;
    }

    public function getCenterItem(): int
    {
        $pages = $this->pages;

        return (int) $pages[ceil((count($pages) - 1) / 2)];
    }

    public function orderPages(array $dependencies, array $base = []): void
    {
        $dependencies = $this->filterDependencies($dependencies);

        while (!empty($this->pages)) {
            $processed = false;

            foreach ($this->pages as $key => $page) {
                if (empty($dependencies[$page]['after'] ?? null)) {
                    $base[] = $page;
                    unset($this->pages[$key]);
                    $dependencies = $this->unsetInDependencies($dependencies, $page);
                    $processed = true;
                } else {
                }
            }

            if (!$processed) {
                return;
            }
        }

        $this->pages = $base;
    }



    public function unsetInDependencies(array $dependencies, int $unsetItem): array
    {
        foreach ($dependencies as $key => &$subArray) {
            if (isset($subArray['after'])) {
                $subArray['after'] = array_filter($subArray['after'], fn($value) => $value !== $unsetItem);
            }

            if (empty($subArray['after'])) {
                unset($dependencies[$key]['after']);
            }
        }

        unset($dependencies[$unsetItem]);

        return $dependencies;
    }

    private function filterDependencies(array $dependencies): array
    {
        return array_filter($dependencies, function ($dependency, $key)  {
            if (!in_array($key, $this->pages, true)) {
                return false;
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
