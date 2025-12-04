<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Src\Greeting;
use Src\Helpers;

$greeter = new Greeting();
echo $greeter->sayHello('Emilie') . PHP_EOL;

$numbers = [1, 2, 3, 4, 5, 6];
echo 'Even numbers: ' . implode(', ', Helpers::filterEvenNumbers($numbers)) . PHP_EOL;

$text = 'hoc php basic';
echo 'Title case: ' . Helpers::titleCase($text) . PHP_EOL;

echo 'Truncate: ' . Helpers::truncate('Hello, this is a very very long string', 20) . PHP_EOL;

echo 'Diff in days: ' . Helpers::diffInDays('2025-01-01', '2025-01-10') . PHP_EOL;
echo 'Formatted date: ' . Helpers::formatDate('2025-02-15') . PHP_EOL;

echo 'Max number: ' . Helpers::maxNumber($numbers) . PHP_EOL;
