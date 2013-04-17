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

abstract class AbstractMdViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 * @var \TYPO3\Flow\Session\SessionInterface
	 * @Flow\Inject
	 */
	protected $session;

	/**
	 *
	 * @var \Scriptme\MarkdownContent\Domain\Service\Context
	 * @Flow\inject
	 */
	protected $context;

	/**
	 * @param string $name
	 * @param string $package
	 * @param string $subpackage
	 * @param string $path
	 *
	 * @Flow\Session(autoStart = TRUE)
	 *
	 * @return string
	 */
	public function render($name = 'Content', $package = NULL, $subpackage = NULL, $path = NULL)
	{

		if ($path === NULL) {
			$path = $this->session->getData('currentPath');
		}

		if ($package !== NULL || $subpackage !== NULL) {
			$packageKey = $package === NULL ? $this->context->getSitePackageKey() : $package;
			$subpackageKey = $subpackage === NULL ? $this->controllerContext->getRequest()->getControllerSubpackageKey() : $subpackage;
			$packageContentDirectory = Files::packageContentDirectory($packageKey, $subpackageKey);
		} else {
			$packageContentDirectory = $this->context->getContentDirectory();
		}

		if (substr($path, -1, 1) != '/') $path .= '/';
		$html = '';

		if (empty($name)) {

			// find all files md at path
			$files = Files::getObjectsInPath($packageContentDirectory . $path, Files::OBJECT_TYPE_FILE, 'md');
			foreach ($files as $file) {
				$html .= $this->loadFile($packageContentDirectory . $path . $file);
			}

		} else {
			$fileName = $packageContentDirectory . $path . $name . '.md';
			$html = $this->loadFile($fileName);
		}

		// create a new fluid template with the cleaned HTML, render it and return the result
		$view = new \TYPO3\Fluid\View\StandaloneView();
		$view->setTemplateSource($html);
		return $view->render();
	}

	protected function loadFile($path)
	{
		if (!file_exists($fileName)) {
			return '';
		}

		// load markdown content from file and render to HTML
		$mdData = file_get_contents($fileName);
		$html = $this->parseMarkDown($mdData);

		// load html into DOM
		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		// find all image tags and replace the sources with fluid resource links
		$imageTags = $dom->getElementsByTagName('img');
		foreach ($imageTags as $tag) {
			$src = $tag->getAttribute('src');
			if (substr($src, 0, 1) == '{') continue;
			$resource = '{f:uri.resource(path: \'' . $src . '\', package:\'' . ($package === NULL ? $this->context->getSitePackageKey() : $package) . '\')}';
			$tag->setAttribute('src', $resource);
		}

		// remove body tag that was created in loadHTML method
		$innerHTML = '';
		foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $child) {
			$innerHTML .= $dom->saveXML($child);
		}

		return $innerHTML;
	}

}

?>