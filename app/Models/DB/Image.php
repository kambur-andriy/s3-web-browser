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
		'path'
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
	 * Return tags list
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tags()
	{

		return $this->hasMany('App\Models\DB\ImagesTag', 'image_id', 'id');

	}

}
