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

		$path = str_replace(array('../', '.html'), '', $path);
		$fsPath = $baseDirectory . '/' . $path;

		if (!file_exists($fsPath) || !is_dir($fsPath)) {
			$this->throwStatus(404);
		}

		$metaData = Files::fetchMetaInformationForDirectory($fsPath);

		$this->session->putData('currentPath', $path);
		$this->view->assign('currentPath', urldecode($path));
		$this->view->assign('metaData', $metaData);
	}



}

?>