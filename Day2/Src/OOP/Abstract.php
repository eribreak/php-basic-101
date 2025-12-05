<?php

declare(strict_types=1);

namespace Src\OOP;

abstract class Vehicle
{
    protected string $brand;
    protected string $model;
    protected int $year;

    public function __construct(string $brand, string $model, int $year)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
    }

    // concrete method - đã có implementation
    public function getInfo(): string
    {
        return "{$this->year} {$this->brand} {$this->model}";
    }
    public function honk(): string
    {
        return "bíp bíp!";
    }

    //abstract method - class con cần implement
    abstract public function start(): string;
    abstract public function stop(): string;

}

class Car extends Vehicle
{
    private int $doors;

    public function __construct(string $brand, string $model, int $year, int $doors)
    {
        parent::__construct($brand, $model, $year);
        $this->doors = $doors;
    }

    //implement abstract methods
    public function start(): string
    {
        return "Xe {$this->brand} {$this->model} đã khởi động bằng kagi";
    }

    public function stop(): string
    {
        return "Xe {$this->brand} {$this->model} đã tắt";
    }

    public function getDoors(): int
    {
        return $this->doors;
    }
}

class Motorcycle extends Vehicle
{
    public function __construct(string $brand, string $model, int $year)
    {
        parent::__construct($brand, $model, $year);
    }

    //implement abstract methods
    public function start(): string
    {
        return "Xe máy {$this->brand} {$this->model} đã khởi động bằng key";
    }

    public function stop(): string
    {
        return "Xe máy {$this->brand} {$this->model} đã tắt máy";
    }
}

