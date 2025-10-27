<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Coin;
use Illuminate\Support\Collection;

class VendingMachine
{
    private float $insertedMoney = 0.0;
    private Collection $availableItems; // Collection of Items: Water, Juice, Soda
    private Collection $availableChange; // Collection of Coin objects

    public function __construct()
    {
        $this->availableItems = collect();
        $this->availableChange = collect();

        // Init items with no count
        $this->availableItems['Water'] = new WaterItem();
        $this->availableItems['Juice'] = new JuiceItem();
        $this->availableItems['Soda'] = new SodaItem();
    }

    public function insertCoin(Coin $coin): void
    {
        $this->insertedMoney += $coin->getValue();
    }

    public function getInsertedMoney(): float
    {
        return $this->insertedMoney;
    }

    public function returnCoins(): array
    {
        $returned = $this->calculateChange();
        $this->insertedMoney = 0.0;
        return $returned;
    }

    public function vendItem(string $itemName): array
    {
        // Check the specific Item exists
        if(!$this->availableItems->has($itemName)){
            throw new \InvalidArgumentException("Invalid item: $itemName");
        }

        // Get the specific Item
        $item = $this->availableItems[$itemName];

        // Check Item's stock
        if($item->getCount() <= 0){
            throw new \Exception("Out of stock: $itemName");
        }

        // Check funds are enough to vend the Item
        if($this->insertedMoney < $item->getPrice()){
            throw new \Exception("Insufficient funds for $itemName");
        }

        // Start vending process
        $item->decrementCount(); // Decrease item count by one
        $changeAmount = $this->insertedMoney - $item->getPrice(); // Get change amount
        $change = $this->calculateChange($changeAmount); // Calculate change
        $this->insertedMoney = 0.0; // Reset inserted money as it will always return the exceeded amount

        // Return all operation related data
        return [$itemName, ...$change];
    }

    private function calculateChange(): array
    {
        $amount = $this->insertedMoney;
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
        return $this->availableChange->filter(fn($c) => $c->getValue() === $value)->count() > 0;
    }

    private function decrementChangeCoin(float $value): void
    {
        $index = $this->availableChange->search(fn($c) => $c->getValue() === $value);
        if ($index !== false) {
            $this->availableChange->forget($index);
        }
    }

    // Service
    public function addItem(string $itemName, int $count): void
    {
        // Items are initialized in constructor, increment count
        if($this->availableItems->has($itemName)){
            $this->availableItems[$itemName]->incrementCount($count);
        }
    }

    public function addChange(Coin $coin, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->availableChange->push($coin);
        }
    }

    // Getters
    public function getAvailableItems(): Collection
    {
        return $this->availableItems;
    }

    public function getAvailableChange(): Collection
    {
        return $this->availableChange;
    }

    // Setters
    public function setAvailableItems(Collection $items): void
    {
        $this->availableItems = $items;
    }

    public function setAvailableChange(Collection $change): void
    {
        $this->availableChange = $change;
    }
}
