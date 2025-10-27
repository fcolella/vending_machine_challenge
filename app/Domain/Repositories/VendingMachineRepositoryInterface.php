<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\VendingMachine;

interface VendingMachineRepositoryInterface
{
    public function find(): VendingMachine; // There will only be one machine for simplicity
    public function save(VendingMachine $machine): void;
}
