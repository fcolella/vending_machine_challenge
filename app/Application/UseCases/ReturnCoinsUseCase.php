<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\VendingMachineRepositoryInterface;

class ReturnCoinUseCase
{
    private VendingMachineRepositoryInterface $repository;

    // Binding registered in app\Providers\AppServiceProvider.php
    public function __construct(VendingMachineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(): array
    {
        $machine = $this->repository->find(); // Assumed only one machine
        $returned = $machine->returnCoins(); // Remove Coins from the inserted money
        $this->repository->save($machine); // Save Vending Machine state (reset to 0.0)
        return $returned;
    }
}
