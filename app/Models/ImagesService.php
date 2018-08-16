<?php

namespace App\Models;


use App\Exceptions\ImagesException;
use App\Http\Requests\CreateImage;
use App\Http\Requests\RemoveImage;
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
		$imageName = $request->name;
		$imageTags = $request->tags_list;

		$image = $this->imageModel->create(
			[
				'path' => $imagePath,
				'name' => $imageName
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

		
		return $this->findImageWithTags($image->id);
	}

	/**
	 * Remove image
	 *
	 * @param RemoveImage $request
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function removeImage(RemoveImage $request)
	{

		$imageId = $request->id;

		$image = $this->findImage($imageId);

		$image->delete();

		foreach ($this->findImageTags($imageId) as $imageTag) {

			$imageTag->delete();
		}

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
	 * Find image tags
	 *
	 * @param integer $imageId
	 *
	 * @return Image
	 */
	public function findImageTags($imageId)
	{

		return $this->imagesTagModel->where('image_id', $imageId)->with('tag')->get();

	}

	/**
	 * Find image with tags
	 *
	 * @param integer $imageId
	 *
	 * @return Image
	 * @throws ImagesException
	 */
	public function findImageWithTags($imageId)
	{

		$image = $this->findImage($imageId);

		$image->tags = $this->findImageTags($imageId);

		return $image;

	}

	/**
	 * Images list
	 *
	 * @return Image[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function imagesList()
	{

		$tagsList = $this->imagesTagModel->with('tag')->get()->groupBy('image_id')->toArray();

		$imagesList = [];

		foreach ($this->imageModel->all() as $image) {

			$image->tags = $tagsList[$image->id] ?? [];

			$imagesList[] = $image;

		}

		return $imagesList;

	}

}
