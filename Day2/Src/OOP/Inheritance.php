<?php

declare(strict_types=1);

namespace Src\OOP;

// base class
class Animal
{
    protected string $name;
    protected string $species;

    public function __construct(string $name, string $species)
    {
        $this->name = $name;
        $this->species = $species;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function makeSound(): string
    {
        return "{$this->name} barking";
    }

    public function eat(): string
    {
        return "{$this->name} eating";
    }
}

// child class
class Dog extends Animal
{
    private string $breed;

    public function __construct(string $name, string $breed)
    {
        parent::__construct($name, 'Dog');
        $this->breed = $breed;
    }

    // override method
    public function makeSound(): string
    {
        return "{$this->name} barking: Woof woof!";
    }

    // method riêng
    public function fetch(): string
    {
        return "{$this->name} running to fetch the ball";
    }

    public function getBreed(): string
    {
        return $this->breed;
    }

    public function eat(): string
    {
        return parent::eat() . " {$this->breed}";
    }
}

// child class
class Cat extends Animal
{
    public function __construct(string $name)
    {
        parent::__construct($name, 'Cat');
    }

    // override method
    public function makeSound(): string
    {
        return "{$this->name} meowing: Meow meow!";
    }

    // method riêng
    public function climb(): string
    {
        return "{$this->name} climbing the column";
    }
}

