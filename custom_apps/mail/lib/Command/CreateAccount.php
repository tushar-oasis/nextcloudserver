<?php

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * Mail
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

namespace OCA\Mail\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OCP\Security\ICrypto;
use OCA\Mail\Db\MailAccount;
use OCA\Mail\Service\AccountService;

class CreateAccount extends Command {

	const ARGUMENT_USER_ID = 'user-id';
	const ARGUMENT_NAME = 'name';
	const ARGUMENT_EMAIL = 'email';
	const ARGUMENT_IMAP_HOST = 'imap-host';
	const ARGUMENT_IMAP_PORT = 'imap-port';
	const ARGUMENT_IMAP_SSL_MODE = 'imap-ssl-mode';
	const ARGUMENT_IMAP_USER = 'imap-user';
	const ARGUMENT_IMAP_PASSWORD = 'imap-password';
	const ARGUMENT_SMTP_HOST = 'smtp-host';
	const ARGUMENT_SMTP_PORT = 'smtp-port';
	const ARGUMENT_SMTP_SSL_MODE = 'smtp-ssl-mode';
	const ARGUMENT_SMTP_USER = 'smtp-user';
	const ARGUMENT_SMTP_PASSWORD = 'smtp-password';

	/** @var AccountService */
	private $accountService;

	/** @var \OCP\Security\ICrypto */
	private $crypto;

	public function __construct(AccountService $service, ICrypto $crypto) {
		parent::__construct();

		$this->accountService = $service;
		$this->crypto = $crypto;
	}

	protected function configure() {
		$this->setName('mail:account:create');
		$this->setDescription('creates IMAP account');
		$this->addArgument(self::ARGUMENT_USER_ID, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_NAME, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_EMAIL, InputArgument::REQUIRED);

		$this->addArgument(self::ARGUMENT_IMAP_HOST, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_PORT, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_SSL_MODE, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_USER, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_IMAP_PASSWORD, InputArgument::REQUIRED);

		$this->addArgument(self::ARGUMENT_SMTP_HOST, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_PORT, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_SSL_MODE, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_USER, InputArgument::REQUIRED);
		$this->addArgument(self::ARGUMENT_SMTP_PASSWORD, InputArgument::REQUIRED);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$userId = $input->getArgument(self::ARGUMENT_USER_ID);
		$name = $input->getArgument(self::ARGUMENT_NAME);
		$email = $input->getArgument(self::ARGUMENT_EMAIL);

		$imapHost = $input->getArgument(self::ARGUMENT_IMAP_HOST);
		$imapPort = $input->getArgument(self::ARGUMENT_IMAP_PORT);
		$imapSslMode = $input->getArgument(self::ARGUMENT_IMAP_SSL_MODE);
		$imapUser = $input->getArgument(self::ARGUMENT_IMAP_USER);
		$imapPassword = $input->getArgument(self::ARGUMENT_IMAP_PASSWORD);

		$smtpHost = $input->getArgument(self::ARGUMENT_SMTP_HOST);
		$smtpPort = $input->getArgument(self::ARGUMENT_SMTP_PORT);
		$smtpSslMode = $input->getArgument(self::ARGUMENT_SMTP_SSL_MODE);
		$smtpUser = $input->getArgument(self::ARGUMENT_SMTP_USER);
		$smtpPassword = $input->getArgument(self::ARGUMENT_SMTP_PASSWORD);

		$account = new MailAccount();
		$account->setUserId($userId);
		$account->setName($name);
		$account->setEmail($email);

		$account->setInboundHost($imapHost);
		$account->setInboundPort($imapPort);
		$account->setInboundSslMode($imapSslMode);
		$account->setInboundUser($imapUser);
		$account->setInboundPassword($this->crypto->encrypt($imapPassword));

		$account->setOutboundHost($smtpHost);
		$account->setOutboundPort($smtpPort);
		$account->setOutboundSslMode($smtpSslMode);
		$account->setOutboundUser($smtpUser);
		$account->setOutboundPassword($this->crypto->encrypt($smtpPassword));

		$this->accountService->save($account);

		$output->writeln("<info>Account $email created</info>");
	}

}
