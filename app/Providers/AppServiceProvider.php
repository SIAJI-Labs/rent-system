<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \OwenIt\Auditing\Models\Audit::creating(function (\OwenIt\Auditing\Models\Audit $model) {
            if (empty($model->old_values) && empty($model->new_values)) {
                return false;
            }
        });
    }
}
