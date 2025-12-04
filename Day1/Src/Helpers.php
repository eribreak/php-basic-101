<?php

namespace Src;

class Helpers
{
    public static function filterEvenNumbers(array $numbers): array
    {
        return array_filter($numbers, static fn (int $n): bool => $n % 2 === 0);
    }

    public static function titleCase(string $text): string
    {
        $text = strtolower($text);

        return ucwords($text);
    }

    public static function truncate(string $text, int $maxLength): string
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        return substr($text, 0, $maxLength) . '...';
    }

    public static function diffInDays(string $from, string $to): int
    {
        $fromDate = new \DateTimeImmutable($from);
        $toDate   = new \DateTimeImmutable($to);

        return (int) $fromDate->diff($toDate)->format('%r%a');
    }

    public static function formatDate(string $date, string $format = 'd/m/Y'): string
    {
        $dt = new \DateTimeImmutable($date);

        return $dt->format($format);
    }

    public static function maxNumber(array $numbers): ?int
    {
        if ($numbers === []) {
            return null;
        }

        return max($numbers);
    }
}


