<?php

namespace App\Models;


use App\Exceptions\TagsException;
use App\Http\Requests\CreateTag;
use App\Http\Requests\CreateTagsCategory;
use App\Http\Requests\EditTag;
use App\Http\Requests\EditTagsCategory;
use App\Http\Requests\RemoveTag;
use App\Http\Requests\RemoveTagsCategory;
use App\Models\DB\Tag;
use App\Models\DB\TagsCategory;


class TagsService
{

	/**
	 * @var Tag
	 */
	private $tagModel;

	/**
	 * @var TagsCategory
	 */
	private $tagsCategoryModel;

	/**
	 * TagsService constructor.
	 *
	 * @param Tag $tagModel
	 * @param TagsCategory $tagsCategoryModel
	 */
	public function __construct(Tag $tagModel, TagsCategory $tagsCategoryModel)
	{

		$this->tagModel = $tagModel;
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
	 * Edit category
	 *
	 * @param EditTagsCategory $request
	 *
	 * @return TagsCategory
	 * @throws TagsException
	 */
	public function editCategory(EditTagsCategory $request)
	{

		$categoryId = $request->id;
		$categoryName = $request->name;

		$category = $this->findCategory($categoryId);

		$category->name = $categoryName;
		$category->save();

		return $category;

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

		$tag = $this->tagModel->create(
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
	 * Edit tag
	 *
	 * @param EditTag $request
	 *
	 * @return Tag
	 * @throws TagsException
	 */
	public function editTag(EditTag $request)
	{

		$tagId = $request->id;
		$tagName = $request->name;
		$tagCategory = $request->category;
		$tagParent = $request->parent_tag;

		$tag = $this->findTag($tagId);

		$tag->name = $tagName;
		$tag->category_id = $tagCategory;
		$tag->parent_tag_id = $tagParent;
		$tag->save();

		return $this->findTag($tag->id);

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

		$tag = $this->tagModel->where('id', $tagId)->with('category', 'parentTag')->first();

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

		return $this->tagModel->with('category', 'parentTag')->get();

	}

}
