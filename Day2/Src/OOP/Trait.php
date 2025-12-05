<?php

declare(strict_types=1);

namespace Src\OOP;


// trait
trait Logger
{
    protected array $logs = [];

    public function log(string $message): void
    {
        $this->logs[] = date('Y-m-d H:i:s') . ' - ' . $message;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}

trait Timestamps
{
    protected ?string $createdAt = null;
    protected ?string $updatedAt = null;

    public function setCreatedAt(string $date): void
    {
        $this->createdAt = $date;
    }

    public function setUpdatedAt(string $date): void
    {
        $this->updatedAt = $date;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
}

trait Validator
{
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validateRequired(string $value): bool
    {
        return !empty(trim($value));
    }

    public function validateLength(string $value, int $min, int $max): bool
    {
        $length = strlen($value);
        return $length >= $min && $length <= $max;
    }
}

// class using multiple traits
class UserWithTraits
{
    use Logger, Timestamps, Validator;

    private string $name;
    private string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->setCreatedAt(date('Y-m-d H:i:s'));
        $this->log("User {$name} created");
    }

    public function setName(string $name): bool
    {
        if (!$this->validateRequired($name)) {
            $this->log("Name is required");
            return false;
        }

        if (!$this->validateLength($name, 2, 50)) {
            $this->log("2 to 50 characters");
            return false;
        }

        $this->name = $name;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->log("Name updated to: {$name}");
        return true;
    }

    public function setEmail(string $email): bool
    {
        if (!$this->validateEmail($email)) {
            $this->log("Invalid email: {$email}");
            return false;
        }

        $this->email = $email;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->log("Email updated to: {$email}");
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

// class using multiple traits
class Product
{
    use Logger, Timestamps;

    private string $name;
    private float $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
        $this->setCreatedAt(date('Y-m-d H:i:s'));
        $this->log("Product {$name}: {$price}");
    }

    public function updatePrice(float $newPrice): void
    {
        $oldPrice = $this->price;
        $this->price = $newPrice;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->log("Product {$this->name} from {$oldPrice} to {$newPrice}");
    }
}

