<?php

namespace App\Domain\ValueObjects;

class Coin
{
    private float $_value;
    private const VALID_VALUES = [1.00, 0.25, 0.10, 0.05];

    public function __construct(float $value)
    {
        if(!in_array($value, self::VALID_VALUES)){
            throw new \InvalidArgumentException("Invalid coin value: $value");
        }
        $this->_value = $value;
    }

    public static function getValidValues(): array
    {
        return self::VALID_VALUES;
    }

    public function getValue(): float
    {
        return $this->_value;
    }
}
