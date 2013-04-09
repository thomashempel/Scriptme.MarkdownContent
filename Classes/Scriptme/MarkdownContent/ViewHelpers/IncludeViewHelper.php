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
	 * @param $package
	 * @param $controller
	 * @param $action
	 *
	 * @return string
	 */
	public function render($package, $controller, $action)
	{
		// return $package.'/'.$controller.'->'.$action;

		$parentRequest = $this->controllerContext->getRequest();
		$pluginRequest = new ActionRequest($parentRequest);
		$pluginRequest->setArgumentNamespace('--' . 'test');
		//$this->passArgumentsToPluginRequest($pluginRequest);

		$pluginRequest->setArguments($parentRequest->getArguments());
		//$pluginNamespace = $this->getPluginNamespace();
		$pluginRequest->setControllerPackageKey($package);
		$pluginRequest->setControllerName($controller);
		$pluginRequest->setControllerActionName($action);

		$parentResponse = $this->controllerContext->getResponse();
		$pluginResponse = new Response($parentResponse);

		$this->dispatcher->dispatch($pluginRequest, $pluginResponse);

		return $pluginResponse->getContent();
	}

}

?>