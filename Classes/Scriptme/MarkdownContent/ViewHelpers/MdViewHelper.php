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

use \Michelf\Markdown as Md;

class MdViewHelper extends \Scriptme\MarkdownContent\ViewHelpers\AbstractMdViewHelper
{

	protected function parseMarkDown($markdown)
	{
		return Md::defaultTransform($markdown);
	}
}

?>