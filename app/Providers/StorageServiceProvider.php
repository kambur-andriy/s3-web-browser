<?php

namespace App\Providers;


use App\Models\StorageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;


class StorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

	    App::bind(StorageService::class, function ($app) {

		    $storage = Storage::disk('s3');

		    return new StorageService($storage);

	    });

    }
}
