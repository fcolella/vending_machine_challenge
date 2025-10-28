<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Persistence\Eloquent\Models\ItemModel;
use App\Infrastructure\Persistence\Eloquent\Models\CoinModel;

class TestVendingSeeder extends Seeder
{
    public function run(): void
    {
        // Items
        ItemModel::create(['name' => 'Water', 'price' => 0.65, 'count' => 10]);
        ItemModel::create(['name' => 'Juice', 'price' => 1.00, 'count' => 10]);
        ItemModel::create(['name' => 'Soda',  'price' => 1.50, 'count' => 10]);

        // Change
        CoinModel::create(['value' => 0.05, 'count' => 20]);
        CoinModel::create(['value' => 0.10, 'count' => 20]);
        CoinModel::create(['value' => 0.25, 'count' => 20]);
        CoinModel::create(['value' => 1.00, 'count' => 20]);
    }
}
