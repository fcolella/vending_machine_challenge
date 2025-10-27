<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Coin;
use Illuminate\Support\Collection;

class VendingMachine
{
    private float $_insertedMoney = 0.0;
    private Collection $_availableItems; // Collection of Items: Water, Juice, Soda
    private Collection $_availableChange; // Collection of Coin objects

    public function __construct()
    {
        $this->_availableItems = collect();
        $this->_availableChange = collect();

        // Init items with no count
        $this->_availableItems['Water'] = new WaterItem();
        $this->_availableItems['Juice'] = new JuiceItem();
        $this->_availableItems['Soda'] = new SodaItem();
    }

    public function insertCoin(Coin $coin): void
    {
        $this->_insertedMoney += $coin->getValue();
    }

    public function getInsertedMoney(): float
    {
        return $this->_insertedMoney;
    }

    public function returnCoins(): array
    {
        $returned = $this->calculateChange();
        $this->_insertedMoney = 0.0;
        return $returned;
    }

    private function calculateChange(): array
    {
        $amount = $this->_insertedMoney;
        $coins = Coin::getValidValues();
        $change = [];
        foreach($coins as $coinValue){
            while($amount >= $coinValue && $this->hasChangeCoin($coinValue)){ // amount inserted is more than current coin value and there are coins of this value available
                $change[] = $coinValue; // Add one of these coins to change
                $amount -= $coinValue; // Decrease amount left to return
                $this->decrementChangeCoin($coinValue); // Remove one of these coins from available change
            }
        }

        // Alert if machine is unable to return change in full
        if($amount > 0){
            throw new \Exception("Insufficient change available");
        }

        return $change;
    }

    private function hasChangeCoin(float $value): bool
    {
        return $this->_availableChange->filter(fn($c) => $c->getValue() === $value)->count() > 0;
    }

    private function decrementChangeCoin(float $value): void
    {
        $index = $this->_availableChange->search(fn($c) => $c->getValue() === $value);
        if ($index !== false) {
            $this->_availableChange->forget($index);
        }
    }

    public function vendItem(string $itemName): array
    {
        // Check the specific Item exists
        if(!$this->_availableItems->has($itemName)){
            throw new \InvalidArgumentException("Invalid item: $itemName");
        }

        // Get the specific Item
        $item = $this->_availableItems[$itemName];

        // Check Item's qty
        if($item->getCount() <= 0){
            throw new \Exception("Out of stock: $itemName");
        }

        // Check funds are enough to vend the Item
        if($this->_insertedMoney < $item->getPrice()){
            throw new \Exception("Insufficient funds for $itemName");
        }

        // Start vending process
        $item->decrementCount(); // Decrease specific item's count by one
        $changeAmount = $this->_insertedMoney - $item->getPrice(); // Get change amount
        $change = $this->calculateChange($changeAmount); // Calculate Coins/change
        $this->_insertedMoney = 0.0; // Reset inserted money as it will always return the exceeded amount to remain at 0.0

        // Return all operation related data
        return [$itemName, ...$change];
    }

    // Service
    public function addItem(string $itemName, int $count): void
    {
        // Items are initialized in constructor, increment count
        if($this->_availableItems->has($itemName)){
            $this->_availableItems[$itemName]->incrementCount($count);
        }
    }

    public function addChange(Coin $coin, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->_availableChange->push($coin);
        }
    }

    // Getters
    public function getAvailableItems(): Collection
    {
        return $this->_availableItems;
    }

    public function getAvailableChange(): Collection
    {
        return $this->_availableChange;
    }

    // Setters
    public function setInsertedMoney(float $inserted_money): void
    {
        $this->_insertedMoney = $$inserted_money;
    }

    public function setAvailableItems(Collection $items): void
    {
        $this->_availableItems = $items;
    }

    public function setAvailableChange(Collection $change): void
    {
        $this->_availableChange = $change;
    }
}
