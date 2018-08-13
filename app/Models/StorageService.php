<?php

namespace App\Models;


use App\Exceptions\StorageException;
use App\Http\Requests\ContentInfo;
use App\Http\Requests\ContentList;
use App\Http\Requests\ContentPaste;
use App\Http\Requests\ContentRemove;
use App\Http\Requests\ContentRename;
use App\Http\Requests\DirectoryMake;
use App\Http\Requests\FileDownload;
use App\Http\Requests\FileUpload;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class StorageService
{

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * StorageService constructor.
	 *
	 * @param FilesystemAdapter $storage
	 */
	public function __construct(FilesystemAdapter $storage)
	{

		$this->storage = $storage;

	}

	/** Get directory content
	 *
	 * @param ContentList $request
	 *
	 * @return array
	 */
	public function list(ContentList $request)
	{

		$path = $request->path;

		$dirInfo = $this->pathInfo($path);
		$dirList = $this->directories($path);
		$filesList = $this->files($path);
		$qNavigation = $this->quickNavigation($path);

		return [
			'info' => [
				'dirPath' => $path,
				'dirName' => $dirInfo['basename'] === '.' ? 'Root' : $dirInfo['basename'],
				'dirCount' => count($dirList),
				'filesCount' => count($filesList)
			],
			'directories' => $dirList,
			'files' => $filesList,
			'quick_navigation' => $qNavigation
		];

	}

	/**
	 * Get file info
	 *
	 * @param ContentInfo $request
	 *
	 * @return array
	 *
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function info(ContentInfo $request)
	{

		$path = $request->path;

		$this->checkExists($path);

		$size = $this->isFile($path) ? $this->fileSize($path) : $this->directorySize($path);
		$lastModified = $this->storage->lastModified($path);

		return  [
			'size' => $size,
			'modified' => $lastModified !== false ? date('d-m-Y H:i:s', $this->storage->lastModified($path)) : '---'
		];

	}

	/**
	 * Rename file or directory
	 *
	 * @param ContentRename $request
	 *
	 * @return void
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function rename(ContentRename $request)
	{

		$sourcePath = $request->path;
		$newName = $request->name;

		$info = pathinfo($sourcePath);

		$destinationPath = $info['dirname'] . '/' . $newName;

		$this->checkUnique($destinationPath);

		if ($this->isFile($sourcePath)) {

			$this->storage->move($sourcePath, $destinationPath);

		} elseif ($this->isDirectory($sourcePath)) {

			$this->createDirectory($destinationPath);

			$subDirectories = $this->directories($sourcePath);

			foreach ($subDirectories as $subDirectory) {
				$this->moveDirectory($subDirectory['path'], $destinationPath);
			}

			$files = $this->files($sourcePath);

			foreach ($files as $file) {
				$this->moveFile($file['path'], $destinationPath);
			}

			$this->removeDirectory($sourcePath);

		}

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

		$path = $request->path;

		$this->checkExists($path);

		return $this->storage->download($path);

	}

	/**
	 * Create directory
	 *
	 * @param DirectoryMake $request
	 *
	 * @return void
	 * @throws StorageException
	 */
	public function makeDirectory(DirectoryMake $request)
	{

		$parentDirectory = $request->path;

		$directoryName = $request->name;

		$sourcePath = $parentDirectory . '/' . $directoryName;

		$this->checkUnique($sourcePath);

		$this->createDirectory($sourcePath);

	}

	/**
	 * Remove content
	 *
	 * @param ContentRemove $request
	 *
	 * @return void
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function remove(ContentRemove $request)
	{

		$pathList = $request->path_list;

		foreach ($pathList as $path) {

			if ($this->isFile($path)) {

				$this->removeFile($path);

			} else if ($this->isDirectory($path)) {

				$this->removeDirectory($path);

			} else {

				throw new StorageException('Can not remove files or directories');

			}

		}

	}

	/**
	 * Paste content
	 *
	 * @param ContentPaste $request
	 *
	 * @return void
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function paste(ContentPaste $request)
	{

		$sourcePathList = $request->source_path_list;
		$destinationPath = $request->destination_path;
		$operation = $request->operation;

		if ($operation !== 'copy' && $operation !== 'cut') {
			throw new StorageException('Unknown operation type.');
		}

		foreach ($sourcePathList as $sourcePath) {

			if ($operation === 'copy') {

				$this->copy($sourcePath, $destinationPath);

			} else {

				$this->move($sourcePath, $destinationPath);

			}
		}

	}

	/**
	 * Upload files
	 *
	 * @param FileUpload $request
	 *
	 * @return void
	 * @throws StorageException
	 */
	public function upload(FileUpload $request)
	{

		$filesList = $request->files_list;

		$path = $request->path;

		if (count($filesList) === 0) {
			throw new StorageException('Files list is empty.');
		}

		foreach ($filesList as $file) {

			$this->uploadFile($file, $path);

		}

	}


	/**
	 * Get file size
	 *
	 * @param string $path
	 *
	 * @return integer|null
	 */
	private function fileSize($path)
	{

		return (int) $this->storage->size($path);

	}

	/**
	 * Get directory size
	 *
	 * @param string $path
	 *
	 * @return integer
	 */
	private function directorySize($path)
	{

		$size = 0;

		$files = $this->filesAll($path);

		foreach ($files as $file) {

			$size += $this->storage->size($file['path']);

		}

		return $size;
	}

	/**
	 * Get path info
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	private function pathInfo($path)
	{

		$pathInfo = pathinfo($path);

		return [
			'path' => $path,
			'basename' => $pathInfo['basename'],
			'dirname' => $pathInfo['dirname'],
			'filename' => $pathInfo['filename'],
			'extension' => $pathInfo['extension'] ?? ''
		];

	}

	/**
	 * Get directories list
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	private function directories($directory)
	{

		$s3Directories = $this->storage->directories($directory);

		$directoriesList = [];

		foreach ($s3Directories as $s3Directory) {

			$dirInfo = pathinfo($s3Directory);

			$directoriesList[] = [
				'name' => $dirInfo['basename'],
				'path' => $s3Directory,
			];

		}

		return $directoriesList;

	}

	/**
	 * Get directories list with all subdirectories
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	private function directoriesAll($directory)
	{

		$s3Directories = $this->storage->allDirectories($directory);

		$directoriesList = [];

		foreach ($s3Directories as $s3Directory) {

			$dirInfo = pathinfo($s3Directory);

			$directoriesList[] = [
				'name' => $dirInfo['basename'],
				'path' => $s3Directory,
			];

		}

		return $directoriesList;

	}

	/**
	 * Get files list for directory
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	private function files($directory)
	{

		$s3Files = $this->storage->files($directory);

		$filesList = [];

		foreach ($s3Files as $s3File) {

			$fileInfo = pathinfo($s3File);

			$filesList[] = [
				'name' => $fileInfo['basename'],
				'path' => $s3File,
				'url' => $this->storage->temporaryUrl(
					$s3File,
					now()->addMinutes(30)
				)
			];

		}

		return $filesList;

	}

	/**
	 * Get files list for directory and all subdirectories
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	private function filesAll($directory)
	{

		$s3Files = $this->storage->allFiles($directory);

		$filesList = [];

		foreach ($s3Files as $s3File) {

			$fileInfo = pathinfo($s3File);

			$filesList[] = [
				'name' => $fileInfo['basename'],
				'path' => $s3File,
				'url' => $this->storage->temporaryUrl(
					$s3File,
					now()->addMinutes(5)
				)
			];

		}

		return $filesList;

	}

	/**
	 * Get list of path parts for navigation
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	private function quickNavigation($directory)
	{
		$directoriesList = [];

		if ($directory === '.') {

			return $directoriesList;

		}

		$directoriesList = [
			[
				'name' => 'Root',
				'path' => '.'
			]
		];

		$pathParts = explode('/', $directory);

		if (count($pathParts) === 1) {

			return $directoriesList;

		}

		$path = '/';

		foreach ($pathParts as $s3Directory) {

			if ($s3Directory === '') {
				continue;
			}

			$path .= $s3Directory;

			$directoriesList[] = [
				'name' => $s3Directory,
				'path' => $path,
			];

			$path .= '/';

		}

		return array_slice($directoriesList, 0, -1);

	}

	/**
	 * Check if file/directory name is unique
	 *
	 * @param string $path
	 *
	 * return void
	 * @throws StorageException
	 */
	private function checkUnique($path)
	{

		if ($this->storage->exists($path)) {
			throw new StorageException('File or directory already exists.');
		}

	}

	/**
	 * Check if file/directory exists
	 *
	 * @param string $path
	 *
	 * return void
	 * @throws StorageException
	 */
	private function checkExists($path)
	{

		if (!$this->storage->exists($path)) {
			throw new StorageException('No such file or directory.');
		}

	}

	/**
	 * Remove directory
	 *
	 * @param string $path
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function removeDirectory($path)
	{

		$this->checkExists($path);

		return $this->storage->deleteDirectory($path);

	}

	/**
	 * Remove file
	 *
	 * @param string $path
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function removeFile($path)
	{

		$this->checkExists($path);

		return $this->storage->delete($path);

	}

	/**
	 * Copy file
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function copyFile($sourcePath, $destinationPath)
	{

		$this->checkExists($sourcePath);

		try {

			$filePath = $this->getCopyFilePath($sourcePath, $destinationPath);

			return $this->storage->copy($sourcePath, $filePath);

		} catch (\Exception $e) {

			throw new StorageException('Can not copy files.');

		}

	}

	/**
	 * Move file
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function moveFile($sourcePath, $destinationPath)
	{

		$this->checkExists($sourcePath);

		try {

			$filePath = $this->getCopyFilePath($sourcePath, $destinationPath);

			return $this->storage->move($sourcePath, $filePath);

		} catch (\Exception $e) {

			throw new StorageException('Can not move files.');

		}

	}

	/**
	 * Create directory
	 *
	 * @param string $path
	 *
	 * @return boolean
	 */
	private function createDirectory($path)
	{

		return $this->storage->makeDirectory($path);

	}

	/**
	 * Copy directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function copyDirectory($sourcePath, $destinationPath)
	{

		$this->checkExists($sourcePath);

		try {

			$directoryPath = $this->getCopyDiretoryPath($sourcePath, $destinationPath);

			$this->createDirectory($directoryPath);

			$subDirectories = $this->directories($sourcePath);

			foreach ($subDirectories as $subDirectory) {

				$this->copyDirectory($subDirectory['path'], $directoryPath);

			}

			$filesList = $this->files($sourcePath);

			foreach ($filesList as $file) {

				$this->copyFile($file['path'], $directoryPath);

			}

		} catch (\Exception $e) {

			return false;

		}

		return true;

	}

	/**
	 * Move directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 * @throws StorageException
	 */
	private function moveDirectory($sourcePath, $destinationPath)
	{

		try {

			$this->copyDirectory($sourcePath, $destinationPath);

			$this->removeDirectory($sourcePath);

		} catch (\Exception $e) {

			return false;

		}

		return true;

	}

	/**
	 * Get file name for copying it to destination directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return string
	 */
	private function getCopyFilePath($sourcePath, $destinationPath)
	{

		$pathInfo = $this->pathInfo($sourcePath);

		$newFilePath = $destinationPath . '/' . $pathInfo['basename'];

		$index = 1;

		do {

			try {

				$this->checkExists($newFilePath);

				$fileExist = true;

				$newFilePath = $destinationPath . '/' . $pathInfo['filename'] . '_copy(' . $index . ').' . $pathInfo['extension'];

				$index++;



			} catch (StorageException $e) {

				$fileExist = false;

			}


		} while ($fileExist === true);


		return $newFilePath;

	}

	/**
	 * Get directory name for copying it to destination directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return string
	 */
	private function getCopyDiretoryPath($sourcePath, $destinationPath)
	{

		$pathInfo = $this->pathInfo($sourcePath);

		$newFilePath = $destinationPath . '/' . $pathInfo['basename'];

		$index = 1;

		do {

			try {

				$this->checkExists($newFilePath);

				$fileExist = true;

				$newFilePath = $destinationPath . '/' . $pathInfo['filename'] . '_copy(' . $index . ')';

				$index++;



			} catch (StorageException $e) {

				$fileExist = false;

			}


		} while ($fileExist === true);


		return $newFilePath;

	}

	/**
	 * Upload file
	 *
	 * @param UploadedFile $file
	 * @param string $path
	 *
	 * @return string
	 */
	private function uploadFile($file, $path)
	{

		$sourcePath = $path . '/' . $file->getClientOriginalName();

		$uploadFileName = $this->getCopyFilePath($sourcePath, $path);

		$this->storage->putFileAs('.', $file, $uploadFileName);

	}

	/**
	 * Check if path is a directory
	 *
	 * @param string $path
	 *
	 * @return bool
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	private function isDirectory($path) {

		return $this->storage->getMimetype($path) === false;

	}

	/**
	 * Check if path is file
	 *
	 * @param string $path
	 *
	 * @return bool
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	private function isFile($path) {

		return $this->storage->getMimetype($path) !== false;

	}

	/**
	 * Copy file or directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return void
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	private function copy($sourcePath, $destinationPath) {

		if ($this->isFile($sourcePath)) {

			$this->copyFile($sourcePath, $destinationPath);

		} else if ($this->isDirectory($sourcePath)) {

			$this->copyDirectory($sourcePath, $destinationPath);

		} else {

			throw new StorageException('Can not copy files or directories');

		}

	}

	/**
	 * Move file or directory
	 *
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return void
	 * @throws StorageException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	private function move($sourcePath, $destinationPath) {

		if ($this->isFile($sourcePath)) {

			$this->moveFile($sourcePath, $destinationPath);

		} else if ($this->isDirectory($sourcePath)) {

			$this->moveDirectory($sourcePath, $destinationPath);

		} else {

			throw new StorageException('Can not move files or directories');

		}

	}

}
