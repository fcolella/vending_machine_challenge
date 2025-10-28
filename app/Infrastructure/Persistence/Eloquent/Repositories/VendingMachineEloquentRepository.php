<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Item;
use App\Domain\Entities\SodaItem;
use App\Domain\Entities\JuiceItem;
use App\Domain\Entities\WaterItem;
use App\Domain\Entities\VendingMachine;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use App\Domain\ValueObjects\Coin;
use App\Infrastructure\Persistence\Eloquent\Models\CoinModel;
use App\Infrastructure\Persistence\Eloquent\Models\ItemModel;
use App\Infrastructure\Persistence\Eloquent\Models\VendingMachineStateModel;
use Illuminate\Support\Collection;

class VendingMachineEloquentRepository implements VendingMachineRepositoryInterface
{
    // It is assumed that there is only one Vending Machine for simplicity
    public function find(): VendingMachine
    {
        // Init Vending Machine entity
        $machine = new VendingMachine();

        // Get all Items
        $itemModels = ItemModel::all();
        $items = collect();

        // Initialize specific Items and their qtys
        foreach($itemModels as $model){
            switch($model->name){
                case 'Water':
                    $items['Water'] = new WaterItem($model->count);
                    break;
                case 'Juice':
                    $items['Juice'] = new JuiceItem($model->count);
                    break;
                case 'Soda':
                    $items['Soda'] = new SodaItem($model->count);
                    break;
            }
        }
        // Add Items to the machine from the DB
        $machine->setAvailableItems($items);

        // Load al Coins/change
        $coinModels = CoinModel::all();
        $change = collect();

        // Initialize change with Coins
        foreach($coinModels as $model){
            for($i = 0; $i < $model->count; $i++){
                $change->push(new Coin($model->value));
            }
        }
        // Add Coins/change to the machine from the DB
        $machine->setAvailableChange($change);

        // Load Vending Machine state (inserted money, from the DB), assumed one unique machine
        $state = VendingMachineStateModel::firstOrCreate(['id' => 1], ['inserted_money' => 0.0]);
        $machine->setInsertedMoney($state->inserted_money);

        // Return the Vending Machine entity with the "loaded" state
        return $machine;
    }

    public function save(VendingMachine $machine): void
    {
        // Save Items to the DB (qtys)
        $items = $machine->getAvailableItems();
        foreach($items as $item){
            ItemModel::updateOrCreate(
                ['name' => $item->getName()],
                ['price' => $item->getPrice(), 'count' => $item->getCount()]
            );
        }

        // Save Coins/change to the DB (group by value to update quantity)
        $change = $machine->getAvailableChange()->map(fn($coin) => number_format($coin->getValue(), 2, '.', ''))->countBy()->toArray();
        foreach($change as $value => $count){
            CoinModel::updateOrCreate(
                ['value' => $value],
                ['count' => $count] // Update the quantity for current Coin value
            );
        }

        // Save Vending Machine state to the DB (inserted money), assumed 1 machine only
        VendingMachineStateModel::updateOrCreate(
            ['id' => 1],
            ['inserted_money' => $machine->getInsertedMoney()]
        );
    }
}
