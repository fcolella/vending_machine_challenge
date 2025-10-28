<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendingMachineControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\InitialVendingMachineSeeder::class);
    }

    /** @test */
    public function insert_coin_endpoint(): void
    {
        $response = $this->postJson('/api/insert-coin', ['value' => 0.25]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Coin inserted']);
    }

    /** @test */
    public function vend_water_with_change(): void
    {
        $this->postJson('/api/insert-coin', ['value' => 1.00]);

        $response = $this->postJson('/api/vend-item', ['item' => 'Water']);

        $response->assertStatus(200)->assertJson(['change' => [0.25, 0.10]]);
    }

    /** @test */
    public function return_coin_endpoint(): void
    {
        $this->postJson('/api/insert-coin', ['value' => 0.10]);
        $this->postJson('/api/insert-coin', ['value' => 0.25]);

        $response = $this->postJson('/api/return-coin');

        $response->assertStatus(200)->assertJson(['coins' => [0.25, 0.10]]);
    }

    /** @test */
    public function service_restock_endpoint(): void
    {
        $payload = [
            'type' => 'item',
            'item_name' => 'Soda',
            'count' => 5
        ];
        $response = $this->postJson('/api/service/restock', $payload);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Restocked']);
    }

    /** @test */
    public function insufficient_funds_returns_400(): void
    {
        $this->postJson('/api/insert-coin', ['value' => 0.25]);

        $response = $this->postJson('/api/vend-item', ['item' => 'Soda']);
        $response->assertStatus(400)
                 ->assertJson(['error' => 'Insufficient funds for Soda']);
    }
}
