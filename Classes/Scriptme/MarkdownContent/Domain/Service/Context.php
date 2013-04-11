<?php
namespace Scriptme\MarkdownContent\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Neos".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * The Context
 * @author Patrick Kollodzik <patrick.kollodzik@googlemail.com>
 * @Flow\Scope("singleton")
 */
class Context {
	/**
	 * The packagekey that contains the template and content
	 * @var string
	 */
	protected $sitePackageKey;

	/**
	 * Absolute path to the resources
	 * @var string
	 */
	protected $resourceDirectory;

	/**
	 *  Absolute path to the content
	 * @var string
	 */
	protected $contentDirectory;

	/**
	 * Current page path (URI)
	 * @var string
	 */
	protected $currentPath;

	/**
	 * Contains the meta data of the current page
	 * @var array
	 */
	protected $metdaData;

	/**
	 * @return string
	 */
	public function getSitePackageKey() {
		return $this->sitePackageKey;
	}

	/**
	 * @param string $metdaData
	 */
	public function setSitePackageKey($sitePackageKey) {
		$this->sitePackageKey = $sitePackageKey;
	}

	/**
	 * @return string
	 */
	public function getResourceDirectory() {
		return $this->resourceDirectory;
	}

	/**
	 * @param string $metdaData
	 */
	public function setResourceDirectory($resourceDirectory) {
		$this->resourceDirectory = $resourceDirectory;
	}

	/**
	 * @return string
	 */
	public function getContentDirectory() {
		return $this->contentDirectory;
	}

	/**
	 * @param string $metdaData
	 */
	public function setContentDirectory($contentDirectory) {
		$this->contentDirectory = $contentDirectory;
	}

	/**
	 * @return string
	 */
	public function getCurrentPath() {
		return $this->currentPath;
	}

	/**
	 * @param string $metdaData
	 */
	public function setCurrentPath($currentPath) {
		$this->currentPath = $currentPath;
	}

	/**
	 * @return array
	 */
	public function getMetdaData() {
		return $this->metdaData;
	}

	/**
	 * @param array $metdaData
	 */
	public function setMetdaData($metdaData) {
		$this->metdaData = $metdaData;
	}
}

?>
