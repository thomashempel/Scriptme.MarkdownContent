<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thomas
 * Date: 29.03.13
 * Time: 07:11
 * To change this template use File | Settings | File Templates.
 */

namespace Scriptme\MarkdownContent\Utility;


class Files extends \TYPO3\Flow\Utility\Files
{

	const OBJECT_TYPE_DIRECTORY = 1;
	const OBJECT_TYPE_FILE = 2;
	const OBJECT_TYPE_ANY = 3;

	/**
	 * Returns all directories inside a given path.
	 * This is a wrapper for getObjectsInPath
	 *
	 * @see getObjectsInPath
	 *
	 * @param string $path
	 *
	 * @return array
	 * @throws \TYPO3\Flow\Utility\Exception
	 */
	static public function getDirectoriesInPath($path)
	{
		return self::getObjectsInPath($path, self::OBJECT_TYPE_DIRECTORY);
	}

	/**
	 * Returns all objects inside a given path. The objects can be directories or files.
	 *
	 * @param $path
	 * @param int $type
	 * @param string $extension
	 * @return array
	 * @throws \TYPO3\Flow\Utility\Exception
	 */
	static public function getObjectsInPath($path, $type = self::OBJECT_TYPE_ANY, $extension = NULL)
	{
		if (!is_dir($path)) throw new \TYPO3\Flow\Utility\Exception('"' . $path . '" is no directory.', 1364537943);
		$objects = array();

		$directoryIterator = new \DirectoryIterator($path);
		foreach ($directoryIterator as $fileInfo) {
			$fileName = $fileInfo->getFilename();

			if ($fileName == '.' || $fileName == '..') {
				continue;
			}

			if ($extension) {
				$fileExtension = strtolower($fileInfo->getExtension());
				if (strtolower($extension) != $fileExtension) {
					continue;
				}
			}

			if (($type == self::OBJECT_TYPE_ANY) || ($type == self::OBJECT_TYPE_DIRECTORY && $fileInfo->isDir()) || ($type == self::OBJECT_TYPE_FILE && $fileInfo->isFile())) {
				$objects[] = $fileInfo->getPathname();
			}
		}

		return $objects;
	}

	/**
	 * Returns all files inside a given path.
	 * This is a wrapper for getObjectsInPath
	 *
	 * @see getObjectsInPath
	 * @param string $path
	 * @param string $extension
	 * @return array
	 */
	static public function getFilesInPath($path, $extension = NULL)
	{
		return self::getObjectsInPath($path, self::OBJECT_TYPE_FILE, $extension);
	}

	/**
	 * @param string $directory
	 * @return array
	 */
	static public function fetchMetaInformationForDirectory($directory)
	{
		$metaFileName = $directory . '/Meta.yaml';
		if (!file_exists($metaFileName)) {
			return FALSE;
		}

		$metaData = \Symfony\Component\Yaml\Yaml::parse($metaFileName);
		return $metaData;
	}

	static public function contentBaseDirectory($packageKey, $subpackageKey)
	{
		return FLOW_PATH_PACKAGES . 'Application/' . $packageKey . '/Resources/Private/' . ($subpackageKey !== NULL ? $subpackageKey . '/' : '') . 'Content';
	}
}