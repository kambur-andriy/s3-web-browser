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
	 * @var StorageService
	 */
	private $storageService;

	/**
	 * ImagesService constructor.
	 *
	 * @param Image $imageModel
	 * @param ImagesTag $imagesTagModel
	 * @param StorageService $storageService
	 */
	public function __construct(Image $imageModel, StorageService $storageService)
	{

		$this->imageModel = $imageModel;
		$this->storageService = $storageService;

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

		$image->tags()->attach($imageTags);

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

		$image->tags()->detach();

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

		$image->url = $this->getImageUrl($image->path);

		return $image;

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

		$image->load('tags');

		return $image;

	}

	/**
	 * Images list
	 *
	 * @return Image[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function imagesList()
	{

		$imagesTagsList = $this->imageModel->with('tags')->get();

		$imagesList = [];

		foreach ($imagesTagsList as $image) {

			$image->url = $this->getImageUrl($image->path);

			$imagesList[] = $image;

		}

		return $imagesList;

	}

	/**
	 * Return image url
	 *
	 * @param string $imagePath
	 *
	 * @return string
	 */
	protected function getImageUrl($imagePath)
	{

		return $this->storageService->fileUrl($imagePath);

	}

}
