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
namespace OCA\Mail\Model;

use Horde_Mail_Rfc822_List;
use OCA\Mail\AddressList;
use OCA\Mail\Db\LocalAttachment;
use OCP\Files\File;
use OCP\Files\SimpleFS\ISimpleFile;

interface IMessage {

	/**
	 * Get the ID if available
	 *
	 * @return int|null
	 */
	public function getMessageId();

	/**
	 * Get all flags set on this message
	 * 
	 * @return array
	 */
	public function getFlags();

	/**
	 * @param array $flags
	 */
	public function setFlags(array $flags);

	/**
	 * @return AddressList
	 */
	public function getFrom();

	/**
	 * @param string $from
	 */
	public function setFrom(AddressList $from);

	/**
	 * @return AddressList
	 */
	public function getTo();

	/**
	 * @param AddressList $to
	 */
	public function setTo(AddressList $to);

	/**
	 * @return AddressList
	 */
	public function getCC();

	/**
	 * @param AddressList $cc
	 */
	public function setCC(AddressList $cc);

	/**
	 * @return AddressList
	 */
	public function getBCC();

	/**
	 * @param AddressList $bcc
	 */
	public function setBcc(AddressList $bcc);

	/**
	 * @return IMessage
	 */
	public function getRepliedMessage();

	/**
	 * @param IMessage $message
	 */
	public function setRepliedMessage(IMessage $message);

	/**
	 * @return string
	 */
	public function getSubject();

	/**
	 * @param string $subject
	 */
	public function setSubject($subject);

	/**
	 * @return string
	 */
	public function getContent();

	/**
	 * @param string $content
	 */
	public function setContent($content);

	/**
	 * @return File[]
	 */
	public function getCloudAttachments();

	/**
	 * @return int[]
	 */
	public function getLocalAttachments();

	/**
	 * @param File $fileName
	 */
	public function addAttachmentFromFiles(File $fileName);

	/**
	 * @param LocalAttachment $attachment
	 * @param ISimpleFile $file
	 */
	public function addLocalAttachment(LocalAttachment $attachment, ISimpleFile $file);
}
