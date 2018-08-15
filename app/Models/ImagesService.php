<?php

namespace App\Models;


use App\Exceptions\ImagesException;
use App\Http\Requests\CreateImage;
use App\Models\DB\Image;
use App\Models\DB\ImagesTag;


class ImagesService
{

	/**
	 * @var Image
	 */
	private $imageModel;

	/**
	 * @var ImagesTag
	 */
	private $imagesTagModel;

	/**
	 * ImagesService constructor.
	 *
	 * @param Image $imageModel
	 * @param ImagesTag $imagesTagModel
	 */
	public function __construct(Image $imageModel, ImagesTag $imagesTagModel)
	{

		$this->imageModel = $imageModel;
		$this->imagesTagModel = $imagesTagModel;

	}

	/**
	 * Create image
	 *
	 * @param CreateImage $request
	 *
	 * @return Image
	 * @throws ImagesException
	 */
	public function createImage(CreateImage $request)
	{

		$imagePath = $request->path;
		$imageTags = $request->tags_list;

		$image = $this->imageModel->create(
			[
				'path' => $imagePath
			]
		);

		foreach ($imageTags as $tagId) {

			$this->imagesTagModel->create(
				[
					'image_id' => $image->id,
					'tag_id' => $tagId
				]
			);
		}

		
		return $this->findImage($image->id);
	}


	/**
	 * Edit image
	 *
	 * @param EditTag $request
	 *
	 * @return Image
	 * @throws ImagesException
	 */
	public function editImage(EditTag $request)
	{

//		$tagId = $request->id;
//		$tagName = $request->name;
//		$tagCategory = $request->category;
//		$tagParent = $request->parent_tag;
//
//		$tag = $this->findTag($tagId);
//
//		$tag->name = $tagName;
//		$tag->category_id = $tagCategory;
//		$tag->parent_tag_id = $tagParent;
//		$tag->save();
//
//		return $this->findTag($tag->id);

	}

	/**
	 * Find image
	 *
	 * @param integer $imageId
	 *
	 * @return Image
	 * @throws ImagesException
	 */
	public function findImage($imageId)
	{

		$image = $this->imageModel->where('id', $imageId)->first();

		if (is_null($image)) {
			throw new ImagesException('Image not found');
		}

		return $image;

	}

	/**
	 * Images list
	 *
	 * @return Image[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function imagesList()
	{

		return $this->imageModel->with('tags')->get();

	}

}
