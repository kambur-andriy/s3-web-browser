<?php

namespace App\Providers;


use App\Models\DB\Image;
use App\Models\DB\ImagesTag;
use App\Models\ImagesService;
use App\Models\StorageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;


class ImagesServiceProvider extends ServiceProvider
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

	    App::bind(ImagesService::class, function ($app) {

		    $imageModel = new Image();
		    $imagesTagModel = new ImagesTag();
			$storageService = $app->make(StorageService::class);

		    return new ImagesService($imageModel, $imagesTagModel, $storageService);

	    });

    }
}
