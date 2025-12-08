<?php

// khai báo strict types: mọi type hint kiểm tra chặt chẽ
declare(strict_types=1);

// composer autoload: tự động load các class theo chuẩn PSR-4 được khai báo trong composer.json
require __DIR__ . '/vendor/autoload.php';

use Src\Greeting;
use Src\Helpers;

// biến, hằng số, kiểu dữ liệu

$name = 'Emilie';          // string
$age  = 25;                // int
$isStudent = true;         // bool
$height = 1.65;            // float

define('APP_ENV', 'local'); // hằng số 

echo 'Name: ' . $name . PHP_EOL;
echo 'Age: ' . $age . PHP_EOL;
echo 'Is student: ' . ($isStudent ? 'yes' : 'no') . PHP_EOL;
echo 'Height: ' . $height . PHP_EOL;
echo 'Env: ' . APP_ENV . PHP_EOL;

// câu điều kiện & vòng lặp

if ($age >= 18) {
    echo "Bạn đã đủ 18+.\n";
} elseif ($age >= 13) {
    echo "Bạn là teenager.\n";
} else {
    echo "Bạn là kiddo.\n";
}

// vòng lặp for
for ($i = 1; $i <= 3; $i++) {
    echo "Tach gacha i = {$i}\n";
}

// vòng lặp foreach cho mảng
$languages = ['PHP', 'JavaScript', 'Python'];
foreach ($languages as $index => $lang) {
    echo "lang #{$index}: {$lang}\n";
}

// function & OOP cơ bản

// class Greeting: tên class dạng PascalCase 
$greeter = new Greeting();
echo $greeter->sayHello($name) . PHP_EOL;

// làm việc với mảng (array)

// mảng tuần tự
$numbers = [1, 2, 3, 4, 5, 6];

// mảng kết hợp 
$user = [
    'name' => 'Emilie',
    'email' => 'emilie@example.com',
    'role' => 'admin',
];

echo 'User name: ' . $user['name'] . PHP_EOL;

echo 'Even numbers: ' . implode(', ', Helpers::filterEvenNumbers($numbers)) . PHP_EOL;
echo 'Squared numbers: ' . implode(', ', Helpers::squareNumbers($numbers)) . PHP_EOL;
echo 'Sum numbers: ' . Helpers::sumNumbers($numbers) . PHP_EOL;

// làm việc với chuỗi (string)

$text = 'hoc php basic';
echo 'Title case: ' . Helpers::titleCase($text) . PHP_EOL;

echo 'Uppercase: ' . Helpers::toUpper($text) . PHP_EOL;
echo 'Replace "php" by "PHP 8": ' . Helpers::replace($text, 'php', 'PHP 8') . PHP_EOL;

echo 'Truncate: ' . Helpers::truncate('Hello, this is a very very long string', 20) . PHP_EOL;

// tách chuỗi thành mảng & ngược lại
$csv = 'php,js,python';
$parts = Helpers::splitByComma($csv);
echo 'Split by comma: ' . implode(' | ', $parts) . PHP_EOL;
echo 'Join with comma: ' . Helpers::joinWithComma($parts) . PHP_EOL;

// date time & helper khác 


echo 'Diff in days: ' . Helpers::diffInDays('2025-01-01', '2025-01-10') . PHP_EOL;
echo 'Formatted date: ' . Helpers::formatDate('2025-02-15') . PHP_EOL;

echo 'Max number: ' . Helpers::maxNumber($numbers) . PHP_EOL;



echo 'Time remote: ' . Helpers::timeRemote('Monday') . PHP_EOL;
try {
    echo 'Time remote: ' . Helpers::timeRemote('ABC') . PHP_EOL;
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}