<?php

namespace App\Providers;

use App\Actions\ValidateCartStock;
use App\Contract\CartServiceInterface;
use App\Models\User;
use App\Services\RegionQueryService;
use App\Services\SessionCartService;
use App\Services\ShippingMethodService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Konfigurasi Inject
        $this->app->bind(CartServiceInterface::class, SessionCartService::class);
        $this->app->bind(RegionQueryService::class, RegionQueryService::class);
        $this->app->bind(ShippingMethodService::class, ShippingMethodService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model::unguard();
        Number::useCurrency('IDR');

        // mendaftarkan Gate yang didefinisikan dengan nama is_stock_available
        Gate::define('is_stock_available', function(User $user = null){
            try {
                // Method ini dijalankan. Jika gagal, ia akan melempar exception.
                ValidateCartStock::run();
                // Jika kita berhasil mencapai baris ini, itu artinya tidak ada
                // exception yang dilempar. Stok tersedia! Jadi, kita harus
                // secara eksplisit mengatakan "YA, DIIZINKAN".
                return true;
            } catch (ValidationException $e) {
                session()->flash('error', $e->getMessage());
                return false;
            }
        });
    }
}
