<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\VendingMachineRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\VendingMachineEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the VendingMachineRepositoryInterface to the Eloquent implementation
        $this->app->bind(VendingMachineRepositoryInterface::class, VendingMachineEloquentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
