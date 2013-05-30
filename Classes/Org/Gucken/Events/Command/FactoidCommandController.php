<?php

namespace Org\Gucken\Events\Command;

/* *
 * This script belongs to the FLOW3 package "Kickstart".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Gesneral Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Org\Gucken\Events\Domain\Model\Type as Type;
use Org\Gucken\Events\Domain\Model\Location as Location;
use Org\Gucken\Events\Domain\Model\PostalAddress as PostalAddress;
use Org\Gucken\Events\Domain\Model\GeoCoordinates as GeoCoordinates;
use TYPO3\FLOW3\Annotations as FLOW3;
use TYPO3\FLOW3\Cli\CommandController as CommandController;

/**
 * Command controller for the Importer
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FactoidCommandController extends CommandController {

    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Service\ImportEventFactoidsService
     */
    protected $importService;

    /**
     * @FLOW3\Inject
     * @var \Org\Gucken\Events\Domain\Repository\EventSourceRepository
     */
    protected $sourceRepository;


    /**
     * Import factoids from given source
     * @param $name string
     * @return string
     */
    public function importCommand($name) {
        $message = '';
        $source = $this->sourceRepository->findOneByName($name);
        if (empty ($source)) {
            $message = sprintf('Source "%s" nout found',$name);
        } else {
            $count = $this->importService->importSource($source);
            $message = sprintf('imported %d factoids for source "%s" ', $count, $name);
        }
        return $message.PHP_EOL;
    }

    /**
     * Imports factoids from all sources
     * @return string
     */
    public function importAllCommand() {
        $count = $this->importService->import();
        $this->outputLine('imported %d Factoids',array($count));
    }

    /**
     * Show factoids for one source
     * @param string $name source name
     * @param string $filter optionally filter by title
     */
    public function showCommand($name, $filter = '') {
           $source = $this->sourceRepository->findOneByName($name);
           /* @var $source \Org\Gucken\Events\Domain\Model\EventSource */

           foreach ($source->getImplementation()->getEvents() as $factoid) {
               if ($filter && strpos($factoid->getTitle(),$filter) === false) {
                   continue;
               }
               var_dump($factoid->getNativeValue());
           }


    }


}

?>