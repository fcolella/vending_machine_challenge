<?php

namespace App\Domain\Entities;

class JuiceItem extends Item
{
    public function __construct(int $count = 0)
    {
        parent::__construct('Juice', 1.00, $count);
    }
}
