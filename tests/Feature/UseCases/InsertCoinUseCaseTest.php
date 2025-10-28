<?php

namespace Tests\Feature\UseCases;

use App\Application\UseCases\InsertCoinUseCase;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use Tests\Feature\FeatureTestCase;

class InsertCoinUseCaseTest extends FeatureTestCase
{
    /** @test */
    public function it_inserts_coin_and_persists(): void
    {
        $repo = $this->app->make(VendingMachineRepositoryInterface::class);
        $useCase = new InsertCoinUseCase($repo);

        $useCase->execute(0.10);

        $this->assertEquals(0.10, $repo->find()->getInsertedMoney());
    }
}
