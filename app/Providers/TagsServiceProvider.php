<?php

namespace App\Providers;


use App\Models\DB\Tag;
use App\Models\DB\TagsCategory;
use App\Models\StorageService;
use App\Models\TagsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;


class TagsServiceProvider extends ServiceProvider
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

	    App::bind(TagsService::class, function ($app) {

		    $tagsModel = new Tag();
		    $tagsCategoryModel = new TagsCategory();

		    return new TagsService($tagsModel, $tagsCategoryModel);

	    });

    }
}
