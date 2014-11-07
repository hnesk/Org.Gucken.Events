<?php

namespace Org\Gucken\Events;

/* *
 * This script belongs to the Flow package "Events".          *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Org\Gucken\Events\Service\ImportEventFactoidsService as FactoidsService;
use Org\Gucken\Events\Service\ImportLogService as LogService;
use TYPO3\Flow\Core\Bootstrap;
use \TYPO3\Flow\Package\Package as BasePackage;

/**
 * The Events Package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Package extends BasePackage {

    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap) {
        /** @noinspection PhpIncludeInspection */
        require_once $this->packagePath.'Resources/Private/PHP/pickup/app/init.php';
        /** @noinspection PhpIncludeInspection */
		require_once $this->packagePath.'Resources/Private/PHP/SG-iCalendar/SG_iCal.php';

		$dispatcher = $bootstrap->getSignalSlotDispatcher();

		$dispatcher->connect(FactoidsService::class, 'importStarted'  , LogService::class, 'importStarted');
		$dispatcher->connect(FactoidsService::class, 'factoidImported', LogService::class, 'factoidImported');
		$dispatcher->connect(FactoidsService::class, 'exceptionThrown', LogService::class, 'exceptionThrown');
		$dispatcher->connect(FactoidsService::class, 'importFinished' , LogService::class, 'importFinished');

    }

}

?>