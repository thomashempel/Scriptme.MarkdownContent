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
	 * @var array
	 */
	protected $settings;

	/**
	 *
	 * @var \Scriptme\MarkdownContent\Domain\Service\Context
	 * @Flow\inject
	 */
	protected $context;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings)
	{
		$this->settings = $settings;
	}

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
		$externalPackage = FALSE;

		if (isset($this->settings['SitePackage'])) {
			$packageKey = $this->settings['SitePackage'];
			$subpackageKey = NULL;
		} else {
			$packageKey = $this->controllerContext->getRequest()->getControllerPackageKey();
			$subpackageKey = $this->controllerContext->getRequest()->getControllerSubpackageKey();
		}

		$packageResourcesDirectory = Files::packageResourcesDirectory($packageKey);
		$packageContentDirectory = Files::packageContentDirectory($packageKey, $subpackageKey);

		$path = str_replace(array('../', '.html'), '', $path);
		$pagePathInFilesystem = $packageContentDirectory . $path;

		if (!file_exists($pagePathInFilesystem) || !is_dir($pagePathInFilesystem)) {
			$this->throwStatus(404);
		}

		$metaData = Files::fetchMetaInformationForDirectory($pagePathInFilesystem);
		if (!isset($metaData['Layout'])) $metaData['Layout'] = 'Default';
		if (!isset($metaData['Template'])) $metaData['Template'] = 'Default';

		//Create the Context, this will be use in the viewhelper
		$this->context->setResourceDirectory($packageResourcesDirectory);
		$this->context->setContentDirectory($packageContentDirectory);
		$this->context->setCurrentPath($path);
		$this->context->setMetdaData($metaData);
		$this->context->setSitePackageKey($packageKey);

		$this->session->putData('currentPath', $path);

		$this->view->setTemplatePathAndFilename($packageResourcesDirectory.'Private/Templates/'.$metaData['Template'].'.html');

		$this->view->setLayoutRootPath($packageResourcesDirectory.'Private/Templates/Page/');
		$this->view->setTemplateRootPath($packageResourcesDirectory.'Private/Templates/');
		$this->view->setPartialRootPath($packageResourcesDirectory.'Private/Partials/');

		$this->view->assign('currentPath', urldecode($path));
		$this->view->assign('metaData', $metaData);
	}



}

?>