<?php

namespace Scriptme\MarkdownContent\Controller;

use TYPO3\Flow\Annotations as Flow;
use \Scriptme\MarkdownContent\Utility\Files as Files;

/**
 * Standard controller for the Scriptme.MarkdownContent package 
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\Flow\Session\SessionInterface
	 * @Flow\Inject
	 */
	protected $session;

	/**
	 * Index action
	 *
	 * @Flow\Session(autoStart = TRUE)
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	public function indexAction($path = '')
	{
		$packageKey = $this->controllerContext->getRequest()->getControllerPackageKey();
		$subpackageKey = $this->controllerContext->getRequest()->getControllerSubpackageKey();
		$baseDirectory = Files::contentBaseDirectory($packageKey, $subpackageKey);

		$fsPath = str_replace('-', '/', urldecode($path));

		$metaData = Files::fetchMetaInformationForDirectory($baseDirectory . '/' . $fsPath);
		// $contents = Files::getFilesInPath($baseDirectory . '/' . $path, 'md');

		$this->session->putData('currentPath', $path);
		$this->view->assign('currentPath', urldecode($path));
		$this->view->assign('metaData', $metaData);
	}



}

?>