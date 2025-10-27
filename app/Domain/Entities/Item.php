<?php

namespace App\Domain\Entities;

abstract class Item
{
    protected string $name;
    protected float $price;
    protected int $count;

    public function __construct(string $name, float $price, int $count = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->count = $count;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function decrementCount(): void
    {
        if ($this->count > 0) {
            $this->count--;
        }
    }

    public function incrementCount(int $amount): void
    {
        $this->count += $amount;
    }
}
