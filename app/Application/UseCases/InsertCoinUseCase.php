<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\VendingMachineRepositoryInterface;
use App\Domain\ValueObjects\Coin;

class InsertCoinUseCase
{
    private VendingMachineRepositoryInterface $repository;

    // Binding registered in app\Providers\AppServiceProvider.php
    public function __construct(VendingMachineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(float $value): void
    {
        $machine = $this->repository->find(); // Assumed only one machine
        $coin = new Coin($value); // Init Value Object with a given float (validated in the VO)
        $machine->insertCoin($coin);
        $this->repository->save($machine);
    }
}
