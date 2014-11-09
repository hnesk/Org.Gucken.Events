<?php

namespace Org\Gucken\Events\Domain\Model;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
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

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Reflection\ReflectionService;

/**
 * An identifier for a location on an external website or service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("singleton")
 */
class ExternalLocationIdentifierFactory
{

    /**
     *
     * @var ObjectManagerInterface
     * @Flow\Inject
     */
    protected $objectManager;

    /**
     *
     * @var ReflectionService
     * @Flow\Inject
     */
    protected $reflectionService;

    public function getIdentifierOptions()
    {
        $options = array(
            '' => '---'
        );
        $classNames = $this->reflectionService->getAllSubClassNamesForClass(ExternalLocationIdentifier::class);
        foreach ($classNames as $className) {
            $identifierInstance = $this->objectManager->get($className);
            $options[$className] = $identifierInstance->getSchemeLabel();
        }

        return $options;
    }

    /**
     *
     * @param  string                                              $prototypeClass
     * @param  Location                                            $location
     * @return array|ExternalLocationIdentifier[]
     */
    public function getCandidatesForLocation($prototypeClass, Location $location)
    {
        $result = array();
        if ($prototypeClass) {
            $identifierMasterInstance = $this->objectManager->get($prototypeClass);
            if ($identifierMasterInstance) {
                /* @var $identifierMasterInstance ExternalLocationIdentifier */
                $result = $identifierMasterInstance->getCandidates($location);
            }
        }

        return $result;
    }

}
