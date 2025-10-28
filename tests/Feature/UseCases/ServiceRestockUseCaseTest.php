<?php

namespace Tests\Feature\UseCases;

use App\Application\UseCases\ServiceRestockUseCase;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use Tests\Feature\FeatureTestCase;

class ServiceRestockUseCaseTest extends FeatureTestCase
{
    /** @test */
    public function it_restocks_items_and_change(): void
    {
        $repo = $this->app->make(VendingMachineRepositoryInterface::class);
        $useCase = new ServiceRestockUseCase($repo);

        $useCase->addItem('Juice', 7);
        $useCase->addChange(0.05, 15);

        $machine = $repo->find();
        $this->assertEquals(17, $machine->getAvailableItems()['Juice']->getCount());
        $this->assertEquals(35, $machine->getAvailableChange()
            ->filter(fn($c) => $c->getValue() === 0.05)->count());
    }
}
