<?php

use OCA\GroupFolders\AppInfo\Application;

$app = new Application();
$app->register();

$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener(
	'OCA\Files::loadAdditionalScripts',
	function () {
		\OC_Util::addScript('groupfolders', 'files');
	}
);

$eventDispatcher->addListener('OCA\Files_Sharing::loadAdditionalScripts', function () {
	\OC_Util::addScript('groupfolders', 'files');
});
