<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'path', 'name'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at'
	];

	/**
	 * Return image tags id list
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tags()
	{

		return $this->belongsToMany('App\Models\DB\Tag', 'images_tags','image_id', 'tag_id');

	}

}
