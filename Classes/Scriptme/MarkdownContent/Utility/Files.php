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
     * Returns all files inside a given path.
     * This is a wrapper for getObjectsInPath
     *
     * @see getObjectsInPath
     * @param $path
     * @return array
     */
    static public function getFilesInPath($path)
    {
        return self::getObjectsInPath($path, self::OBJECT_TYPE_FILE);
    }

    /**
     * Returns all objects inside a given path. The objects can be directories or files.
     *
     * @param $path
     * @param int $type
     * @return array
     * @throws \TYPO3\Flow\Utility\Exception
     */
    static public function getObjectsInPath($path, $type = self::OBJECT_TYPE_ANY)
    {
        if (!is_dir($path)) throw new \TYPO3\Flow\Utility\Exception('"' . $path . '" is no directory.', 1364537943);
        $objects = array();

        $directoryIterator = new \DirectoryIterator($path);
        foreach ($directoryIterator as $fileInfo) {
            $fileName = $fileInfo->getFilename();

            if ($fileName == '.' || $fileName == '..') {
                continue;
            }

            if (($type == self::OBJECT_TYPE_ANY) || ($type == self::OBJECT_TYPE_DIRECTORY && $fileInfo->isDir()) || ($type == self::OBJECT_TYPE_FILE && $fileInfo->isFile())) {
                $objects[] = $fileInfo->getPathname();
            }
        }

        return $objects;
    }

}