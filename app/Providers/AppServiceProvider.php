<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\PaymentRecord;
use App\Observers\AuditLogObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register audit log observer
        Room::observe(AuditLogObserver::class);
        Tenant::observe(AuditLogObserver::class);
        Lease::observe(AuditLogObserver::class);
        PaymentRecord::observe(AuditLogObserver::class);
    }
}
