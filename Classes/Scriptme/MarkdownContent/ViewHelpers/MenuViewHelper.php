<?php
namespace Scriptme\MarkdownContent\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \Scriptme\MarkdownContent\Utility\Files as Files;

class MenuViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 *
	 * @var \Scriptme\MarkdownContent\Domain\Service\Context
	 * @Flow\inject
	 */
	protected $context;

	/**
	 * Render stuff
	 *
	 * @param null $package
	 * @param null $subpackage
	 * @param string $path
	 * @param string $currentPath
	 * @param boolean $addRoot
	 * @param boolean $recursive
	 * @param integer $maxLevel
	 *
	 * @return string
	 */
	public function render($package = NULL, $subpackage = NULL, $path = '/', $currentPath = '/', $addRoot = FALSE, $recursive = TRUE, $maxLevel = 5)
	{
		if ($package !== NULL || $subpackage !== NULL) {
			$packageKey = $package === NULL ? $this->context->getSitePackageKey() : $package;
			$subpackageKey = $subpackage === NULL ? $this->controllerContext->getRequest()->getControllerSubpackageKey() : $subpackage;
			$packageContentDirectory = Files::packageContentDirectory($packageKey, $subpackageKey);
		}else {
			$packageContentDirectory = $this->context->getContentDirectory();
		}

		$searchDirectory = $packageContentDirectory . $path;

		$pages = $this->fetchPagesFromPath($searchDirectory, $packageContentDirectory, $currentPath, $recursive, $maxLevel, 0);

		if ($addRoot) {
			$pageData = $this->fetchMetaDataAtPath($searchDirectory, $packageContentDirectory);
			if ($pageData) {
				array_unshift($pages, $pageData);
			}
		}

		$this->templateVariableContainer->add('pages', $pages);
		$content = $this->renderChildren();
		$this->templateVariableContainer->remove('pages');

		return $content;
	}

	/**
	 * @param string $path
	 * @param bool $recursive
	 * @param int $maxLevel
	 * @param int $level
	 * @return array
	 */
	protected function fetchPagesFromPath($path, $basePath, $currentPath, $recursive = TRUE, $maxLevel = 5, $level = 0)
	{
		$pages = array();
		$directories = \Scriptme\MarkdownContent\Utility\Files::getDirectoriesInPath($path);
		$rootline = explode('/', $currentPath);

		foreach ($directories as $directory) {
			$pageData = $this->fetchMetaDataAtPath($directory, $basePath, $rootline);

			if ($recursive === TRUE && $level < $maxLevel) {
				$subItems = $this->fetchPagesFromPath($directory, $basePath, $currentPath, $recursive, $maxLevel, $level++);
				if ($subItems) {
					$pageData['children'] = $subItems;
				}
			}

			if (count($pageData) > 0) {
				$pages[] = $pageData;
			}
		}

		if (count($pages) > 0) {
			return $pages;
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $path
	 * @param $basePath
	 * @param array|null $rootline
	 * @return array|bool
	 */
	protected function fetchMetaDataAtPath($path, $basePath, $rootline = NULL)
	{
		$metaData = Files::fetchMetaInformationForDirectory($path);
		$pageData = array();

		if (!$metaData) return FALSE;

		if ($metaData['Visible'] === FALSE || $metaData['InMenu'] === FALSE) return FALSE;

		$pageData['meta'] = $metaData;
		$pageData['path'] = str_replace($basePath.'/', '', $path);

		$pathSegments = explode('/', $pageData['path']);
		$pageData['name'] = $pathSegments[count($pathSegments) -1];

		if ($rootline && in_array($pageData['name'], $rootline)) {
			$pageData['current'] = TRUE;
		}

		return $pageData;
	}
}

?>