<?php

namespace Org\Gucken\Events;

/* *
 * This script belongs to the FLOW3 package "Events".          *
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
     * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
        require_once $this->packagePath.'Resources/Private/PHP/pickup/app/init.php';
		require_once $this->packagePath.'Resources/Private/PHP/ToDate/bootstrap.php';
		require_once $this->packagePath.'Resources/Private/PHP/SG-iCalendar/SG_iCal.php';

		$dispatcher = $bootstrap->getSignalSlotDispatcher();

		$dispatcher->connect('Org\Gucken\Events\Service\ImportEventFactoidsService', 'importStarted', 'Org\Gucken\Events\Service\ImportLogService', 'importStarted');
		$dispatcher->connect('Org\Gucken\Events\Service\ImportEventFactoidsService', 'factoidImported', 'Org\Gucken\Events\Service\ImportLogService', 'factoidImported');
		$dispatcher->connect('Org\Gucken\Events\Service\ImportEventFactoidsService', 'exceptionThrown', 'Org\Gucken\Events\Service\ImportLogService', 'exceptionThrown');
		$dispatcher->connect('Org\Gucken\Events\Service\ImportEventFactoidsService', 'importFinished', 'Org\Gucken\Events\Service\ImportLogService', 'importFinished');

    }

}

?>