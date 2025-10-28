<?php

namespace Tests\Feature\UseCases;

use App\Application\UseCases\InsertCoinUseCase;
use App\Application\UseCases\ReturnCoinsUseCase;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use Tests\Feature\FeatureTestCase;

class ReturnCoinUseCaseTest extends FeatureTestCase
{
    /** @test */
    public function it_returns_inserted_coins(): void
    {
        $repo = $this->app->make(VendingMachineRepositoryInterface::class);

        $insert = new InsertCoinUseCase($repo);
        $insert->execute(0.25);
        $insert->execute(0.10);

        $return = new ReturnCoinsUseCase($repo);
        $coins = $return->execute();

        $this->assertEquals([0.25, 0.10], $coins);
        $this->assertEquals(0.0, $repo->find()->getInsertedMoney());
    }
}
