<?php

namespace App\Models;


use App\Exceptions\TagsException;
use App\Http\Requests\CreateTag;
use App\Http\Requests\CreateTagsCategory;
use App\Http\Requests\RemoveTag;
use App\Http\Requests\RemoveTagsCategory;
use App\Models\DB\Tag;
use App\Models\DB\TagsCategory;


class TagsService
{

	/**
	 * @var Tag
	 */
	private $tagsModel;

	/**
	 * @var TagsCategory
	 */
	private $tagsCategoryModel;

	/**
	 * TagsService constructor.
	 *
	 * @param Tag $tagsModel
	 * @param TagsCategory $tagsCategoryModel
	 */
	public function __construct(Tag $tagsModel, TagsCategory $tagsCategoryModel)
	{

		$this->tagsModel = $tagsModel;
		$this->tagsCategoryModel = $tagsCategoryModel;

	}

	/**
	 * Create tags category
	 *
	 * @param CreateTagsCategory $request
	 *
	 * @return TagsCategory
	 */
	public function createCategory(CreateTagsCategory $request)
	{

		return $this->tagsCategoryModel->create(
			[
				'name' => $request->name
			]
		);

	}

	/**
	 * Remove category
	 *
	 * @param RemoveTagsCategory $request
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function removeCategory(RemoveTagsCategory $request)
	{

		$categoryId = $request->id;

		$category = $this->findCategory($categoryId);

		$category->delete();

	}

	/**
	 * Find category
	 *
	 * @param integer $categoryId
	 *
	 * @return TagsCategory
	 * @throws TagsException
	 */
	public function findCategory($categoryId)
	{

		$category = $this->tagsCategoryModel->where('id', $categoryId)->first();

		if (is_null($category)) {
			throw new TagsException('Tags category not found');
		}

		return $category;

	}

	/**
	 * Categories list
	 *
	 * @return TagsCategory[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function categoriesList()
	{

		return $this->tagsCategoryModel->all();

	}








	/**
	 * Create tag
	 *
	 * @param CreateTag $request
	 *
	 * @return Tag
	 * @throws TagsException
	 */
	public function createTag(CreateTag $request)
	{

		$tag = $this->tagsModel->create(
			[
				'name' => $request->name,
				'category_id' => $request->category,
				'parent_tag_id' => $request->parent_tag
			]
		);
		
		return $this->findTag($tag->id);

	}

	/**
	 * Remove tag
	 *
	 * @param RemoveTag $request
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function removeTag(RemoveTag $request)
	{

		$tagId = $request->id;

		$tag = $this->findTag($tagId);

		$tag->delete();

	}

	/**
	 * Find tag
	 *
	 * @param integer $tagId
	 *
	 * @return Tag
	 * @throws TagsException
	 */
	public function findTag($tagId)
	{

		$tag = $this->tagsModel->where('id', $tagId)->with('category', 'parentTag')->first();

		if (is_null($tag)) {
			throw new TagsException('Tag not found');
		}

		return $tag;

	}

	/**
	 * Tags list
	 *
	 * @return Tag[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function tagsList()
	{

		return $this->tagsModel->with('category', 'parentTag')->get();

	}

}
