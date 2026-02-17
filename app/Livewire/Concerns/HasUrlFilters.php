<?php

namespace App\Livewire\Concerns;

/**
 * Reusable trait for Livewire components that need advanced URL filtering.
 * Provides helpers for parsing and validating filter params from query string.
 */
trait HasUrlFilters
{
    /**
     * Parse comma-separated IDs from URL param to array of integers.
     */
    protected function parseIdParam(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(
            array_map('intval', explode(',', (string) $value)),
            fn (int $id) => $id > 0
        ));
    }

    /**
     * Parse single ID from URL param.
     */
    protected function parseSingleIdParam(?string $value): ?int
    {
        $ids = $this->parseIdParam($value);

        return $ids[0] ?? null;
    }

    /**
     * Validate and return a value from allowed options.
     */
    protected function validateEnumParam(?string $value, array $allowed, string $default): string
    {
        if (empty($value) || !in_array($value, $allowed, true)) {
            return $default;
        }

        return $value;
    }
}
