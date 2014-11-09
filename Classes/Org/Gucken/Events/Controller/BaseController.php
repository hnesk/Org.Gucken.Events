<?php

namespace Org\Gucken\Events\Controller;

/* *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
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

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\Flow\Mvc\View\ViewInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class BaseController extends ActionController
{
    const CREATION = 1;
    const MODIFICATION = 2;
    const OVERRIDE = 4;
    const EVERYTHING = 7;

    /**
     * Name of the argument to pass redirect information
     * @var string
     */
    protected $redirectArgumentName = 'redirect';

    /**
     * Name of the argument to pass redirect information
     * @var string
     */
    protected $redirectArgumentArrayName = 'redirectButton';

    /**
     * @var \TYPO3\Flow\Log\SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     *
     * @param  string $message
     * @param  int    $severity
     * @param  array  $additionalData
     * @return string
     */
    protected function log($message, $severity = LOG_INFO, $additionalData = array())
    {
        $this->systemLogger->log($message, $severity, $additionalData);
    }

    /**
     * Overriden to allow passsing of the redirect parameter
     */
    protected function initializeAction()
    {
        if (!empty($this->redirectArgumentName)) {
            $this->arguments->addNewArgument($this->redirectArgumentName, 'string', false, '');
        }

        if (!empty($this->redirectArgumentArrayName)) {
            $this->arguments->addNewArgument($this->redirectArgumentArrayName, 'array', false, array());
        }
        parent::initializeAction();
    }

    /**
     * @param string      $actionName
     * @param string|null $controllerName
     * @param string|null $packageKey
     * @param array       $arguments
     * @param int         $delay
     * @param int         $statusCode
     * @param string|null $format
     */
    protected function redirect(
        $actionName,
        $controllerName = null,
        $packageKey = null,
        array $arguments = null,
        $delay = 0,
        $statusCode = 303,
        $format = null
    ) {
        $redirectArgumentArray = $this->arguments->getArgument($this->redirectArgumentArrayName);
        $redirectArgument = $this->arguments->getArgument($this->redirectArgumentName);

        $redirectUri = null;
        if (count($redirectArgumentArray->getValue()) > 0) {
            $redirectUri = \key($redirectArgumentArray->getValue());
        } else {
            if ($redirectArgument->getValue()) {
                $redirectUri = $redirectArgument->getValue();
            }
        }
        if ($redirectUri) {
            if (strpos($redirectUri, 'https://') === false && strpos($redirectUri, 'http://') === false) {
                $redirectUri = $this->request->getHttpRequest()->getBaseUri() . $redirectUri;
            }
            $this->redirectToUri($redirectUri, $delay, $statusCode);
        } else {
            parent::redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
        }
    }

    /**
     * Shortcut for easier setting of property mapping configuration for nested objects
     *
     * @param  string                    $argument
     * @param  string                    $propertyPath
     * @param $callback
     * @throws \InvalidArgumentException
     */
    protected function preprocessProperty($argument, $propertyPath, $callback)
    {
        $data = $this->request->getArgument($argument);
        if (!is_callable($callback)) {
            if (!is_string($callback)) {
                throw new \InvalidArgumentException('callback must be callable or a property name');
            }
            $requiredKey = (string) $callback;
            $callback = function ($argument) use ($requiredKey) {
                return trim($argument[$requiredKey]) !== '' ? $argument : null;
            };
        }
        $propertyPathParts = explode('.', $propertyPath);
        $data = $this->_preprocessProperty($data, $propertyPathParts, $callback);
        $this->request->setArgument($argument, $data);
    }

    /**
     * @param $data
     * @param $propertyPathParts
     * @param $callback
     * @return mixed
     */
    private function _preprocessProperty($data, $propertyPathParts, $callback)
    {
        $currentProperty = array_shift($propertyPathParts);

        // * = recursive call for all keys
        if ($currentProperty === '*') {
            foreach ($data as $currentProperty => $subData) {
                $data[$currentProperty] = $this->_preprocessProperty($subData, $propertyPathParts, $callback);
                if (!isset($data[$currentProperty])) {
                    unset($data[$currentProperty]);
                }
            }
        } // recursive call for given property
        else {
            if (!empty($currentProperty)) {
                $subData = isset($data[$currentProperty]) ? $data[$currentProperty] : array();
                $data[$currentProperty] = $this->_preprocessProperty($subData, $propertyPathParts, $callback);
                if (!isset($data[$currentProperty])) {
                    unset($data[$currentProperty]);
                }
            } else {
                $data = $callback($data);
            }
        }

        return $data;
    }

    /**
     * Shortcut for easier setting of property mapping configuration for nested objects
     *
     * @param string $argument
     * @param string $propertyPath
     * @param int    $propertyFlags allow creation and/or modification, bitfield of self::CREATION / self::MODIFICATION
     */
    protected function allowForProperty($argument, $propertyPath, $propertyFlags = self::EVERYTHING)
    {
        $data = $this->request->getArgument($argument);
        $propertyMappingConfiguration = $this->arguments[$argument]->getPropertyMappingConfiguration();
        /* @var $propertyMapping MvcPropertyMappingConfiguration */
        $propertyPathParts = explode('.', $propertyPath);
        $this->applyAllowForProperty($propertyMappingConfiguration, $data, $propertyPathParts, $propertyFlags);
    }

    /**
     *
     * @param MvcPropertyMappingConfiguration $propertyMappingConfiguration
     * @param array                           $data
     * @param array                           $subPropertyPathParts
     * @param int                             $propertyFlags                allow creation and/or modification, bitfield of self::CREATION / self::MODIFICATION
     */
    private function applyAllowForProperty(
        MvcPropertyMappingConfiguration $propertyMappingConfiguration,
        $data,
        $subPropertyPathParts,
        $propertyFlags
    ) {
        if (empty($data)) {
            return;
        }
        $currentProperty = array_shift($subPropertyPathParts);

        // * = recursive call for all keys
        if ($currentProperty === '*') {
            foreach ($data as $currentProperty => $subData) {
                $subPropertyMappingConfiguration = $propertyMappingConfiguration->forProperty($currentProperty);
                $this->applyAllowForProperty(
                    $subPropertyMappingConfiguration,
                    $subData,
                    $subPropertyPathParts,
                    $propertyFlags
                );
            }
        } // recursive call for given property
        else {
            if (!empty($currentProperty)) {
                $subPropertyMappingConfiguration = $propertyMappingConfiguration->forProperty($currentProperty);
                $subData = isset($data[$currentProperty]) ? $data[$currentProperty] : array();
                $this->applyAllowForProperty(
                    $subPropertyMappingConfiguration,
                    $subData,
                    $subPropertyPathParts,
                    $propertyFlags
                );
            } else {
                if ($propertyFlags && self::CREATION) {
                    $propertyMappingConfiguration->setTypeConverterOption(
                        'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
                        PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                        true
                    );
                }
                if ($propertyFlags && self::MODIFICATION) {
                    $propertyMappingConfiguration->setTypeConverterOption(
                        'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
                        PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED,
                        true
                    );
                }
                if ($propertyFlags && self::OVERRIDE) {
                    $propertyMappingConfiguration->setTypeConverterOption(
                        'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
                        PersistentObjectConverter::CONFIGURATION_OVERRIDE_TARGET_TYPE_ALLOWED,
                        true
                    );
                }
            }
        }
    }

    /**
     * Overriden to allow template switching
     *
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        /* @var $view \TYPO3\Fluid\View\TemplateView */
        $currentView = $this->settings['currentView'];
        $view->setLayoutRootPath($this->settings['views'][$currentView]['layoutRootPath']);
        $view->setTemplateRootPath($this->settings['views'][$currentView]['templateRootPath']);
        $view->setPartialRootPath($this->settings['views'][$currentView]['partialRootPath']);
        $view->assign('skinPackage', $this->settings['views'][$currentView]['skinPackage']);
    }

    /**
     *
     * @return boolean
     */
    public function isHtmlRequest()
    {
        return strpos($this->request->getHttpRequest()->getHeaders()->get('Accept'), 'text/html') !== false;
    }

    /**
     *
     * @param  \Traversable|array $collection
     * @param  string             $label
     * @return array
     */
    public function addDummyEntry($collection, $label = '---')
    {
        if ($collection instanceof QueryResultInterface) {
            $collection = $collection->toArray();
        }

        return array('' => $label) + $collection;
    }
}
