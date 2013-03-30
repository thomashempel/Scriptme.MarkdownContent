<?php
namespace Scriptme\MarkdownContent\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Scriptme.MarkdownContent".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Scriptme.MarkdownContent package 
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * Index action
	 *
	 * @param string $path
	 * @return void
	 */
	public function indexAction($path = '') {
		$this->view->assign('currentPath', urldecode($path));
	}

}

?>