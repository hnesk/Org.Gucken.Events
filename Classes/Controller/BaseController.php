<?php

namespace Org\Gucken\Events\Controller;

/* *
 * This script belongs to the FLOW3 package "Events".                     *
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

use Org\Gucken\Events\Domain\Model;

/**
 * Standard controller for the Events package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class BaseController extends \TYPO3\FLOW3\MVC\Controller\ActionController {
	const CREATION = 1;
	const MODIFICATION = 2;
	const EVERYTHING = 3;

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
     * Overriden to allow passsing of the redirect parameter
     */
    protected function initializeAction() {
        if (!empty($this->redirectArgumentName)) {
            $this->arguments->addNewArgument($this->redirectArgumentName,'string',false, '');
        }
        
        if (!empty($this->redirectArgumentArrayName)) {
            $this->arguments->addNewArgument($this->redirectArgumentArrayName,'array',false, array());
        }
        
        parent::initializeAction();
    }


    protected function redirect($actionName, $controllerName = NULL, $packageKey = NULL, array $arguments = NULL, $delay = 0, $statusCode = 303, $format = NULL) {
        $redirectArgumentArray = $this->arguments[$this->redirectArgumentArrayName];
        /* @var $redirectArgumentArray \TYPO3\FLOW3\MVC\Controller\Argument */
        $redirectArgument = $this->arguments[$this->redirectArgumentName];
        /* @var $redirectArgument \TYPO3\FLOW3\MVC\Controller\Argument */

        $redirectUri = null;        
        if (count($redirectArgumentArray->getValue()) > 0) {            
            $redirectUri = $this->request->getBaseUri() . \key($redirectArgumentArray->getValue());
        } else if ($redirectArgument->getValue()) {
            $redirectUri = $this->request->getBaseUri() . $redirectArgument->getValue();            
        } 
        if ($redirectUri) {
            $this->redirectToUri($redirectUri, $delay, $statusCode);            
        } else {
            parent::redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
        }
    }
	
	/**
	 * Shortcut for easier setting of property mapping configuration for nested objects 
	 * 
	 * @param string $argument
	 * @param string $propertyPath
	 * @param int $propertyFlags allow creation and/or modification, bitfield of self::CREATION / self::MODIFICATION
	 */
	protected function allowForProperty($argument,$propertyPath, $propertyFlags = self::EVERYTHING) {
		$data = $this->request->getArgument($argument);		
        $propertyMappingConfiguration = $this->arguments[$argument]->getPropertyMappingConfiguration();		
		/* @var $propertyMapping \TYPO3\FLOW3\MVC\Controller\MvcPropertyMappingConfiguration */		
		
		$propertyPathParts = explode('.', $propertyPath);
		$this->applyAllowForProperty($propertyMappingConfiguration, $data, $propertyPathParts, $propertyFlags);
	}
	
	/**
	 *
	 * @param \TYPO3\FLOW3\MVC\Controller\MvcPropertyMappingConfiguration $propertyMappingConfiguration
	 * @param array $data
	 * @param array $subPropertyPathParts
	 * @param int $propertyFlags allow creation and/or modification, bitfield of self::CREATION / self::MODIFICATION
	 */
	private function applyAllowForProperty($propertyMappingConfiguration, $data, $subPropertyPathParts, $propertyFlags) {
		if (empty($data)) {
			return;
		}
		$currentProperty = array_shift($subPropertyPathParts);

		// * = recursive call for all keys
		if ($currentProperty === '*') {
			foreach ($data as $currentProperty => $subData) {
				$subPropertyMappingConfiguration = $propertyMappingConfiguration->forProperty($currentProperty);
				$this->applyAllowForProperty($subPropertyMappingConfiguration, $subData, $subPropertyPathParts, $propertyFlags);					
			}
		} 
		// recursive call for given property
		else if (!empty($currentProperty)) {
			$subPropertyMappingConfiguration = $propertyMappingConfiguration->forProperty($currentProperty);
			$subData = isset($data[$currentProperty]) ? $data[$currentProperty] : array();
			$this->applyAllowForProperty($subPropertyMappingConfiguration, $subData, $subPropertyPathParts, $propertyFlags);
		} else {
			if ($propertyFlags && self::CREATION) {
				$propertyMappingConfiguration->setTypeConverterOption('TYPO3\FLOW3\Property\TypeConverter\PersistentObjectConverter', \TYPO3\FLOW3\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
			}
			if ($propertyFlags && self::MODIFICATION) {	
				$propertyMappingConfiguration->setTypeConverterOption('TYPO3\FLOW3\Property\TypeConverter\PersistentObjectConverter', \TYPO3\FLOW3\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);
			}			
		} 								
	}
	
    
    /**
     *
     * @param string $message 
     */
    public function addNotice($message) {        
        $this->flashMessageContainer->addMessage(new \TYPO3\FLOW3\Error\Notice($message));
    }

}

?>