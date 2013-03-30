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

class MenuViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 * @param null $package
	 * @param null $subpackage
	 * @param string $path
	 * @param string $currentPath
	 * @param boolean $recursive
	 * @param integer $maxLevel
	 */
	public function render($package = NULL, $subpackage = NULL, $path = '/', $currentPath = '/', $recursive = TRUE, $maxLevel = 5)
	{
		$packageKey = $package === NULL ? $this->controllerContext->getRequest()->getControllerPackageKey() : $package;
		$subpackageKey = $subpackage === NULL ? $this->controllerContext->getRequest()->getControllerSubpackageKey() : $subpackage;

		$baseDirectory = FLOW_PATH_PACKAGES . 'Application/' . $packageKey . '/Resources/Private/' . ($subpackageKey !== NULL ? $subpackageKey . '/' : '') . 'Content';
		$searchDirectory = $baseDirectory . $path;
		$pages = $this->fetchPagesFromPath($searchDirectory, $baseDirectory, $currentPath, $recursive, $maxLevel, 0);

		$this->templateVariableContainer->add('pages', $pages);
		return $this->renderChildren();
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
		$rootline = explode('-', $currentPath);

		foreach ($directories as $directory) {
			$metaData = $this->fetchMetaInformationForDirectory($directory);
			$pageData = array();

			if (!$metaData) continue;

			if ($metaData['Visible'] === FALSE || $metaData['InMenu'] === FALSE) continue;

			$pageData['meta'] = $metaData;

			$pageData['path'] = str_replace(array($basePath.'/', '/'), array('', '-'), $directory);

			$pathSegments = explode('-', $pageData['path']);
			$pageData['name'] = $pathSegments[count($pathSegments) -1];

			if (in_array($pageData['name'], $rootline)) {
				$pageData['current'] = TRUE;
			}

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
	 * @param string $directory
	 * @return array
	 */
	protected function fetchMetaInformationForDirectory($directory)
	{
		$metaFileName = $directory . '/Meta.yaml';
		if (!file_exists($metaFileName)) {
			return FALSE;
		}

		$metaData = \Symfony\Component\Yaml\Yaml::parse($metaFileName);
		return $metaData;
	}
}

?>