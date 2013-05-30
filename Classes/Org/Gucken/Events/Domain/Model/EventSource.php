<?php

namespace Org\Gucken\Events\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * This script belongs to the FLOW3 package "Events".                     *
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

/**
 * An event source
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class EventSource {

    /**
     * The name
     * @var string
     */
    protected $name;

    /**
     *
     * @var array
     */
    protected $parameters;


    /**
     * The name of the class implementing the event fetching
     * @var string
     */
    protected $implementationClass;

	/**
	 *
	 * @var boolean
	 */
	protected $active;

	/**
	 *
	 * @var string
	 */
	protected $code;


	/**
	 *
	 * @var string
	 */
	protected $style;


	/**
     * @Flow\Inject
     * @var \TYPO3\Flow\Property\PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @Flow\Inject
     * @var Org\Gucken\Rad\Service\ReflectionService
     */
    protected $repositoryReflectionService;

    /**
     * @Flow\Inject
     * @var Org\Gucken\Events\Domain\Repository\ImportLogEntryRepository
     */
    protected $importLogEntryRepository;

    /**
     * @var \TYPO3\Flow\Reflection\ReflectionService
     */
    protected $reflectionService;

    /**
     * @var \TYPO3\Flow\Object\ObjectManager
     */
    protected $objectManager;



	/**
	 * @param \TYPO3\Flow\Reflection\ReflectionService $reflectionService
	 */
	public function injectReflectionService(\TYPO3\Flow\Reflection\ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * @param \TYPO3\Flow\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\Flow\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * @var \TYPO3\Flow\Validation\ValidatorResolver
	 */
	protected $validatorResolver;

	/**
	 * Injects the validator resolver
	 *
	 * @param \TYPO3\Flow\Validation\ValidatorResolver $validatorResolver
	 * @return void
	 */
	public function injectValidatorResolver(\TYPO3\Flow\Validation\ValidatorResolver $validatorResolver) {
		$this->validatorResolver = $validatorResolver;
	}
    /**
     * Get the source name
     *
     * @return string The Sources name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets this sources name
     *
     * @param string $name The Sources name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     *
     * @param array $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     *
     * @return string
     */
    public function getParameter($key) {
        return $this->parameters[$key];
    }


	/**
	 *
	 * @param boolean $active
	 */
	public function setActive($active) {
		$this->active = (boolean)$active;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isActive() {
		return (boolean)$this->active;
	}

	/**
	 *
	 * @return boolean
	 */
	public function getCanConvertLocation() {
		return method_exists($this->getImplementation(), 'convertLocation');
	}

	/**
	 *
	 * @return Location
	 */
	public function convertLocation(EventFactoid $factoid) {
		return $this->getCanConvertLocation() ? $this->getImplementation()->convertLocation($factoid) : null;
	}


	/**
	 *
	 * @return boolean
	 */
	public function getCanConvertLink() {
		return $this->getImplementationClass()  && method_exists($this->getImplementation(), 'convertLink');
	}


	/**
	 * @param EventFactoidIdentity $factoidIdentity
	 * @return EventLink
	 */
	public function convertLink(EventFactoidIdentity $factoidIdentity) {
		$link = null;
		/* @var $link EventLink */
		if ($this->getCanConvertLink()) {
			$link = $this->getImplementation()->convertLink($factoidIdentity);
			$link->setFactoidIdentity($factoidIdentity);
		}
		return $link;
	}


    /**
     * @param string
     * @return void
     */
    public function setImplementationClass($implementationClass) {
        if (!\class_exists($implementationClass)) {
            throw new \TYPO3\Flow\Aop\Exception\InvalidArgumentException('Argument needs to be a class name, "' . $implementationClass . '" given', 1314480311);
        }
		$implementation = $this->objectManager->get($implementationClass);
		if (!$implementation instanceof \Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface) {
            throw new \TYPO3\Flow\Aop\Exception\InvalidArgumentException('Argument needs to implement Org\Gucken\Events\Domain\Model\EventSource\EventSourceInterface, ' . $implementationClass . ' given', 1314480281);
        }
        $this->implementationClass = $implementationClass;
    }

    /**
     * @return string
     */
    public function getImplementationClass() {
        return $this->implementationClass;
    }

	/**
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 *
	 * @param string $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 *
	 * @return string
	 */
	public function getStyle() {
		return $this->style;
	}

	/**
	 *
	 * @param string $style
	 */
	public function setStyle($style) {
		$this->style = $style;
	}


    /**
     *
     * @return array
     */
    public function getParameterProperties() {
        $properties = array();
        foreach ($this->getParameterHints() as $key => $type) {
            if (!empty($this->parameters[$key]) && !is_null($this->parameters[$key])) {
				try {
					$rawValue = $this->parameters[$key];
					$repository = $this->repositoryReflectionService->getRepositoryFor($type);
					if ($repository && $repository->findByIdentifier($rawValue)) {
						$value = $this->propertyMapper->convert($rawValue, $type);
					} else if (\TYPO3\Flow\Utility\TypeHandling::isLiteral($type)) {
						$value = $rawValue;
					} else {
						$value = $this->propertyMapper->convert($rawValue, $type);
					}
				} catch (\Exception $e) {
					$value = '';
				}
			} else {
                if (\TYPO3\Flow\Utility\TypeHandling::isLiteral($type)) {
                    $value = '';
                } else {
                    $value = $this->objectManager->get($type);
                }
            }
            $properties[$key] = $value;
        }
        return $properties;
    }

    /**
     *
     * @return array
     */
    protected function getParameterHints() {
        $parameters = array();
		if ($this->getImplementationClass()) {
			$configurableProperties = $this->reflectionService->getPropertyNamesByTag($this->getImplementationClass(), 'configurable');
			foreach ($configurableProperties as $configurableProperty) {
				$types = $this->reflectionService->getPropertyTagValues($this->getImplementationClass(), $configurableProperty, 'var');
				$parameters[$configurableProperty] = current($types);
			}
		}
        return $parameters;
    }


    /**
     *
     * @return \Doctrine\Common\Collections\Collection<Org\Gucken\Events\Domain\Model\EventFactoid>
     */
    public function getEventFactoids() {
        $eventFactoids = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getImplementation()->getEvents() as $eventFactoidRecord) {
            $eventFactoid = new EventFactoid($eventFactoidRecord);
            $eventFactoid->setSource($this);
            $eventFactoids[] = $eventFactoid;
        }
        return $eventFactoids;
    }

    /**
     *
     * @return EventSourceImplementation
     */
    public function getImplementation() {
        // create object
        $implementation = $this->objectManager->get($this->getImplementationClass());
        // configure object
        foreach ($this->getParameterProperties() as $key => $property) {
			try {
				\TYPO3\Flow\Reflection\ObjectAccess::setProperty($implementation, $key, $property);
			} catch (\Exception $e) {}
        }
        return $implementation;
    }

	/**
	 *
	 * @return \TYPO3\Flow\Error\Result
	 */
	public function validate() {
		$implementation = $this->getImplementation();
		$validator = $this->validatorResolver->getBaseValidatorConjunction(get_class($implementation));
		return $validator->validate($implementation);

	}

	/**
	 *
	 * @return ImportLogEntry
	 */
	public function getLastLogEntry() {
		return $this->importLogEntryRepository->findLastBySource($this);
	}

	/**
	 *
	 * @param \DateTime $start
	 * @return array
	 */
	public function getLogEntries() {
		$start = new \DateTime('-2 weeks');
		return $this->importLogEntryRepository->findBySourceAndDate($this, $start);
	}

	/**
	 * @return ImportLogEntry
	 */
	public function createLogEntry() {
		return new ImportLogEntry($this, new \DateTime());
	}

    public function __toString() {
        return $this->getName() . ' (' . $this->getImplementationClass().')';
    }

}

?>