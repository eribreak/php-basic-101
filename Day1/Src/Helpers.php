<?php

namespace Src;

class Helpers
{
    // các hàm xử lý mảng (array)

    public static function filterEvenNumbers(array $numbers): array
    {
        return array_filter($numbers, static fn (int $n): bool => $n % 2 === 0);
    }

    public static function squareNumbers(array $numbers): array
    {
        // áp dụng 1 callback lên từng phần tử của mảng
        return array_map(static fn (int $n): int => $n * $n, $numbers);
    }

    // cộng tất cả phần tử trong mảng
    public static function sumNumbers(array $numbers): int
    {
        return array_sum($numbers);
    }

    // sắp xếp tại chỗ, nhỏ -> lớn
    public static function sortAscending(array $numbers): array
    { 
        return sort($numbers);;
    }

    // sắp xếp tại chỗ, lớn -> nhỏ
    public static function sortDescending(array $numbers): array
    {
        return rsort($numbers);
    }

    // loại bỏ phần tử trùng nhau
    public static function uniqueValues(array $values): array
    {
        return array_values(array_unique($values));
    }

    // kiểm tra phần tử có tồn tại trong mảng hay không
    public static function hasValue(array $values, mixed $needle): bool
    {
        return in_array($needle, $values, true);
    }

    // Các hàm xử lý chuỗi (string)

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

    public static function toUpper(string $text): string
    {
        return strtoupper($text);
    }

    public static function replace(string $text, string $search, string $replace): string
    {
        return str_replace($search, $replace, $text);
    }

    public static function splitByComma(string $text): array
    {
        $parts = array_map('trim', explode(',', $text));

        return array_filter($parts, static fn (string $part): bool => $part !== '');
    }

    public static function joinWithComma(array $parts): string
    {
        return implode(',', $parts);
    }

    // các hàm xử lý ngày tháng

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


