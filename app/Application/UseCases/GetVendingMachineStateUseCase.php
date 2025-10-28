<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\VendingMachineRepositoryInterface;

class GetVendingMachineStateUseCase
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

        // Format Items
        $items = $machine->getAvailableItems()
                ->map(fn($item, $name) => [
                    'name'  => $name,
                    'price' => $item->getPrice(),
                    'stock' => $item->getCount()
                ])->values()->toArray();

        // Format change, force 2 decimals to avoid wrong group/counts
        $change = $machine->getAvailableChange()->map(fn($coin) => number_format($coin->getValue(), 2, '.', ''))->countBy()->toArray();

        return [
            'items' => $items,
            'change' => $change,
            'balance' => $machine->getInsertedMoney()
        ];
    }
}
