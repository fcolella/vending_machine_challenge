<?php

namespace Tests\Feature\UseCases;

use App\Application\UseCases\InsertCoinUseCase;
use App\Application\UseCases\VendItemUseCase;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use Tests\Feature\FeatureTestCase;

class VendItemUseCaseTest extends FeatureTestCase
{
    /** @test */
    public function it_vends_water_and_gives_change(): void
    {
        $repo = $this->app->make(VendingMachineRepositoryInterface::class);

        $insert = new InsertCoinUseCase($repo);
        $insert->execute(1.00);

        $vend = new VendItemUseCase($repo);
        $result = $vend->execute('Water');

        $this->assertEquals([0.25, 0.10], $result); // only change
        $this->assertEquals(0.0, $repo->find()->getInsertedMoney());
    }
}
