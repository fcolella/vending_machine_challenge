<?php

namespace App\Domain\Entities;

class SodaItem extends Item
{
    public function __construct(int $count = 0)
    {
        parent::__construct('Soda', 1.50, $count);
    }
}
