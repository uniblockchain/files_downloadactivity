<?php
/**
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\FilesDownloadActivity\AppInfo;

use OCA\FilesDownloadActivity\Activity\Extension;
use OCA\FilesDownloadActivity\Activity\Listener;
use OCP\Util;

class Application extends \OCP\AppFramework\App {

	/** @var string */
	protected $identifier;
	/** @var string|null */
	protected $sessionUser;

	public function __construct() {
		parent::__construct('files_downloadactivity');
	}

	/**
	 * Register all hooks and listeners
	 */
	public function register() {
		Util::connectHook('OC_Filesystem', 'read', $this, 'listenReadFile');

		$this->getContainer()->getServer()->getActivityManager()->registerExtension(function() {
			return $this->getContainer()->query(Extension::class);
		});
	}

	/**
	 * @param array $params
	 */
	public function listenReadFile($params) {
		/** @var Listener $hooks */
		$hooks = $this->getContainer()->query(Listener::class);
		$hooks->readFile($params['path']);
	}
}
