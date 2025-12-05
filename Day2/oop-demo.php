<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Src\OOP\UserClass;
use Src\OOP\Dog;
use Src\OOP\Cat;
use Src\OOP\Bird;
use Src\OOP\Duck;
use Src\OOP\Car;
use Src\OOP\Motorcycle;
use Src\OOP\UserWithTraits;
use Src\OOP\Product;

echo "=== DEMO OOP CONCEPTS ===\n\n";

// 1. CLASS & OBJECT
echo "1. CLASS & OBJECT\n";
echo str_repeat("-", 50) . "\n";

// create object from class
$user1 = new UserClass('Nguyễn Văn A', 'a@example.com', 25);
echo $user1->introduce() . "\n";

// use static method
$guest = UserClass::createGuest();
echo $guest->introduce() . "\n";

// change property via setter
$user1->setName('Nguyễn Văn B');
echo "After changing name: " . $user1->getName() . "\n\n";

// 2. INHERITANCE
echo "2. INHERITANCE \n";
echo str_repeat("-", 50) . "\n";

$dog = new Dog('Lucky', 'Golden Retriever');
echo $dog->makeSound() . "\n"; 
echo $dog->eat() . "\n"; 
echo $dog->fetch() . "\n";  
echo "Breed: " . $dog->getBreed() . "\n\n";
echo $dog->eat() . "\n";

$cat = new Cat('Mimi');
echo $cat->makeSound() . "\n"; 
echo $cat->climb() . "\n"; 
echo $cat->eat() . "\n\n"; 

// 3. INTERFACE
echo "3. INTERFACE\n";
echo str_repeat("-", 50) . "\n";

$bird = new Bird('Sparrow');
echo $bird->fly() . "\n";
echo $bird->land() . "\n\n";

$duck = new Duck('Donald Trump');
echo $duck->fly() . "\n";
echo $duck->swim() . "\n"; 
echo $duck->land() . "\n\n";

// 4. ABSTRACT CLASS
echo "4. ABSTRACT CLASS\n";
echo str_repeat("-", 50) . "\n";

$car = new Car('Toyota', 'Camry', 2023, 4);
echo $car->getInfo() . "\n"; 
echo $car->start() . "\n"; 
echo $car->stop() . "\n";
echo $car->honk() . "\n"; 
echo "Doors: " . $car->getDoors() . "\n\n";

$motorcycle = new Motorcycle('Honda', 'Wave', 2022);
echo $motorcycle->getInfo() . "\n";
echo $motorcycle->start() . "\n";
echo $motorcycle->stop() . "\n\n";

// 5. TRAIT
echo "5. TRAIT\n";
echo str_repeat("-", 50) . "\n";

$user = new UserWithTraits('Trần Văn C', 'c@example.com');
echo "Name: " . $user->getName() . "\n";
echo "Email: " . $user->getEmail() . "\n";
echo "Created At: " . $user->getCreatedAt() . "\n\n";

// use trait methods
$user->setName('Trần Văn D');
echo "After changing name: " . $user->getName() . "\n";
echo "Updated At: " . $user->getUpdatedAt() . "\n\n";

// view logs from Logger trait
echo "Logs:\n";
foreach ($user->getLogs() as $log) {
    echo "  - $log\n";
}
echo "\n";

// Validation from Validator trait
echo "Validation:\n";
echo "Email valid: " . ($user->validateEmail('test@example.com') ? "Yes" : "No") . "\n";
echo "Email invalid: " . ($user->validateEmail('invalid') ? "Yes" : "No") . "\n\n";


$product = new Product('Laptop', 15000000);
$product->updatePrice(14000000);
echo "Logs of Product:\n";
foreach ($product->getLogs() as $log) {
    echo "  - $log\n";
}
echo "\n";

echo "=== END DEMO ===\n";

