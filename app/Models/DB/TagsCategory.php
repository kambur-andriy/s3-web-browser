<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class TagsCategory extends Model
{

	protected $table = 'tags_categories';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at'
	];


}
