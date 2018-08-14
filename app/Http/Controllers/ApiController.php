<?php

namespace App\Http\Controllers;


use App\Exceptions\StorageException;
use App\Exceptions\TagsException;
use App\Http\Requests\ContentInfo;
use App\Http\Requests\ContentList;
use App\Http\Requests\ContentPaste;
use App\Http\Requests\ContentRemove;
use App\Http\Requests\ContentRename;
use App\Http\Requests\CreateTag;
use App\Http\Requests\CreateTagsCategory;
use App\Http\Requests\DirectoryMake;
use App\Http\Requests\FileDownload;
use App\Http\Requests\FileUpload;
use App\Http\Requests\RemoveTag;
use App\Http\Requests\RemoveTagsCategory;
use App\Models\StorageService;
use App\Models\TagsService;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ApiController extends Controller
{

	private $storageService;
	private $tagsService;

	/**
	 * ApiController constructor.
	 *
	 * @param StorageService $storageService
	 */
	public function __construct(StorageService $storageService, TagsService $tagsService)
	{

		$this->storageService = $storageService;
		$this->tagsService = $tagsService;

	}

	/**
     * List of directory content
     *
     * @param ContentList $request
     *
     * @return JSON
     */
    public function list(ContentList $request)
    {

    	$content = $this->storageService->list($request);

	    return response()->json(
		    $content
	    );

    }

	/**
	 * Content info
	 *
	 * @param ContentInfo $request
	 *
	 * @return JSON
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function info(ContentInfo $request)
	{

		$info = $this->storageService->info($request);

		return response()->json(
			$info
		);

	}

	/**
	 * Remove
	 *
	 * @param ContentRename $request
	 *
	 * @return JSON
	 * @throws StorageException
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function rename(ContentRename $request)
	{

		$this->storageService->rename($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Download file
	 *
	 * @param FileDownload $request
	 *
	 * @return StreamedResponse
	 * @throws StorageException
	 */
	public function download(FileDownload $request)
	{

		return $this->storageService->download($request);

	}

	/**
	 * Make directory
	 *
	 * @param DirectoryMake $request
	 *
	 * @return JSON
	 * @throws StorageException
	 */
	public function makeDirectory(DirectoryMake $request)
	{

		$this->storageService->makeDirectory($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Remove
	 *
	 * @param ContentRemove $request
	 *
	 * @return JSON
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function remove(ContentRemove $request)
	{

		$this->storageService->remove($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Paste
	 *
	 * @param ContentPaste $request
	 *
	 * @return JSON
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function paste(ContentPaste $request)
	{

		$this->storageService->paste($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Upload
	 *
	 * @param FileUpload $request
	 *
	 * @return JSON
	 * @throws StorageException
	 */
	public function upload(FileUpload $request)
	{

		$this->storageService->upload($request);

		return response()->json(
			[]
		);

	}





	/**
	 * Create category
	 *
	 * @param CreateTagsCategory $request
	 *
	 * @return JSON
	 */
	public function createCategory(CreateTagsCategory $request)
	{

		$category = $this->tagsService->createCategory($request);

		return response()->json(
			$category
		);

	}

	/**
	 * Create tag
	 *
	 * @param CreateTag $request
	 *
	 * @return JSON
	 * @throws TagsException
	 */
	public function createTag(CreateTag $request)
	{

		$category = $this->tagsService->createTag($request);

		return response()->json(
			$category
		);

	}

	/**
	 * Remove category
	 *
	 * @param RemoveTagsCategory $request
	 *
	 * @return JSON
	 * @throws \Exception|TagsException
	 */
	public function removeCategory(RemoveTagsCategory $request)
	{

		$this->tagsService->removeCategory($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Remove tag
	 *
	 * @param RemoveTag $request
	 *
	 * @return JSON
	 * @throws \Exception|TagsException
	 */
	public function removeTag(RemoveTag $request)
	{

		$this->tagsService->removeTag($request);

		return response()->json(
			[]
		);

	}

	/**
	 * Categories List
	 *
	 * @param CreateTagsCategory $request
	 *
	 * @return JSON
	 */
	public function tags()
	{

		$categoriesList = $this->tagsService->categoriesList();
		$tagsList = $this->tagsService->tagsList();

		return response()->json(
			[
				'tags_categories_list' => $categoriesList,
				'tags_list' => $tagsList
			]
		);

	}

}
