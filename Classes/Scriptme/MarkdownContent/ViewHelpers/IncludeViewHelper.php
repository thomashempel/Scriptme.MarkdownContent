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
		// $pluginRequest->setArgumentNamespace('--' . $this->getPluginNamespace());
		// $this->passArgumentsToPluginRequest($pluginRequest);

		$pluginRequest->setArguments($parentRequest->getArguments());
		// $pluginNamespace = $this->getPluginNamespace();
		$pluginRequest->setControllerPackageKey($package);
		$pluginRequest->setControllerName($controller);
		$pluginRequest->setControllerActionName($action);

		$parentResponse = $this->controllerContext->getResponse();
		$pluginResponse = new Response($parentResponse);

		$this->dispatcher->dispatch($pluginRequest, $pluginResponse);

		return $pluginResponse->getContent();

		/*
		if ($this->node instanceof \TYPO3\TYPO3CR\Domain\Model\PersistentNodeInterface) {
			if ($pluginRequest->getControllerPackageKey() === NULL) {
				$pluginRequest->setControllerPackageKey($this->node->getProperty('package') ?: $this->package);
			}
			if ($pluginRequest->getControllerSubpackageKey() === NULL) {
				$pluginRequest->setControllerSubpackageKey($this->node->getProperty('subpackage') ?: $this->subpackage);
			}
			if ($pluginRequest->getControllerName() === NULL) {
				$pluginRequest->setControllerName($this->node->getProperty('controller') ?: $this->controller);
			}
			if ($this->action === NULL) {
				$this->action = 'index';
			}
			if ($pluginRequest->getControllerActionName() === NULL) {
				$pluginRequest->setControllerActionName($this->node->getProperty('action') ?: $this->action);
			}

			// TODO: Check if we want to use all properties as arguments
			//     This enables us to configure plugin controller arguments via
			//     node type definitions for now.
			foreach ($this->node->getProperties() as $propertyName => $propertyValue) {
				$propertyName = '--' . $propertyName;
				if (!in_array($propertyName, array('--package', '--subpackage', '--controller', '--action', '--format')) && !$pluginRequest->hasArgument($propertyName)) {
					$pluginRequest->setArgument($propertyName, $propertyValue);
				}
			}
		} else {
			$pluginRequest->setControllerPackageKey($this->getPackage());
			$pluginRequest->setControllerSubpackageKey($this->getSubpackage());
			$pluginRequest->setControllerName($this->getController());
			$pluginRequest->setControllerActionName($this->getAction());
		}
		return $pluginRequest;
		*/
	}

}

?>