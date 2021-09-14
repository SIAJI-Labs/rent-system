<?php

namespace App\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class GlobalViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $settings = null;
        View::composer('*', function ($view) use ($settings) {
            $wtitle = $wdesc = $wlogo = $wfavicon = null;
            $wadminAmount = 0;

            // Append W-Value
            $wtitle = "Rent System";
            $wdesc = "Just an Open Source Rent System";
            $wfavicon = null;
            $wlogo = null;

            if (Schema::hasTable('website_configurations')) {
                $keys = [
                    'title', 'description', 'favicon', 'logo'
                ];
                foreach($keys as $item){
                    $data = \App\Models\WebsiteConfiguration::where('key', $item)->first();
                    if(!empty($data)){
                        switch($item){
                            case 'title':
                                $wtitle = $data->value;
                                break;
                            case 'description':
                                $wdesc = $data->value;
                                break;
                            case 'favicon':
                                $wfavicon = $data->value;
                                break;
                            case 'title':
                                $wlogo = $data->value;
                                break;
                        }
                    }
                }
            }

            $view->with([
                'wtitle' => $wtitle,
                'wdesc' => $wdesc,
                'wfavicon' => $wfavicon,
                'wlogo' => $wlogo,
            ]);
        });
    }
}
