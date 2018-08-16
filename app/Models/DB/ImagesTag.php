<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ImagesTag extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'image_id', 'tag_id'
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
	 * Return tag
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function tag()
	{

		return $this->hasOne('App\Models\DB\Tag', 'id', 'tag_id');

	}


}
