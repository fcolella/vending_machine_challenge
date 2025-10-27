<?php

namespace App\Domain\Entities;

class WaterItem extends Item
{
    public function __construct(int $count = 0)
    {
        parent::__construct('Water', 0.65, $count);
    }
}
