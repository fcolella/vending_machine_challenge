<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\VendingMachineRepositoryInterface;

class VendItemUseCase
{
    private VendingMachineRepositoryInterface $repository;

    // Binding registered in app\Providers\AppServiceProvider.php
    public function __construct(VendingMachineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $itemName): array
    {
        $machine = $this->repository->find(); // Assumed only one machine
        $result = $machine->vendItem($itemName); // Vend the Item
        $this->repository->save($machine); // Save Vending Machine state (added inserted money, decreased specific Item's qty)
        return $result;
    }
}
