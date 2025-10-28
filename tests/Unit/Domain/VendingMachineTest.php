<?php

namespace Tests\Unit\Domain;

use App\Domain\Entities\VendingMachine;
use App\Domain\ValueObjects\Coin;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private VendingMachine $machine;

    protected function setUp(): void
    {
        $this->machine = new VendingMachine();
        // start with 10 of each coin for change
        $this->addChange(0.05, 10);
        $this->addChange(0.10, 10);
        $this->addChange(0.25, 10);
        $this->addChange(1.00, 10);
    }

    private function addChange(float $value, int $count): void
    {
        $coin = new Coin($value);
        for ($i = 0; $i < $count; $i++) {
            $this->machine->addChange($coin, 1);
        }
    }

    /** @test */
    public function insert_coin_increases_inserted_money(): void
    {
        $this->machine->insertCoin(new Coin(0.25));
        $this->assertEquals(0.25, $this->machine->getInsertedMoney());
    }

    /** @test */
    public function vend_water_with_exact_change(): void
    {
        $this->machine->insertCoin(new Coin(0.25));
        $this->machine->insertCoin(new Coin(0.25));
        $this->machine->insertCoin(new Coin(0.10));
        $this->machine->insertCoin(new Coin(0.05));

        $result = $this->machine->vendItem('Water');

        $this->assertEquals([], $result); // no change
        $this->assertEquals(0.0, $this->machine->getInsertedMoney());
        $this->assertEquals(9, $this->machine->getAvailableItems()['Water']->getCount());
    }

    /** @test */
    public function vend_soda_with_change(): void
    {
        $this->machine->insertCoin(new Coin(1.00));
        $this->machine->insertCoin(new Coin(1.00));

        $result = $this->machine->vendItem('Soda');

        $this->assertEquals([0.25, 0.25], $result); // only change
        $this->assertEquals(0.0, $this->machine->getInsertedMoney());
    }

    /** @test */
    public function return_coins_gives_back_inserted_money(): void
    {
        $this->machine->insertCoin(new Coin(0.10));
        $this->machine->insertCoin(new Coin(0.25));

        $returned = $this->machine->returnCoins();

        $this->assertEquals([0.25, 0.10], $returned); // greedy order
        $this->assertEquals(0.0, $this->machine->getInsertedMoney());
    }

    /** @test */
    public function insufficient_funds_throws_exception(): void
    {
        $this->expectExceptionMessage('Insufficient funds');
        $this->machine->insertCoin(new Coin(0.25));
        $this->machine->vendItem('Soda');
    }

    /** @test */
    public function out_of_stock_throws_exception(): void
    {
        // remove all Water
        $items = $this->machine->getAvailableItems();
        $items['Water']->incrementCount(-10);
        $this->machine->setAvailableItems($items);

        $this->machine->insertCoin(new Coin(1.00));
        $this->expectExceptionMessage('Out of stock');
        $this->machine->vendItem('Water');
    }
}
