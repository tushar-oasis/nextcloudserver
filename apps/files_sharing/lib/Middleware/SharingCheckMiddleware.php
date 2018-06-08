<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Bjoern Schiessle <bjoern@schiessle.org>
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Files_Sharing\Middleware;

use OCA\Files_Sharing\Controller\ExternalSharesController;
use OCA\Files_Sharing\Controller\ShareController;
use OCP\App\IAppManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\AppFramework\Middleware;
use OCP\Files\NotFoundException;
use OCP\IConfig;
use OCP\AppFramework\Utility\IControllerMethodReflector;
use OCA\Files_Sharing\Exceptions\S2SException;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\Share\IManager;
use OCP\Share\Exceptions\ShareNotFound;

/**
 * Checks whether the "sharing check" is enabled
 *
 * @package OCA\Files_Sharing\Middleware
 */
class SharingCheckMiddleware extends Middleware {

	/** @var string */
	protected $appName;
	/** @var IConfig */
	protected $config;
	/** @var IAppManager */
	protected $appManager;
	/** @var IControllerMethodReflector */
	protected $reflector;
	/** @var IManager */
	protected $shareManager;
	/** @var IRequest */
	protected $request;

	/***
	 * @param string $appName
	 * @param IConfig $config
	 * @param IAppManager $appManager
	 * @param IControllerMethodReflector $reflector
	 * @param IManager $shareManager
	 * @param IRequest $request
	 */
	public function __construct($appName,
								IConfig $config,
								IAppManager $appManager,
								IControllerMethodReflector $reflector,
								IManager $shareManager,
								IRequest $request
								) {
		$this->appName = $appName;
		$this->config = $config;
		$this->appManager = $appManager;
		$this->reflector = $reflector;
		$this->shareManager = $shareManager;
		$this->request = $request;
	}

	/**
	 * Check if sharing is enabled before the controllers is executed
	 *
	 * @param Controller $controller
	 * @param string $methodName
	 * @throws NotFoundException
	 * @throws S2SException
	 * @throws ShareNotFound
	 */
	public function beforeController($controller, $methodName) {
		if(!$this->isSharingEnabled()) {
			throw new NotFoundException('Sharing is disabled.');
		}

		if ($controller instanceof ExternalSharesController &&
			!$this->externalSharesChecks()) {
			throw new S2SException('Federated sharing not allowed');
		} else if ($controller instanceof ShareController) {
			$token = $this->request->getParam('token');
			$share = $this->shareManager->getShareByToken($token);
			if ($share->getShareType() === \OCP\Share::SHARE_TYPE_LINK
				&& !$this->isLinkSharingEnabled()) {
				throw new NotFoundException('Link sharing is disabled');
			}
		}
	}

	/**
	 * Return 404 page in case of a not found exception
	 *
	 * @param Controller $controller
	 * @param string $methodName
	 * @param \Exception $exception
	 * @return NotFoundResponse
	 * @throws \Exception
	 */
	public function afterException($controller, $methodName, \Exception $exception) {
		if(is_a($exception, '\OCP\Files\NotFoundException')) {
			return new NotFoundResponse();
		}

		if (is_a($exception, '\OCA\Files_Sharing\Exceptions\S2SException')) {
			return new JSONResponse($exception->getMessage(), 405);
		}

		throw $exception;
	}

	/**
	 * Checks for externalshares controller
	 * @return bool
	 */
	private function externalSharesChecks() {

		if (!$this->reflector->hasAnnotation('NoIncomingFederatedSharingRequired') &&
			$this->config->getAppValue('files_sharing', 'incoming_server2server_share_enabled', 'yes') !== 'yes') {
			return false;
		}

		if (!$this->reflector->hasAnnotation('NoOutgoingFederatedSharingRequired') &&
		    $this->config->getAppValue('files_sharing', 'outgoing_server2server_share_enabled', 'yes') !== 'yes') {
			return false;
		}

		return true;
	}

	/**
	 * Check whether sharing is enabled
	 * @return bool
	 */
	private function isSharingEnabled() {
		// FIXME: This check is done here since the route is globally defined and not inside the files_sharing app
		// Check whether the sharing application is enabled
		if(!$this->appManager->isEnabledForUser($this->appName)) {
			return false;
		}

		return true;
	}

	/**
	 * Check if link sharing is allowed
	 * @return bool
	 */
	private function isLinkSharingEnabled() {
		// Check if the shareAPI is enabled
		if ($this->config->getAppValue('core', 'shareapi_enabled', 'yes') !== 'yes') {
			return false;
		}

		// Check whether public sharing is enabled
		if($this->config->getAppValue('core', 'shareapi_allow_links', 'yes') !== 'yes') {
			return false;
		}

		return true;
	}

}
