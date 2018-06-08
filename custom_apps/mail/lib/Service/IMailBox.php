<?php

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
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
namespace OCA\Mail\Service;

use Horde_Imap_Client_Search_Query;
use OCA\Mail\Attachment;
use OCA\Mail\Model\IMessage;

interface IMailBox {

	/**
	 * @return string
	 */
	public function getFolderId();

	/**
	 * @param string|Horde_Imap_Client_Search_Query $filter
	 * @param int $cursorId last known ID on the client
	 * @return array
	 */
	public function getMessages($filter = null, $cursorId = null);

	/**
	 * @return string
	 */
	public function getSpecialRole();

	/**
	 * @param int $id
	 * @return IMessage
	 */
	public function getMessage($id, $loadHtmlMessageBody = false);

	/**
	 * @param int $messageId
	 * @param string $attachmentId
	 * @return Attachment
	 */
	public function getAttachment($messageId, $attachmentId);

	/**
	 * @param int $messageId
	 * @param string $flag
	 * @param mixed $value
	 */
	public function setMessageFlag($messageId, $flag, $value);

	/**
	 * @return array
	 */
	public function getStatus();

}
