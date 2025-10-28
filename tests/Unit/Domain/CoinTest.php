<?php

namespace Tests\Unit\Domain;

use App\Domain\ValueObjects\Coin;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    public function test_valid_coin_values_are_accepted(): void
    {
        $valid = [0.05, 0.10, 0.25, 1.00];
        foreach ($valid as $v) {
            $coin = new Coin($v);
            $this->assertSame($v, $coin->getValue());
        }
    }

    public function test_invalid_coin_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Coin(0.07);
    }
}
