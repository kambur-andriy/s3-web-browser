<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'category_id', 'parent_tag_id'
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
	 * Return category
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{

		return $this->belongsTo('App\Models\DB\TagsCategory', 'category_id', 'id')->withDefault(
			[
				'name' => 'Without category'
			]
		);

	}

	/**
	 * Return parent tag
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parentTag()
	{

		return $this->belongsTo('App\Models\DB\Tag', 'parent_tag_id', 'id')->withDefault(
			[
				'name' => 'Without parents'
			]
		);

	}

}
