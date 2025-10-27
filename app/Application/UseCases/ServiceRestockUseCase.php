<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\VendingMachineRepositoryInterface;
use App\Domain\ValueObjects\Coin;

class ServiceRestockUseCase
{
    private VendingMachineRepositoryInterface $repository;

    // Binding registered in app\Providers\AppServiceProvider.php
    public function __construct(VendingMachineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addItem(string $itemName, int $count): void
    {
        $machine = $this->repository->find(); // Assumed only one machine
        $machine->addItem($itemName, $count); // Add specific Item qty
        $this->repository->save($machine); // Persist new Item qty
    }

    public function addChange(float $value, int $count): void
    {
        $machine = $this->repository->find(); // Assumed only one machine
        $coin = new Coin($value); // Init Value Object with a given float (validated in the VO)
        $machine->addChange($coin, $count); // Add specific Coin qty
        $this->repository->save($machine); // Persist new Coin qty
    }
}
