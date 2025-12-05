<?php

declare(strict_types=1);

namespace Src\OOP;

class UserClass
{
    // properties
    private string $name;
    private string $email;
    private int $age;

    // constructor
    public function __construct(string $name, string $email, int $age)
    {
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }

    // getter methods
    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    // setter methods
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // method
    public function introduce(): string
    {
        return "Hello, I am {$this->name}, {$this->age} years old. Email: {$this->email}";
    }

    // static method
    public static function createGuest(): self
    {
        return new self('Guest', 'guest@example.com', 0);
    }

    public static function createUser(string $name, string $email, int $age): static
    {
        return new static($name, $email, $age);
    }
}

