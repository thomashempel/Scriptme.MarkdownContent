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
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Http\Response;
use \Michelf\Markdown as Md;
use \Scriptme\MarkdownContent\Utility\Files as Files;

class IncludeViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Namespace used for this plugin
	 * @var string
	 */
	protected $pluginNamespace = 'scriptme_markdowncontent_plugin';

	/**
	 * @param $package
	 * @param $controller
	 * @param $action
	 *
	 * @return string
	 */
	public function render($package, $controller, $action)
	{
		$parentRequest = $this->controllerContext->getRequest();
		$pluginRequest = new ActionRequest($parentRequest);
		$pluginRequest->setArgumentNamespace('--' . $this->pluginNamespace);

		$arguments = $pluginRequest->getMainRequest()->getPluginArguments();

		if (isset($arguments[$this->pluginNamespace])) {
			$pluginRequest->setArguments($arguments[$this->pluginNamespace]);
		}

		if ($pluginRequest->getControllerPackageKey() === NULL) {
			$pluginRequest->setControllerPackageKey($package);
		}

		if ($pluginRequest->getControllerName() === NULL) {
			$pluginRequest->setControllerName($controller);
		}

		if ($pluginRequest->getControllerActionName() === NULL) {
			$pluginRequest->setControllerActionName($action);
		}

		$parentResponse = $this->controllerContext->getResponse();
		$pluginResponse = new Response($parentResponse);

		$this->dispatcher->dispatch($pluginRequest, $pluginResponse);

		return $pluginResponse->getContent();
	}

}

?>