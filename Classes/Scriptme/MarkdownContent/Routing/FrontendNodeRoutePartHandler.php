<?php
namespace Scriptme\MarkdownContent\Routing;

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
 * A route part handler for finding nodes specifically in the website's frontend.
 *
 * @Flow\Scope("singleton")
 */
class FrontendNodeRoutePartHandler extends \TYPO3\Flow\Mvc\Routing\DynamicRoutePart
{

	/**
	 * Extracts the node path from the request path.
	 *
	 * @param string $requestPath The request path to be matched
	 * @return string value to match, or an empty string if $requestPath is empty or split string was not found
	 */
	protected function findValueToMatch($requestPath)
	{
		// var_dump("findValueToMatch($requestPath)");
		return $requestPath;
	}

	protected function matchValue($requestPath)
	{
		// var_dump("matchValue($requestPath)");
		$this->value = $requestPath;
		return TRUE;
	}

	protected function resolveValue($value)
	{
		// var_dump("resolveValue($value)");
		$this->value = $value;
		return TRUE;
	}

}
?>