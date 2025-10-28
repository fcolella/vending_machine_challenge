<?php

namespace Tests\Feature\Repository;

use App\Domain\Entities\VendingMachine;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use App\Domain\ValueObjects\Coin;
use Tests\Feature\FeatureTestCase;

class VendingMachineEloquentRepositoryTest extends FeatureTestCase
{
    private VendingMachineRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(VendingMachineRepositoryInterface::class);
    }

    /** @test */
    public function it_loads_initial_state_from_db(): void
    {
        $machine = $this->repo->find();

        $this->assertEquals(0.0, $machine->getInsertedMoney());
        $this->assertEquals(10, $machine->getAvailableItems()['Water']->getCount());
        $this->assertEquals(20, $machine->getAvailableChange()
            ->filter(fn($c) => $c->getValue() === 0.25)->count());
    }

    /** @test */
    public function it_saves_machine_state(): void
    {
        $machine = $this->repo->find();
        $machine->insertCoin(new Coin(0.25));
        $machine->addItem('Water', 5);

        $this->repo->save($machine);

        $fresh = $this->repo->find();
        $this->assertEquals(0.25, $fresh->getInsertedMoney());
        $this->assertEquals(15, $fresh->getAvailableItems()['Water']->getCount());
    }
}
