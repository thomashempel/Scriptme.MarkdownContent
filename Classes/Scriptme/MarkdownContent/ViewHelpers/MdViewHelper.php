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

require_once FLOW_PATH_PACKAGES . 'Application/Scriptme.MarkdownContent/Resources/Private/PHP/php-markdown/Michelf/Markdown.php';

use TYPO3\Flow\Annotations as Flow;
use \Michelf\Markdown as Md;
use \Scriptme\MarkdownContent\Utility\Files as Files;

class MdViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 * @var \TYPO3\Flow\Session\SessionInterface
	 * @Flow\Inject
	 */
	protected $session;

	/**
	 * @param string $name
	 * @param string $package
	 * @param string $subpackage
	 *
	 * @Flow\Session(autoStart = TRUE)
	 *
	 * @return string
	 */
	public function render($name = 'Content', $package = NULL, $subpackage = NULL)
	{

		$currentPath = $this->session->getData('currentPath');

		$packageKey = $package === NULL ? $this->controllerContext->getRequest()->getControllerPackageKey() : $package;
		$subpackageKey = $subpackage === NULL ? $this->controllerContext->getRequest()->getControllerSubpackageKey() : $subpackage;
		$baseDirectory = Files::contentBaseDirectory($packageKey, $subpackageKey);
		$fileName = $baseDirectory . $currentPath . '/' . $name . '.md';

		if (!file_exists($fileName)) {
			return '';
		}

		// load markdown content from file and render to HTML
		$mdData = file_get_contents($fileName);
		$html = Md::defaultTransform($mdData);

		// load html into DOM
		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		// find all image tags and replace the sources with fluid resource links
		$imageTags = $dom->getElementsByTagName('img');
		foreach($imageTags as $tag) {
			$src = $tag->getAttribute('src');
			if (substr($src, 0, 1) == '{') continue;
			$resource = '{f:uri.resource(path: \'' . $src . '\', package:\'' . $packageKey . '\')}';
			$tag->setAttribute('src', $resource);
		}

		// remove body tag that was created in loadHTML method
		$innerHTML = '';
		foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $child) {
			$innerHTML .= $dom->saveXML($child);
		}

		// create a new fluid template with the cleaned HTML, render it and return the result
		$view = new \TYPO3\Fluid\View\StandaloneView();
		$view->setTemplateSource($innerHTML);
		return $view->render();
	}

}

?>