<?php

require_once 'Report.php';

class Reports
{
    public array $reports;
    public const MIN_STEP = 1;
    public const MAX_STEP = 3;
    public const TOLERANCE = 1;

    public function __construct(array $reports)
    {
        $this->reports = array_map(function (array $report) {
            return new Report($report);
        }, $reports);
    }

    public function safe(): array
    {
        return array_filter($this->reports, function (Report $report) {
            return $report->checkSafe();
        });
    }

    public static function inRange(int $step): bool
    {
        return abs($step) >= Reports::MIN_STEP && abs($step) <= Reports::MAX_STEP;
    }
}