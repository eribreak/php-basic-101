<?php

declare(strict_types=1);

namespace Src\OOP;

// interface
interface Flyable
{
    public function fly(): string;
    public function land(): string;
}

interface Swimmable
{
    public function swim(): string;
}

// class implement interface
class Bird implements Flyable
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    // implement tất cả methods từ interface 
    public function fly(): string
    {
        return "{$this->name} flying";
    }

    public function land(): string
    {
        return "{$this->name} landing";
    }
}

// class implement nhiều interface
class Duck implements Flyable, Swimmable
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function fly(): string
    {
        return "{$this->name} flying low";
    }

    public function land(): string
    {
        return "{$this->name} landing";
    }

    public function swim(): string
    {
        return "{$this->name} swimming";
    }
}
